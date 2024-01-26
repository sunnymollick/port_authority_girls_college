<?php

namespace App\Http\Controllers\Backend\Admin;
use App\Http\Controllers\Controller;
use App\Models\Enroll;
use App\Models\StdParent;
use App\Models\Teacher;
use View;

class DashboardController extends Controller
{
   /**
    * Create a new controller instance.
    *
    * @return void
    */
   public function __construct()
   {
      $this->middleware('auth');
   }

   /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Contracts\Support\Renderable
    */
   public function index()
   {

      $students = Enroll::where('year', config('running_session'))->count();
      $teachers = Teacher::all()->count();
      $parents = StdParent::all()->count();
      return View::make('backend.admin.home', compact('teachers', 'students','parents'));
   }


}
