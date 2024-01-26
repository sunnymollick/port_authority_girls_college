<?php

namespace App\Http\Controllers\Frontend;

use App\Helper\Academic;
use App\Http\Controllers\Controller;
use App\Models\AcademicCalender;
use App\Models\AdmissionApplication;
use App\Models\AdmissionResult;
use App\Models\Download;
use App\Models\Enroll;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Page;
use App\Models\Slider;
use App\Models\StdClass;
use App\Models\Subject;
use App\Models\Syllabus;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use View;
use DB;
use MPDF;

class HomeController extends Controller
{

   // Home
   public function index()
   {
      //return redirect()->route('login');
      $teacher = Teacher::count();
      $students = Enroll::where('year', config('running_session'))->count();
      $birthday = DB::table('students')
        ->leftJoin('enrolls', 'enrolls.student_id', '=', 'students.id')
        ->leftJoin('std_classes', 'std_classes.id', '=', 'enrolls.class_id')
        ->leftJoin('sections', 'sections.id', '=', 'enrolls.section_id')
        ->select('students.name as std_name', 'students.std_code', 'students.file_path',
          'sections.name as section', 'std_classes.name as class_name')
        ->whereRaw('DATE_FORMAT(dob, "%m-%d") = ?', [Carbon::now()->format('m-d')])
        ->where('enrolls.year', config('running_session'))
        ->orderBy('std_classes.in_digit', 'asc')
        ->get();
      $latest_news = News::with('author')->where('category', 'Latest News')->where('status', 1)->orderby('created_at', 'desc')->take(4)->get();
      $teachers_notice = News::with('author')->where('category', 'Teacher Notice')->where('status', 1)->orderby('created_at', 'desc')->take(5)->get();
      $sliders = Slider::orderby('order', 'asc')->get();
      $data = Page::whereIn('slug', ['our-history', 'chairman-message', 'principal-message'])->get()->keyBy('slug');
      // dd($data);
      return View::make('frontend.index', compact('sliders', 'latest_news', 'teacher', 'students', 'birthday', 'data', 'teachers_notice'));
   }


   /* ===== About Us Start  ======== */

   // About Us
   public function ourHistory()
   {
      $data = Page::where('slug', 'our-history')->first();
      return View::make('frontend.history', compact('data'));
   }

   // Vision & Mission
   public function visionMission()
   {
      $data = Page::where('slug', 'mission-vision')->first();
      return View::make('frontend.missionVision', compact('data'));
   }

   // howApply
   public function howApply()
   {

      $data = Page::where('slug', 'how-to-apply')->first();
      return View::make('frontend.howApply', compact('data'));
   }

   // prospectus
   public function prospectus()
   {
      return View::make('frontend.prospectus');
   }


   // Chairman Message
   public function chairmanMessage()
   {
      $data = Page::where('slug', 'chairman-message')->first();
      return View::make('frontend.messagePresident', compact('data'));
   }

   // Principal Message
   public function principalMessage()
   {
      $data = Page::where('slug', 'principal-message')->first();
      return View::make('frontend.messagePrincipal', compact('data'));
   }

   // Management Committee
   public function managementCommittee()
   {
      return View::make('frontend.managementCommittee');
   }
   /* ===== About Us End  ======== */


   // Eligibility
   public function eligibility()
   {
      $data = Page::where('slug', 'eligibility')->first();
      return View::make('frontend.eligibility', compact('data'));
   }


   // Gallery
   public function gallery(Request $request)
   {
      $galleries = Gallery::orderby('created_at', 'desc')->paginate(16);
      if ($request->ajax()) {
         return view('frontend.galleryPag', compact('galleries'));
      }
      return view('frontend.gallery', compact('galleries'));
   }


   // Download
   public function downloads()
   {
      return View::make('frontend.download');
   }

   public function allDownloads(Request $request)
   {
      if ($request->ajax()) {
         DB::statement(DB::raw('set @rownum=0'));
         $downloads = Download::orderby('created_at', 'desc')->get(['downloads.*', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
         return Datatables::of($downloads)
           ->addColumn('file_path', function ($download) {
              return $download->file_path ? "<a class='btn btn-primary' href='" . asset($download->file_path) . "'>Download</a>" : '';
           })
           ->rawColumns(['file_path'])
           ->make(true);
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }

   // Contact Us
   public function contact()
   {
      return View::make('frontend.contact');
   }

   /* ===== Academic Start  ======== */

   // Teachers
   public function teachers()
   {
      $teacher = Teacher::orderBy('order','ASC')->get();
      return View::make('frontend.teachers', compact('teacher'));
   }


   // apiTest
   public function apiTest()
   {
      $stdclass = StdClass::all();
      return View('frontend.apiTest', compact('stdclass'));
   }


   // Student
   public function student()
   {
      $stdclass = StdClass::all();
      return View('frontend.student', compact('stdclass'));
   }

   public function getSections(Request $request, $class_id)
   {
      if ($request->ajax()) {

         $class = StdClass::findOrFail($class_id);
         $sections = $class->sections;
         if ($sections) {
            echo "<option value='' selected disabled> Select a section</option>";
            // echo "<option value='all'> All </option>";
            foreach ($sections as $section) {
               echo "<option  value='$section->id'> $section->name</option>";
            }
         }
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }

   public function getSubjects(Request $request, $class_id)
   {
      if ($request->ajax()) {

         $subjects = Subject::where('class_id', $class_id)->get();
         if ($subjects) {
            echo "<table id='subject' class='table table-striped table-hover table-bordered'>
                      <thead>
                        <tr>
                            <th> All Subjects </th>
                            <th> Code</th>
                        </tr>
                        </thead>
                        <tbody>";
            foreach ($subjects as $sub) {
               echo "<tr><td> <strong>$sub->name</strong></td><td> $sub->subject_code</td></tr>";
            }

            echo "</tbody>
                    </table>";
         }
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }

   public function allStudents(Request $request)
   {
      if ($request->ajax()) {

         $class_id = $request->input('class_id');
         $section_id = $request->input('section_id');
         if ($section_id == 'all') {
            $section_id = 'null';
         }

         DB::statement(DB::raw("SET @section_id = $section_id"));

         $students = DB::table('enrolls')
           ->join('students', 'students.id', '=', 'enrolls.student_id')
           ->select('students.*', 'enrolls.roll')
           ->where('enrolls.class_id', $class_id)
           ->where('enrolls.section_id', DB::raw('COALESCE(@section_id, enrolls.section_id)'))
           ->where('enrolls.year', config('running_session'))->get();
         return Datatables::of($students)
           ->addColumn('file_path', function ($student) {
              return "<img src='" . asset($student->file_path) . "' class='img-thumbnail' width='40px'>";
           })
           ->addColumn('roll', function ($student) {
              return $student->roll;
           })
           ->rawColumns(['action', 'file_path'])
           ->make(true);
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }

   // Class Routine
   public function classRoutine()
   {
      $data = Page::where('slug', 'class-routine')->first();
      return view('frontend.classRoutine', compact('data'));
   }

   public function getClassroutines(Request $request)
   {
      if ($request->ajax()) {

         $class_id = $request->input('class_id');
         $section_id = $request->input('section_id');
         $data['class_name'] = $request->input('class_name');
         $data['section_name'] = $request->input('section_name');

         $data['routines'] = $data['routines'] = Academic::generateClassRoutine($class_id, $section_id);
         $view = View::make('frontend.classRoutineContent', compact('data'))->render();
         return response()->json(['html' => $view]);
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }


   // Class Syllabus
   public function classSyllabus()
   {
      $stdclass = StdClass::all();
      return view('frontend.classSyllabus', compact('stdclass'));
   }

   public function getSyllabus(Request $request)
   {
      if ($request->ajax()) {

         $class_id = $request->input('class_id');
         $section_id = $request->input('section_id');

         DB::statement(DB::raw('set @rownum=0'));
         $syllabus = Syllabus::where('class_id', $class_id)->where('section_id', $section_id)->where('year', config('running_session'))->orderby('created_at', 'desc')->get(['syllabus.*', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
         return Datatables::of($syllabus)
           ->addColumn('file_path', function ($syllabus) {
              return $syllabus->file_path ? "<a class='btn btn-primary' href='" . asset($syllabus->file_path) . "'>Download</a>" : '';
           })
           ->addColumn('subject', function ($syllabus) {
              $subject = $syllabus->subject;
              return $subject ? $subject->name : '';
           })
           ->rawColumns(['action', 'file_path'])
           ->make(true);
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }

   }

   // Academic Calender
   public function academicCalender()
   {
      $calender = AcademicCalender::where('year', config('running_session'))->first();
      return View('frontend.academicCalender', compact('calender'));
   }

   // Academic Event Calender
   public function academicEvents()
   {
      $events = Event::get();
      return view('frontend.academicEvents', compact('events'));
   }

   // Event Details
   public function eventDetails(Request $request, Event $event)
   {
      if ($request->ajax()) {
         $view = View::make('backend.admin.event.view', compact('event'))->render();
         return response()->json(['html' => $view]);
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }

   // News Details
   public function viewNews(News $news)
   {
      return view('frontend.newsDetails', compact('news'));
   }

   // Rules and Regulation
   public function rulesRegulation()
   {
      $data = Page::where('slug', 'rules-regulations')->first();
      return View::make('frontend.rules', compact('data'));
   }

   // Academic Notice Board
   public function academicNotices(Request $request)
   {
      $notices = News::where('category', 'Notice Board')->orderby('created_at', 'desc')->paginate(5);
      $title = "Academic Notices";
      if ($request->ajax()) {
         return view('frontend.academicNoticesNewsPag', compact('notices'));
      }
      return view('frontend.academicNoticesNews', compact('notices', 'title'));
   }

   // Academic Latest News
   public function academicNews(Request $request)
   {
      $notices = News::where('category', 'Latest News')->orderby('created_at', 'desc')->paginate(5);
      $title = "Academic Latest News";
      if ($request->ajax()) {
         return view('frontend.academicNoticesNewsPag', compact('notices'));
      }
      return view('frontend.academicNoticesNews', compact('notices', 'title'));
   }


   /* ===== Careers Start  ======== */

   // Job circular
   public function jobCircular()
   {
      $jobs = News::where('category', 'Job News')->where('status', 1)->orderby('created_at', 'desc')->get();
      $total = $jobs->count();
      return view('frontend.jobCircular', compact('jobs', 'total'));
   }

   // Submit Resume
   public function submitResume()
   {
      return view('frontend.submitResume');
   }


   public function mailResume(Request $request)
   {

      if ($request->ajax()) {
         $rules = [
           'name' => 'required',
           'email' => 'required',
           'mobile' => 'required',
           'resume' => 'max:1024'
         ];

         $validator = Validator::make($request->all(), $rules);
         if ($validator->fails()) {
            return response()->json([
              'type' => 'error',
              'errors' => $validator->getMessageBag()->toArray()
            ]);
         } else {
            if ($request->hasFile('resume')) {
               $extension = Input::file('resume')->getClientOriginalExtension();;
               if ($extension == "doc" || $extension == "docx" || $extension == "pdf") {
                  $destinationPath = 'assets/uploads/resume'; // upload path
                  $extension = Input::file('resume')->getClientOriginalExtension(); // getting image extension
                  $fileName = time() . '.' . $extension; // renameing image
                  $file_path = 'assets/uploads/resume/' . $fileName;
                  //  Input::file('resume')->move($destinationPath, $fileName); // uploading file to given path
                  $upload_ok = 1;

               } else {
                  return response()->json([
                    'type' => 'error',
                    'message' => "<div class='alert alert-warning'>File type is not valid</div>"
                  ]);
               }
            } else {
               return response()->json([
                 'type' => 'error',
                 'message' => "<div class='alert alert-warning'>No file uploaded</div>"
               ]);
            }

            if ($upload_ok == 0) {
               return response()->json([
                 'type' => 'error',
                 'message' => "<div class='alert alert-warning'>Sorry Failed</div>"
               ]);
            } else {
               $data = array(
                 'name' => $request->name,
                 'email' => $request->email,
                 'mobile' => $request->mobile,
                 'job_position' => $request->job_position,
                 'cover_letter' => $request->cover_letter
               );
               $files = $request->file('resume');
               \Mail::send('frontend.mailTemplate', compact('data'), function ($message) use ($data, $files) {
                  $message->from($data['email']);
                  $message->to('w3pocl@gmail.com')->subject($data['job_position'] . ' - ' . $data['name']);
                  $message->attach($files->getRealPath(), array(
                      'as' => $files->getClientOriginalName(),
                      'mime' => $files->getMimeType())
                  );
               });
               Input::file('resume')->move($destinationPath, $fileName);
               return response()->json(['type' => 'success', 'message' => "<div class='alert alert-success'>Successfully Uploaded</div>"]);
            }
         }
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }

   /* ===== Academic Start  ======== */


   public function onlineAdmission()
   {
      $stdclass = StdClass::all();
      return View::make('frontend.admission_form', compact('stdclass'));
   }

   public function onlineAdmissionStore(Request $request)
   {
      if ($request->ajax()) {

         $rules = [
           'applicant_name_en' => 'required',
           'applicant_name_bn' => 'required',
           'father_name_en' => 'required',
           'admitted_class' => 'required'
         ];

         $validator = Validator::make($request->all(), $rules);
         if ($validator->fails()) {
            return response()->json([
              'type' => 'error',
              'errors' => $validator->getMessageBag()->toArray()
            ]);
         } else {

            $rows = DB::table('admission_applications')
              ->where('applicant_name_en', $request->input('applicant_name_en'))
              ->where('applicant_name_bn', $request->input('applicant_name_bn'))
              ->where('father_name_en', $request->input('father_name_en'))
              ->where('father_name_bn', $request->input('father_name_bn'))
              ->count();

            if ($rows == 0) {

               $file_path = 'assets/images/no.png';

               if ($request->hasFile('photo')) {
                  $extension = $request->file('photo')->getClientOriginalExtension();
                  if ($extension == "jpg" || $extension == "jpeg" || $extension == "png") {
                     if ($request->file('photo')->isValid()) {
                        $destinationPath = public_path('assets/uploads/admission');
                        $fileName = time() . '.' . $extension; // renameing image
                        $file_path = 'assets/uploads/admission/' . $fileName;
                        $request->file('photo')->move($destinationPath, $fileName); // uploading file to given path

                     } else {
                        return response()->json([
                          'type' => 'error',
                          'message' => "<div class='alert alert-warning'>File is not valid</div>",
                        ]);
                     }
                  } else {
                     return response()->json([
                       'type' => 'error',
                       'message' => "<div class='alert alert-warning'>Error! File type is not valid</div>",
                     ]);
                  }
               }

               DB::beginTransaction();

               try {


                  $last_inserted = AdmissionApplication::where('applied_year', config('running_session'))->whereMonth('created_at', Carbon::now()->month)->orderBy('id', 'DESC')->first();
                  if ($last_inserted) {
                     $sum = '1001' + $last_inserted->id;
                  }
                  $applicant_id = $last_inserted ? 'AID' . date('Ym') . $sum : 'AID' . date('ym') . '1001';

                  $application_no = $last_inserted ? date('Ym') . $sum : date('Ym') . '1001';


                  $admission = new AdmissionApplication();
                  $admission->applicant_id = $applicant_id;
                  $admission->applicant_form_no = $application_no;
                  $admission->admitted_class = $request->input('admitted_class');
                  $admission->admitted_section = $request->input('admitted_section');
                  $admission->applicant_name_en = $request->input('applicant_name_en');
                  $admission->applicant_name_bn = $request->input('applicant_name_bn');
                  $admission->mobile = $request->input('mobile');
                  $admission->father_name_en = $request->input('father_name_en');
                  $admission->father_name_bn = $request->input('father_name_bn');
                  $admission->father_mobile = $request->input('father_mobile');
                  $admission->mother_name_en = $request->input('mother_name_en');
                  $admission->mother_name_bn = $request->input('mother_name_bn');
                  $admission->mother_mobile = $request->input('mother_mobile');
                  $admission->std_relation_port_officer = $request->input('std_relation_port_officer');
                  $admission->port_officer_name = $request->input('port_officer_name');
                  $admission->port_officer_working_place = $request->input('port_officer_working_place');
                  $admission->port_officer_designation = $request->input('port_officer_designation');
                  $admission->alternet_gurdian_name = $request->input('alternet_gurdian_name');
                  $admission->alternet_gurdian_phone = $request->input('alternet_gurdian_phone');
                  $admission->alternet_gurdian_address = $request->input('alternet_gurdian_address');
                  $admission->alternet_gurdian_relation = $request->input('alternet_gurdian_relation');
                  $admission->present_village = $request->input('present_village');
                  $admission->present_post_office = $request->input('present_post_office');
                  $admission->present_thana = $request->input('present_thana');
                  $admission->present_district = $request->input('present_district');
                  $admission->parmanent_village = $request->input('parmanent_village');
                  $admission->parmanent_post_office = $request->input('parmanent_post_office');
                  $admission->parmanent_thana = $request->input('parmanent_thana');
                  $admission->parmanent_district = $request->input('parmanent_district');
                  $admission->email = $request->input('email');
                  $admission->dob = $request->input('dob');
                  $admission->nationality = $request->input('nationality');
                  $admission->religion = $request->input('religion');
                  $admission->fourth_subject_name = $request->input('fourth_subject_name');
                  $admission->fourth_subject_code = $request->input('fourth_subject_code');
                  $admission->optional_subject_name = $request->input('optional_subject_name');
                  $admission->optional_subject_code = $request->input('optional_subject_code');
                  $admission->passed_school_name = $request->input('passed_school_name');
                  $admission->exam_roll = $request->input('exam_roll');
                  $admission->reg_no = $request->input('reg_no');
                  $admission->exam_board = $request->input('exam_board');
                  $admission->exam_session = $request->input('exam_session');
                  $admission->passed_year = $request->input('passed_year');
                  $admission->gpa_without_fourth = $request->input('gpa_without_fourth');
                  $admission->fourth_sub_gpa = $request->input('fourth_sub_gpa');
                  $admission->grand_gpa = $request->input('grand_gpa');
                  $admission->file_path = $file_path;
                  $admission->status = 2;
                  $admission->applied_year = config('running_session');
                  $admission->aggrement = $request->input('aggrement');
                  $admission->save();


                  // readable subject inserting

                  $sub_name = $request->input('sub_name');
                  $sub_code = $request->input('sub_code');
                  $total_subject = count(array_filter($sub_name));
                  $bulk_readable_subject = [];

                  if ($total_subject != 0) {

                     for ($i = 0; $i < $total_subject; $i++) {
                        $s_name = $sub_name[$i];
                        $s_code = $sub_code[$i];

                        $bulk_readable_subject[] = [
                          'sub_name' => $s_name,
                          'sub_code' => $s_code,
                          'admission_id' => $admission->id
                        ];

                     }
                  }

                  DB::table('admission_readable_subjects')->insert($bulk_readable_subject);

                  // ssc result inserting

                  $ssc_sub_name = $request->input('ssc_sub_name');
                  $grade = $request->input('grade');
                  $gpa = $request->input('gpa');

                  $total_ssc_sub_name = count(array_filter($ssc_sub_name));
                  $bulk_ssc_subject = [];

                  if ($total_ssc_sub_name != 0) {

                     for ($i = 0; $i < $total_ssc_sub_name; $i++) {
                        $ssc_name = $ssc_sub_name[$i];
                        $ssc_grade = $grade[$i];
                        $ssc_gpa = $gpa[$i];

                        $bulk_ssc_subject[] = [
                          'ssc_sub_name' => $ssc_name,
                          'grade' => $ssc_grade,
                          'gpa' => $ssc_gpa,
                          'admission_id' => $admission->id
                        ];

                     }
                  }

                  DB::table('admission_ssc_results')->insert($bulk_ssc_subject);

                  DB::commit();
                  return response()->json(['type' => 'success',
                    'message' => "<div class='alert alert-success' style='color: #fff'>Successfully Submitted.</div> <br/> <a class='btn btn-success' target='_blank' href='/admissionPrint/$admission->id'>Download Admission Form</a>"]);

               } catch (\Exception $e) {
                  DB::rollback();
                  return response()->json(['type' => 'error', 'message' => "<div class='alert alert-success'>Sorry! Form not submitted</div>"]);
               }
            } else {
               return response()->json(['type' => 'error', 'message' => "<div class='alert alert-warning'> Already applied with same information</div>"]);

            }

         }

      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }


   public function admissionPrint($id)
   {
      $admissionApplication = AdmissionApplication::where('id', $id)->first();
      $view = View::make('frontend.admissionPrint', compact('admissionApplication'));
      $pdf = MPDF::loadHTML($view);
      return $pdf->stream('document.pdf');

   }

   public function admissionPrint2($id)
   {
      $admissionApplication = AdmissionApplication::where('id', $id)->first();
      $view = View::make('frontend.admissionPrint', compact('admissionApplication'));

      $html = '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">';
      $html .= $view->render();
      $html .= '</html>';
      $pdf = MPDF::loadHTML($html, ['mode' => 'utf-8', 'format' => 'A4']);
      return $pdf->download('Application_' . $admissionApplication->applicant_id . '.pdf');

   }

   public function admissionResult()
   {
      $result = AdmissionResult::where('year', config('running_session'))->orderBy('id', 'desc')->get();
      return View::make('frontend.admission_result', compact('result'));
   }

}
