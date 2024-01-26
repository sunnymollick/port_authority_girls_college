@extends('frontend.layouts.right_master')
@section('title', 'View Details')
@section('content')
    <section class="blog-page-section">
        @if($news)
            <div class="col-md-12">
                <div class="post-item post-details">
                    @if( $news->category != 'Notice Board' && $news->category != 'Teacher Notice')
                        <img src="{{ asset($news->file_path) }}" class="post-thumb-full img-responsive"
                             alt="{{ $news->title }}"/>
                    @endif
                    <div class="post-content">
                        <h3>{{ $news->title }}</h3>
                        <div class="post-meta">
                            <span><i class="fa fa-calendar-o"></i> {{ date('dS F, Y', strtotime($news->created_at))  }}</span>
                            <span><i class="fa fa-user"></i> {{ $news->author? $news->author->name : ''}}</span>
                        </div><br/>
                        @if( $news->category == 'Notice Board' || $news->category == 'Teacher Notice')
                            <p class="news_details">
                                <object data="{{ asset($news->document) }}" width="100%"
                                        height="600px">
                                    <iframe
                                        src="https://docs.google.com/gview?url={{ asset($news->document) }}&embedded=true"
                                        style="width:100%; height:500px;" frameborder="0"></iframe>
                                </object>
                            </p>
                            <br/><br/>
                            <a class="btn btn-success" href="{{ asset($news->document) }}" download>Click here
                                to
                                Download</a>
                            <br/><br/>
                        @endif
                        <p class="news_details">{!! $news->description !!}  </p> <br/>
                         <br/><br/>
                        <br/>
                    </div>
                </div>
            </div>
        @else
            <div class="col-md-12">
                Sorry Nothing found!!!
            </div>
        @endif

    </section>
@endsection