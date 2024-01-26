@extends('frontend.layouts.right_master')
@section('title', 'Message')
@section('content')
    <div class="row">
        <div class="section-title">
            <h4>{{$data->title}}</h4>
            <hr>
        </div>
        <div class="post-item">
            <div class="post-content">
                <p class="justify">
                    <img src="{{ asset($data->file_path) }}" class="img-thumbnail"
                         width="200"
                         alt=""/>
                    {!! $data->description !!}
                </p>
            </div>
        </div>
    </div>
@endsection