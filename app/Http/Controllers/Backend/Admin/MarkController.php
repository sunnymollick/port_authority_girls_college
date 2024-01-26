<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helper\Academic;
use App\Models\Exam;
use App\Models\StdClass;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use View;
use DB;
use Excel;
use MPDF;

class MarkController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      $stdclass = StdClass::all();
      return view('backend.admin.mark.index', compact('stdclass'));
   }

   public function getMarks(Request $request)
   {
      if ($request->ajax()) {

         $class_id = $request->input('class_id');
         $section_id = $request->input('section_id');
         $exam_id = $request->input('exam_id');
         $subject_id = $request->input('subject_id');
         $year = config('running_session');

         if ($section_id == 'all') {
            $section_id = 'null';
         }

         $data = Academic::getSubjectMarks($class_id, $section_id, $subject_id, $exam_id, $year);
         $view = View::make('backend.admin.mark.view', compact('data'))->render();
         return response()->json(['html' => $view]);
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }


   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
      if ($request->ajax()) {
         $haspermision = auth()->user()->can('marks-create');
         if ($haspermision) {
            $exam_id = $request->input('exam_id');
            $class_id = $request->input('class_id');
            $section_id = $request->input('section_id');
            $subject_id = $request->input('subject_id');
            $student_code = $request->input('student_code');
            $teacher_id = $request->input('teacher_id');
            $uploader_id = auth()->user()->id;

            DB::beginTransaction();
            try {

               $bulk_data = [];

               DB::table('marks')
                 ->where('exam_id', $exam_id)
                 ->where('class_id', $class_id)
                 ->where('section_id', $section_id)
                 ->where('subject_id', $subject_id)
                 ->delete();


               foreach ($student_code as $std_code) {
                  $theory_marks = $request->input('theory_' . $std_code) ? $request->input('theory_' . $std_code) : 0;
                  $mcq_marks = $request->input('mcq_' . $std_code) ? $request->input('mcq_' . $std_code) : 0;
                  $practical_marks = $request->input('practical_' . $std_code) ? $request->input('practical_' . $std_code) : 0;
                  $ct_marks = $request->input('ct_' . $std_code) ? $request->input('ct_' . $std_code) : 0;
                  $total_marks = $theory_marks + $mcq_marks + $practical_marks;

                  $bulk_data[] = [
                    'exam_id' => $exam_id,
                    'student_code' => $std_code,
                    'subject_id' => $subject_id,
                    'class_id' => $class_id,
                    'section_id' => $section_id,
                    'total_marks' => $total_marks,
                    'theory_marks' => $theory_marks,
                    'mcq_marks' => $mcq_marks,
                    'practical_marks' => $practical_marks,
                    'ct_marks' => $ct_marks,
                    'teacher_id' => $teacher_id,
                    'uploader_id' => $uploader_id,
                    'year' => config('running_session'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                  ];

               }

               DB::table('marks')->insert($bulk_data);
               DB::commit();

            } catch (\Exception $e) {
               DB::rollback();
               return response()->json(['type' => 'error', 'message' => $e->getMessage()]);
            }


            return response()->json(['type' => 'success', 'message' => "Successfully Updated"]);

         } else {
            abort(403, 'Sorry, you are not authorized to access the page');
         }
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }

   public function import()
   {
      $haspermision = auth()->user()->can('marks-import');
      if ($haspermision) {
         $exams = Exam::where('year', config('running_session'))
           ->where('result_modification_last_date', '>=', date('Y-m-d'))
           ->orderBy('created_at', 'desc')->get();
         $stdclass = StdClass::all();
         return view('backend.admin.mark.import', compact('exams', 'stdclass'));
      } else {
         abort(403, 'Sorry, you are not authorized to access the page');
      }
   }

   public function importStore(Request $request)
   {
      if ($request->ajax()) {
         $haspermision = auth()->user()->can('marks-import');
         if ($haspermision) {
            $rules = [
              'exam_id' => 'required',
              'class_id' => 'required',
              'section_id' => 'required',
              'subject_id' => 'required',
              'excel_upload' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
               return response()->json([
                 'type' => 'danger',
                 'errors' => $validator->getMessageBag()->toArray()
               ]);
            } else {
               if ($request->hasFile('excel_upload')) {
                  $extension = $request->file('excel_upload')->getClientOriginalExtension();;
                  if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
                     $destinationPath = public_path('assets/uploads/marks_excel_uploads');
                     $fileName = date('d_m_Y_h_i_s_') . time() . '.' . $extension; // renameing image
                     $file_path = 'assets/uploads/marks_excel_uploads/' . $fileName;
                     $request->file('excel_upload')->move($destinationPath, $fileName); // uploading file to given path

                     $data = Excel::selectSheetsByIndex(0)->load(public_path($file_path), function ($reader) {
                     })->get();

                     if (!empty($data) && $data->count()) {

                        DB::beginTransaction();
                        try {

                           $exam_id = $request->input('exam_id');
                           $class_id = $request->input('class_id');
                           $section_id = $request->input('section_id');
                           $subject_id = $request->input('subject_id');
                           $uploader_id = auth()->user()->id;
                           $subject = Subject::where('id', $subject_id)->first();
                           $teacher_id = $subject->teacher_id;


                           $bulk_data = [];

                           DB::table('marks')
                             ->where('exam_id', $exam_id)
                             ->where('class_id', $class_id)
                             ->where('section_id', $section_id)
                             ->where('subject_id', $subject_id)
                             ->delete();


                           foreach ($data as $key => $value) {
                              if ("$value->student_code" != '') {

                                 $theory_marks = "$value->theory_marks" != '' ? "$value->theory_marks" : 0;
                                 $mcq_marks = "$value->mcq_marks" != '' ? "$value->mcq_marks" : 0;
                                 $practical_marks = "$value->practical_marks" != '' ? "$value->practical_marks" : 0;
                                 $ct_marks = "$value->ct_marks" != '' ? "$value->ct_marks" : 0;
                                 $total_marks = $theory_marks + $mcq_marks + $practical_marks;

                                 $bulk_data[] = [
                                   'exam_id' => $exam_id,
                                   'class_id' => $class_id,
                                   'section_id' => $section_id,
                                   'subject_id' => $subject_id,
                                   'student_code' => "$value->student_code",
                                   'total_marks' => $total_marks,
                                   'theory_marks' => $theory_marks,
                                   'mcq_marks' => $mcq_marks,
                                   'practical_marks' => $practical_marks,
                                   'ct_marks' => $ct_marks,
                                   'teacher_id' => $teacher_id,
                                   'uploader_id' => $uploader_id,
                                   'year' => config('running_session'),
                                   'created_at' => Carbon::now(),
                                   'updated_at' => Carbon::now(),
                                 ];

                              }
                           }

                           DB::table('marks')->insert($bulk_data);
                           DB::commit();
                        } catch (\Exception $e) {
                           DB::rollback();
                           return response()->json(['type' => 'error', 'message' => $e->getMessage()]);
                        }
                        return response()->json(['type' => 'success', 'message' => "Successfully Imported"]);

                     } else {
                        return response()->json([
                          'type' => 'danger',
                          'message' => "Error! No records in file"
                        ]);
                     }


                  } else {
                     return response()->json([
                       'type' => 'danger',
                       'message' => "Error! File type is not valid"
                     ]);
                  }
               }
            }
         } else {
            abort(403, 'Sorry, you are not authorized to access the page');
         }
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }


   public function exportExcelMarks(Request $request)
   {

      $class_id = $request->input('class_id');
      $section_id = $request->input('section_id');
      $exam_id = $request->input('exam_id');
      $subject_id = $request->input('subject_id');

      $year = config('running_session');

      $data = Academic::getSubjectMarks($class_id, $section_id, $subject_id, $exam_id, $year);


      $payload = array();
      $class = null;
      $section = null;

      if (count($data) > 0) {
         foreach ($data as $key => $value) {
            $payload[] = array(
              'Student Id' => $value->std_code,
              'Name' => $value->std_name,
              'Class' => $value->class_name,
              'Section' => $value->section,
              'Roll' => $value->std_roll,
              'Subject' => $value->sub_name,
              'Theory Marks' => $value->theory_marks,
              'MCQ Marks' => $value->mcq_marks,
              'Practical Marks' => $value->practical_marks,
              'CT Marks' => $value->ct_marks
            );


            $class = $value->class_name;
            $section = $value->section;
         }

      }

      return Excel::create('Students_' . $class . '_' . $section, function ($excel) use ($payload) {
         $excel->sheet('Students', function ($sheet) use ($payload) {
            $sheet->fromArray($payload);
         });
      })->download('xls');

   }

   public function exportPdfMarks(Request $request)
   {

      $class_id = $request->input('class_id');
      $section_id = $request->input('section_id');
      $exam_id = $request->input('exam_id');
      $subject_id = $request->input('subject_id');

      $year = config('running_session');
      if ($section_id == 'all') {
         $section_id = 'null';
      }

      $data = Academic::getSubjectMarks($class_id, $section_id, $subject_id, $exam_id, $year);

      $class = $section = $sub = "";

      if (count($data) > 0) {
         $class = $data[0]->class_name;
         $section = $data[0]->section;
         $sub = $data[0]->sub_name;
         $view = view('backend.admin.mark.export_pdf', compact('data'));
         $html = $view->render();
      } else {
         $html = "<html><body><p> Sorry!! no records have found</p></body></html>";
      }


      $pdf = MPDF::loadHTML($html, ['mode' => 'utf-8', 'format' => 'A4-P']);
      return $pdf->download('Marks_' . $class . '_' . $section . '_' . $sub . '.pdf');

   }

}
