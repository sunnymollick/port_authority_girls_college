<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use View;
use DB;

class PageController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      return view('backend.admin.frontend.pages.all');
   }

   public function allPages()
   {

      $can_edit = $can_delete = '';
      if (!auth()->user()->can('page-edit')) {
         $can_edit = "style='display:none;'";
      }
      if (!auth()->user()->can('page-delete')) {
         $can_delete = "style='display:none;'";
      }

      $pages = Page::orderBy('id', 'asc')->get();
      return Datatables::of($pages)
        ->addColumn('status', function ($page) {
           return $page->status ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Inactive</span>';
        })
        ->addColumn('action', function ($page) use ($can_edit, $can_delete) {
           $html = '<div class="btn-group">';
           $html .= '<a data-toggle="tooltip" id="' . $page->id . '" class="btn btn-xs btn-info margin-r-5 view" title="View"><i class="fa fa-eye fa-fw"></i> </a>';
           $html .= '<a data-toggle="tooltip" ' . $can_edit . '  id="' . $page->id . '" class="btn btn-xs btn-primary margin-r-5 edit" title="Edit"><i class="fa fa-edit fa-fw"></i> </a>';
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
         $haspermision = auth()->user()->can('page-create');
         if ($haspermision) {
            $view = View::make('backend.admin.frontend.pages.create')->render();
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
         $haspermision = auth()->user()->can('page-create');
         if ($haspermision) {

            $rules = [
              'slug' => 'required|unique:pages,slug',
              'title' => 'required',
              'photo' => 'max:2048|dimensions:max_width=12000,max_height=11000', // 2mb
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
               return response()->json([
                 'type' => 'error',
                 'errors' => $validator->getMessageBag()->toArray(),
               ]);
            } else {

               $upload_ok = 1;
               $file_path = 'assets/images/featured.png';

               if ($request->hasFile('photo')) {
                  $extension = $request->file('photo')->getClientOriginalExtension();
                  if ($extension == "jpg" || $extension == "jpeg" || $extension == "png") {
                     if ($request->file('photo')->isValid()) {
                        $destinationPath = public_path('assets/uploads/page');
                        $extension = $request->file('photo')->getClientOriginalExtension(); // getting image extension
                        $fileName = time() . '.' . $extension; // renameing image
                        $file_path = 'assets/uploads/page/' . $fileName;
                        $request->file('photo')->move($destinationPath, $fileName); // uploading file to given path
                        $upload_ok = 1;

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

               if ($upload_ok == 0) {
                  return response()->json([
                    'type' => 'error',
                    'message' => "<div class='alert alert-warning'>Sorry Failed</div>",
                  ]);
               } else {
                  $page = new Page;
                  $page->title = $request->input('title');
                  $page->description = $request->input('description');
                  $page->category = $request->input('category');
                  $page->uploaded_by = auth()->user()->id;
                  $page->file_path = $file_path;
                  $page->save(); //
                  return response()->json(['type' => 'success', 'message' => "Successfully Created"]);
               }
            }
         } else {
            abort(403, 'Sorry, you are not authorized to access the page');
         }
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }

   /**
    * Display the specified resource.
    *
    * @param  \App\Models\Page $page
    * @return \Illuminate\Http\Response
    */
   public function show(Request $request, Page $page)
   {
      if ($request->ajax()) {
         $haspermision = auth()->user()->can('page-view');
         if ($haspermision) {
            $view = View::make('backend.admin.frontend.pages.view', compact('page'))->render();
            return response()->json(['html' => $view]);
         } else {
            abort(403, 'Sorry, you are not authorized to access the page');
         }
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Page $page
    * @return \Illuminate\Http\Response
    */
   public function edit(Request $request, Page $page)
   {
      if ($request->ajax()) {
         $haspermision = auth()->user()->can('page-edit');
         if ($haspermision) {
            $view = View::make('backend.admin.frontend.pages.edit', compact('page'))->render();
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
    * @param  \App\Models\Page $page
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, Page $page)
   {
      if ($request->ajax()) {
         $haspermision = auth()->user()->can('page-edit');
         if ($haspermision) {

            $rules = [
              'slug' => 'required|unique:pages,slug,' . $page->id,
              'title' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
               return response()->json([
                 'type' => 'error',
                 'errors' => $validator->getMessageBag()->toArray(),
               ]);
            } else {

               if ($request->hasFile('photo')) {
                  $extension = $request->file('photo')->getClientOriginalExtension();
                  if ($extension == "jpg" || $extension == "jpeg" || $extension == "png") {
                     if ($request->file('photo')->isValid()) {
                        $destinationPath = public_path('assets/uploads/pages');
                        $extension = $request->file('photo')->getClientOriginalExtension(); // getting image extension
                        $fileName = time() . '.' . $extension; // renameing image
                        $file_path = 'assets/uploads/pages/' . $fileName;
                        $request->file('photo')->move($destinationPath, $fileName); // uploading file to given path
                        $upload_ok = 1;

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
               } else {
                  $upload_ok = 1;
                  $file_path = $request->input('SelectedFileName');
               }

               if ($upload_ok == 0) {
                  return response()->json([
                    'type' => 'error',
                    'message' => "<div class='alert alert-warning'>Sorry Failed</div>",
                  ]);
               } else {
                  $page = Page::findOrFail($page->id);
                  $page->slug = $request->input('slug');
                  $page->title = $request->input('title');
                  $page->description = $request->input('description');
                  $page->summery = $request->input('summery');
                  $page->uploaded_by = auth()->user()->id;
                  $page->file_path = $file_path;
                  $page->status = $request->input('status');
                  $page->save(); //
                  return response()->json(['type' => 'success', 'message' => "Successfully Updated"]);
               }
            }
         } else {
            abort(403, 'Sorry, you are not authorized to access the page');
         }
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Page $page
    * @return \Illuminate\Http\Response
    */
   public function destroy(Request $request, Page $page)
   {
      if ($request->ajax()) {
         $haspermision = auth()->user()->can('notice-delete');
         if ($haspermision) {
            $page->delete();
            return response()->json(['type' => 'success', 'message' => 'Successfully Deleted']);
         } else {
            abort(403, 'Sorry, you are not authorized to access the page');
         }
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }

}
