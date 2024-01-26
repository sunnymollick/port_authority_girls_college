<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use View;
use DB;

class NewsController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      return view('backend.admin.frontend.news.all');
   }

   public function allNews(Request $request)
   {
      if ($request->ajax()) {
         DB::statement(DB::raw('set @rownum=0'));
         $news = News::orderby('created_at', 'desc')->get(['news.*', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
         return Datatables::of($news)
           ->addColumn('action', 'backend.admin.frontend.news.action')
           ->addColumn('file_path', function ($news) {
              return "<img src='" . asset($news->file_path) . "' width='80px'>";
           })
           ->addColumn('status', function ($news) {
              return $news->status ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Inactive</span>';
           })
           ->rawColumns(['action', 'file_path', 'status'])
           ->make(true);
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create(Request $request)
   {
      if ($request->ajax()) {
         $haspermision = auth()->user()->can('notice-create');
         if ($haspermision) {
            $view = View::make('backend.admin.frontend.news.create')->render();
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
         $haspermision = auth()->user()->can('news-create');
         if ($haspermision) {

            $rules = [
              'title' => 'required',
              'photo' => 'max:2048', // 2mb
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
               return response()->json([
                 'type' => 'error',
                 'errors' => $validator->getMessageBag()->toArray(),
               ]);
            } else {

               $file_path = 'assets/images/blog/default_news_a.jpg';
               $d_path = '';

               if ($request->hasFile('photo')) {
                  $extension = $request->file('photo')->getClientOriginalExtension();
                  if ($extension == "jpg" || $extension == "jpeg" || $extension == "png") {
                     if ($request->file('photo')->isValid()) {
                        $destinationPath = public_path('assets/uploads/blog');
                        $fileName = time() . '.' . $extension; // renameing image
                        $file_path = 'assets/uploads/blog/' . $fileName;
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

               if ($request->hasFile('document')) {
                  $dextension = $request->file('document')->getClientOriginalExtension();
                  if ($dextension == "pdf" || $dextension == "doc" || $dextension == "docx") {
                     if ($request->file('document')->isValid()) {
                        $dPath = public_path('assets/uploads/blog');
                        $dName = time() . '.' . $dextension; // renameing image
                        $d_path = 'assets/uploads/blog/' . $dName;
                        $request->file('document')->move($dPath, $d_path); // uploading file to given path

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


               $blog = new News;
               $blog->title = $request->input('title');
               $blog->description = $request->input('description');
               $blog->category = $request->input('category');
               $blog->uploaded_by = auth()->user()->id;
               $blog->file_path = $file_path;
               $blog->document = $d_path;
               $blog->save(); //
               return response()->json(['type' => 'success', 'message' => "Successfully Created"]);

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
    * @param  \App\Models\News $news
    * @return \Illuminate\Http\Response
    */
   public function show(Request $request, News $news)
   {
      if ($request->ajax()) {
         $haspermision = auth()->user()->can('notice-view');
         if ($haspermision) {
            $view = View::make('backend.admin.frontend.news.view', compact('news'))->render();
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
    * @param  \App\Models\News $news
    * @return \Illuminate\Http\Response
    */
   public function edit(Request $request, News $news)
   {
      if ($request->ajax()) {
         $haspermision = auth()->user()->can('notice-edit');
         if ($haspermision) {
            $view = View::make('backend.admin.frontend.news.edit', compact('news'))->render();
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
    * @param  \App\Models\News $news
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, News $news)
   {
      if ($request->ajax()) {
         $haspermision = auth()->user()->can('news-edit');
         if ($haspermision) {

            $rules = [
              'title' => 'required',
              'photo' => 'max:2048', // 2mb
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
               return response()->json([
                 'type' => 'error',
                 'errors' => $validator->getMessageBag()->toArray(),
               ]);
            } else {

               $file_path = $request->input('SelectedFileName');;
               $d_path = $request->input('SelecteddocumentName');;


               if ($request->hasFile('photo')) {
                  $extension = $request->file('photo')->getClientOriginalExtension();
                  if ($extension == "jpg" || $extension == "jpeg" || $extension == "png") {
                     if ($request->file('photo')->isValid()) {
                        $destinationPath = public_path('assets/uploads/blog');
                        $fileName = time() . '.' . $extension; // renameing image
                        $file_path = 'assets/uploads/blog/' . $fileName;
                        $request->file('photo')->move($destinationPath, $fileName);

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

               if ($request->hasFile('document')) {
                  $dextension = $request->file('document')->getClientOriginalExtension();
                  if ($dextension == "pdf" || $dextension == "doc" || $dextension == "docx") {
                     if ($request->file('document')->isValid()) {
                        $dPath = public_path('assets/uploads/blog');
                        $dName = time() . '.' . $dextension; // renameing image
                        $d_path = 'assets/uploads/blog/' . $dName;
                        $request->file('document')->move($dPath, $d_path); // uploading file to given path

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

               $blog = News::findOrFail($news->id);
               $blog->title = $request->input('title');
               $blog->description = $request->input('description');
               $blog->category = $request->input('category');
               $blog->uploaded_by = auth()->user()->id;
               $blog->file_path = $file_path;
               $blog->document = $d_path;
               $blog->status = $request->input('status');
               $blog->save(); //
               return response()->json(['type' => 'success', 'message' => "Successfully Updated"]);

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
    * @param  \App\Models\News $news
    * @return \Illuminate\Http\Response
    */
   public function destroy(Request $request, News $news)
   {
      if ($request->ajax()) {
         $haspermision = auth()->user()->can('notice-delete');
         if ($haspermision) {
            $news->delete();
            return response()->json(['type' => 'success', 'message' => 'Successfully Deleted']);
         } else {
            abort(403, 'Sorry, you are not authorized to access the page');
         }
      } else {
         return response()->json(['status' => 'false', 'message' => "Access only ajax request"]);
      }
   }

}
