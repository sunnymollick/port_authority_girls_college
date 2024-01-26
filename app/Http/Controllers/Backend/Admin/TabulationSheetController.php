<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helper\GenerateMarksheet;
use App\Models\Exam;
use App\Models\StdClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use View;
use DB;
use MPDF;

class TabulationSheetController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      $stdclass = StdClass::all();
      return view('backend.admin.tabulation_sheet.index', compact('stdclass'));
   }

   public function summeryResult(Request $request)
   {
      if ($request->ajax()) {

         $class_id = $request->input('class_id');
         $section_id = $request->input('section_id');
         $exam_id = $request->input('exam_id');
         $year = config('running_session');

         $data = array();
         $data['class_id'] = $class_id;
         $data['section_id'] = $section_id;
         $data['exam_id'] = $exam_id;
         $data['class_name'] = $request->input('class_name');
         $data['section_name'] = $request->input('section_name');
         $data['exam_name'] = $request->input('exam_name');
         $data['year'] = $year;


         $data['result'] = GenerateMarksheet::generateSummeryResult($exam_id, $class_id, $section_id, $year);

         $view = View::make('backend.admin.tabulation_sheet.summery', compact('data'))->render();
         return response()->json(['html' => $view]);
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }


   public function viewMarksheet(Request $request)
   {
      if ($request->ajax()) {

         $class_id = $request->input('class_id');
         $section_id = $request->input('section_id');
         $exam_id = $request->input('exam_id');
         $student_code = $request->input('student_code');
         $std_session = $request->input('std_session');
         $year = config('running_session');

         $exam = Exam::where('id', $exam_id)->first();

         $data = array();
         $data['student_code'] = $student_code;
         $data['std_session'] = $std_session;
         $data['student_name'] = $request->input('student_name');
         $data['std_roll'] = $request->input('std_roll');
         $data['class_id'] = $class_id;
         $data['section_id'] = $section_id;
         $data['exam_id'] = $exam_id;
         $data['class_name'] = $request->input('class_name');
         $data['section_name'] = $request->input('section_name');
         $data['exam_name'] = $request->input('exam_name');
         $data['year'] = config('running_session');
         $data['has_ct'] = $exam->ct_marks_percentage;
         $data['mmp'] = $exam->main_marks_percentage;

         $data['result'] = GenerateMarksheet::generateMarksheetResult($exam_id, $class_id, $section_id, $student_code, $year);

         // dd($data['result']);

         $view = View::make('backend.admin.tabulation_sheet.marksheet', compact('data'))->render();
         return response()->json(['html' => $view]);
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */


   public function printMarksheet(Request $request)
   {

      $class_id = Input::get('class_id');
      $section_id = Input::get('section_id');
      $exam_id = Input::get('exam_id');
      $student_code = Input::get('student_code');
      $std_session = Input::get('std_session');
      $year = config('running_session');

      $exam = Exam::where('id', $exam_id)->first();

      $data = array();
      $data['student_code'] = $student_code;
      $data['std_session'] = $std_session;
      $data['student_name'] = $request->input('student_name');
      $data['std_roll'] = $request->input('std_roll');
      $data['class_id'] = $class_id;
      $data['section_id'] = $section_id;
      $data['exam_id'] = $exam_id;
      $data['class_name'] = $request->input('class_name');
      $data['section_name'] = $request->input('section_name');
      $data['exam_name'] = $request->input('exam_name');
      $data['year'] = config('running_session');
      $data['has_ct'] = $exam->ct_marks_percentage;
      $data['mmp'] = $exam->main_marks_percentage;

      $data['total_std'] = Input::get('total_std');
      $data['total_atd'] = Input::get('total_atd');
      $data['total_wd'] = Input::get('total_wd');
      $data['position'] = Input::get('position');

      $data['result'] = GenerateMarksheet::generateMarksheetResult($exam_id, $class_id, $section_id, $student_code, $year);
      $view = View::make('backend.admin.tabulation_sheet.printMarksheet', compact('data'));

      $html = '<!DOCTYPE html><html lang="en">';
      $html .= $view->render();
      $html .= '</html>';
      $pdf = MPDF::loadHTML($html,['mode' => 'utf-8', 'format' => 'A4-L']);
      return $pdf->download('Marksheet_' . $data['student_code'] . '_' . $data['class_name'] . '.pdf');

   }
}
