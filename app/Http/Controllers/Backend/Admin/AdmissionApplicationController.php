<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\AdmissionApplication;
use App\Http\Controllers\Controller;
use App\Models\StdClass;
use App\Models\Section;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

use View;
use DB;

class AdmissionApplicationController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      return view('backend.admin.exam.online_application.all');
   }

   public function allApplications()
   {

      $can_edit = $can_delete = '';
      if (!auth()->user()->can('online-application-edit')) {
         $can_edit = "style='display:none;'";
      }
      if (!auth()->user()->can('online-application-delete')) {
         $can_delete = "style='display:none;'";
      }

      $application = AdmissionApplication::with('stdclass', 'section')->where('applied_year',config('running_session'))->orderBy('id', 'desc')->get();
      return Datatables::of($application)
        ->addColumn('class_name', function ($application) {
           $class = $application->stdclass;
           return $class ? $class->name : '';
        })
        ->addColumn('section_name', function ($application) {
           $section = $application->section;
           return $section ? $section->name : '';
        })
        ->addColumn('action', function ($application) use ($can_edit, $can_delete) {
           $html = '<div class="btn-group">';
           $html .= '<a data-toggle="tooltip" id="' . $application->id . '" class="btn btn-xs btn-info margin-r-5 view" title="View"><i class="fa fa-eye fa-fw"></i> </a>';
           $html .= '<a data-toggle="tooltip" ' . $can_edit . '  id="' . $application->id . '" class="btn btn-xs btn-primary margin-r-5 edit" title="Edit"><i class="fa fa-edit fa-fw"></i> </a>';
           $html .= '<a data-toggle="tooltip" ' . $can_delete . ' id="' . $application->id . '" class="btn btn-xs btn-danger margin-r-5 delete" title="Delete"><i class="fa fa-trash-o fa-fw"></i> </a>';
           $html .= '</div>';
           return $html;
        })
        ->rawColumns(['action', 'status'])
        ->addIndexColumn()
        ->make(true);
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create(Request $request)
   {
      if ($request->ajax()) {
         $haspermision = auth()->user()->can('online-application-create');
         if ($haspermision) {
            $stdclass = StdClass::all();
            $view = View::make('backend.admin.exam.online_application.create', compact('stdclass'))->render();
            return response()->json(['html' => $view]);
         } else {
            abort(403, 'Sorry, you are not authorized to access the page');
         }
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
            $upload_ok = 1;

            if ($request->hasFile('photo')) {
               $extension = $request->file('photo')->getClientOriginalExtension();;
               if ($extension == "jpg" || $extension == "jpeg" || $extension == "png") {
                  if ($request->file('photo')->isValid()) {
                     $destinationPath = public_path('assets/uploads/admission_upload');
                     $extension = $request->file('photo')->getClientOriginalExtension(); // getting image extension
                     $fileName = time() . '.' . $extension; // renameing image
                     $file_path = 'assets/uploads/admission_upload/' . $fileName;
                     $request->file('photo')->move($destinationPath, $fileName); // uploading file to given path
                     $upload_ok = 1;

                  } else {
                     return response()->json([
                       'type' => 'error',
                       'message' => "<div class='alert alert-warning'>File is not valid</div>"
                     ]);
                  }
               } else {
                  return response()->json([
                    'type' => 'error',
                    'message' => "<div class='alert alert-warning'>Error! File type is not valid</div>"
                  ]);
               }
            } else {
               return response()->json([
                 'type' => 'error',
                 'message' => "<div class='alert alert-warning'>Error! File not selected</div>"
               ]);
            }
            if ($upload_ok == 0) {
               return response()->json([
                 'type' => 'error',
                 'message' => "<div class='alert alert-warning'>Sorry Failed</div>"
               ]);
            } else {
               $rows = DB::table('admission_applications')
                 ->where('applicant_name_en', $request->input('applicant_name_en'))
                 ->where('applicant_name_bn', $request->input('applicant_name_bn'))
                 ->where('father_name_en', $request->input('father_name_en'))
                 ->where('father_name_bn', $request->input('father_name_bn'))
                 ->count();
               if ($rows == 0) {

                  $last_inserted = AdmissionApplication::where('applied_year', config('running_session'))->whereMonth('created_at', Carbon::now()->month)->orderBy('id', 'DESC')->first();
                  if ($last_inserted) {
                     $sum = '001' + $last_inserted->id;
                  }
                  $applicant_id = $last_inserted ? 'AID' . date('ym') . $sum : 'AID' . date('ym') . '001';

                  $application_no = $last_inserted ? date('Ym') . $sum : date('Ym') . '001';

                  $admission = new AdmissionApplication();
                  $admission->applicant_id = $applicant_id;
                  $admission->applicant_form_no = $application_no;
                  $admission->applicant_name_en = $request->input('applicant_name_en');
                  $admission->applicant_name_bn = $request->input('applicant_name_bn');
                  $admission->father_name_en = $request->input('father_name_en');
                  $admission->father_name_bn = $request->input('father_name_bn');
                  $admission->mother_name_en = $request->input('mother_name_en');
                  $admission->mother_name_bn = $request->input('mother_name_bn');
                  $admission->father_qualification = $request->input('father_qualification');
                  $admission->mother_qualification = $request->input('mother_qualification');
                  $admission->father_occupation = $request->input('father_occupation');
                  $admission->mother_occupation = $request->input('mother_occupation');
                  $admission->father_occupation_post_name = $request->input('father_occupation_post_name');
                  $admission->father_occupation_org_name = $request->input('father_occupation_org_name');
                  $admission->father_occupation_business_type = $request->input('father_occupation_business_type');
                  $admission->alternet_gurdian_name = $request->input('alternet_gurdian_name');
                  $admission->alternet_gurdian_phone = $request->input('alternet_gurdian_phone');
                  $admission->alternet_gurdian_address = $request->input('alternet_gurdian_address');
                  $admission->yearly_income = $request->input('yearly_income');
                  $admission->mobile = $request->input('mobile');
                  $admission->religion = $request->input('religion');
                  $admission->dob = $request->input('dob');
                  $admission->present_village = $request->input('present_village');
                  $admission->present_post_office = $request->input('present_post_office');
                  $admission->present_thana = $request->input('present_thana');
                  $admission->present_district = $request->input('present_district');
                  $admission->parmanent_village = $request->input('parmanent_village');
                  $admission->parmanent_post_office = $request->input('parmanent_post_office');
                  $admission->parmanent_thana = $request->input('parmanent_thana');
                  $admission->parmanent_district = $request->input('parmanent_district');
                  $admission->email = $request->input('email');
                  $admission->nationality = $request->input('nationality');
                  $admission->children_in_school = $request->input('children_in_school');
                  $admission->children_name = $request->input('children_name');
                  $admission->children_class = $request->input('children_class');
                  $admission->children_section = $request->input('children_section');
                  $admission->admitted_class = $request->input('admitted_class');
                  $admission->admitted_section = $request->input('admitted_section');
                  $admission->old_school_name = $request->input('old_school_name');
                  $admission->old_class = $request->input('old_class');
                  $admission->blood_group = $request->input('blood_group');
                  $admission->status = 2;
                  $admission->file_path = $file_path;
                  $admission->applied_year = config('running_session');
                  $admission->aggrement = $request->input('aggrement');
                  $admission->save(); //
                  return response()->json(['type' => 'success', 'message' => "Successfully Submitted"]);
               } else {
                  return response()->json(['type' => 'error', 'message' => "<div class='alert alert-warning'> Already applied with same information</div>"]);

               }
            }
         }

      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }

   /**
    * Display the specified resource.
    *
    * @param  \App\Models\AdmissionApplication $admissionApplication
    * @return \Illuminate\Http\Response
    */
   public function show(Request $request, AdmissionApplication $admissionApplication)
   {
      if ($request->ajax()) {
         $view = View::make('backend.admin.exam.online_application.view', compact('admissionApplication'))->render();
         return response()->json(['html' => $view]);
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\AdmissionApplication $admissionApplication
    * @return \Illuminate\Http\Response
    */
  public function edit(Request $request, AdmissionApplication $admissionApplication)
   {
      if ($request->ajax()) {
         $haspermision = auth()->user()->can('online-application-edit');
         if ($haspermision) {
            $stdclass = StdClass::all();
            $section = Section::where('class_id', $admissionApplication->admitted_class)->get();
            $view = View::make('backend.admin.exam.online_application.edit', compact('admissionApplication', 'stdclass', 'section'))->render();
            return response()->json(['html' => $view]);
         } else {
            abort(403, 'Sorry, you are not authorized to access the page');
         }
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request $request
    * @param  \App\Models\AdmissionApplication $admissionApplication
    * @return \Illuminate\Http\Response
    */
  public function update(Request $request, AdmissionApplication $admissionApplication)
   {
      if ($request->ajax()) {

         AdmissionApplication::findOrFail($admissionApplication->id);

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

            $file_path = $request->input('SelectedFileName');

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

               $admissionApplication->admitted_class = $request->input('admitted_class');
               $admissionApplication->admitted_section = $request->input('admitted_section');
               $admissionApplication->applicant_name_en = $request->input('applicant_name_en');
               $admissionApplication->applicant_name_bn = $request->input('applicant_name_bn');
               $admissionApplication->mobile = $request->input('mobile');
               $admissionApplication->father_name_en = $request->input('father_name_en');
               $admissionApplication->father_name_bn = $request->input('father_name_bn');
               $admissionApplication->father_mobile = $request->input('father_mobile');
               $admissionApplication->mother_name_en = $request->input('mother_name_en');
               $admissionApplication->mother_name_bn = $request->input('mother_name_bn');
               $admissionApplication->mother_mobile = $request->input('mother_mobile');
               $admissionApplication->std_relation_port_officer = $request->input('std_relation_port_officer');
               $admissionApplication->port_officer_name = $request->input('port_officer_name');
               $admissionApplication->port_officer_working_place = $request->input('port_officer_working_place');
               $admissionApplication->port_officer_designation = $request->input('port_officer_designation');
               $admissionApplication->alternet_gurdian_name = $request->input('alternet_gurdian_name');
               $admissionApplication->alternet_gurdian_phone = $request->input('alternet_gurdian_phone');
               $admissionApplication->alternet_gurdian_address = $request->input('alternet_gurdian_address');
               $admissionApplication->alternet_gurdian_relation = $request->input('alternet_gurdian_relation');
               $admissionApplication->present_village = $request->input('present_village');
               $admissionApplication->present_post_office = $request->input('present_post_office');
               $admissionApplication->present_thana = $request->input('present_thana');
               $admissionApplication->present_district = $request->input('present_district');
               $admissionApplication->parmanent_village = $request->input('parmanent_village');
               $admissionApplication->parmanent_post_office = $request->input('parmanent_post_office');
               $admissionApplication->parmanent_thana = $request->input('parmanent_thana');
               $admissionApplication->parmanent_district = $request->input('parmanent_district');
               $admissionApplication->email = $request->input('email');
               $admissionApplication->dob = $request->input('dob');
               $admissionApplication->nationality = $request->input('nationality');
               $admissionApplication->religion = $request->input('religion');
               $admissionApplication->fourth_subject_name = $request->input('fourth_subject_name');
               $admissionApplication->fourth_subject_code = $request->input('fourth_subject_code');
               $admissionApplication->optional_subject_name = $request->input('optional_subject_name');
               $admissionApplication->optional_subject_code = $request->input('optional_subject_code');
               $admissionApplication->passed_school_name = $request->input('passed_school_name');
               $admissionApplication->exam_roll = $request->input('exam_roll');
               $admissionApplication->reg_no = $request->input('reg_no');
               $admissionApplication->exam_board = $request->input('exam_board');
               $admissionApplication->exam_session = $request->input('exam_session');
               $admissionApplication->passed_year = $request->input('passed_year');
               $admissionApplication->gpa_without_fourth = $request->input('gpa_without_fourth');
               $admissionApplication->fourth_sub_gpa = $request->input('fourth_sub_gpa');
               $admissionApplication->grand_gpa = $request->input('grand_gpa');
               $admissionApplication->file_path = $file_path;
               $admissionApplication->status = 2;
               $admissionApplication->applied_year = config('running_session');
               $admissionApplication->aggrement = $request->input('aggrement');
               $admissionApplication->save();


               // readable subject inserting

               DB::table('admission_readable_subjects')->where('admission_id', $admissionApplication->id)->delete();

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
                       'admission_id' => $admissionApplication->id
                     ];

                  }
               }

               DB::table('admission_readable_subjects')->insert($bulk_readable_subject);

               // ssc result inserting

               DB::table('admission_ssc_results')->where('admission_id', $admissionApplication->id)->delete();

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
                       'admission_id' => $admissionApplication->id
                     ];

                  }
               }

               DB::table('admission_ssc_results')->insert($bulk_ssc_subject);

               DB::commit();
               return response()->json(['type' => 'success',
                 'message' => "<div class='alert alert-success' style='color: #fff'>Successfully Submitted.</div>"]);

            } catch (\Exception $e) {
               DB::rollback();
               return response()->json(['type' => 'error', 'message' => "<div class='alert alert-success'>Sorry! Form not submitted</div>"]);
            }

         }

      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\AdmissionApplication $admissionApplication
    * @return \Illuminate\Http\Response
    */
   public function destroy(Request $request, AdmissionApplication $admissionApplication)
   {
      if ($request->ajax()) {
         $haspermision = auth()->user()->can('online-application-delete');
         if ($haspermision) {
            $admissionApplication->delete();
            return response()->json(['type' => 'success', 'message' => 'Successfully Deleted']);
         } else {
            abort(403, 'Sorry, you are not authorized to access the page');
         }
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }
}
