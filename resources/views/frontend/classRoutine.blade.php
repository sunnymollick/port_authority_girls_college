@extends('frontend.layouts.right_master')
@section('title', 'Class Routine')
@section('content')
    <div class="row">
        <div class="section-title">
            <h4>{{$data->title}}</h4>
            <hr>
        </div>
        <p class="justify">
            <img src="{{ asset($data->file_path) }}" class="img-responsive img-thumbnail" width="100%"/>
            <br/>
        </p>
        <a href="{{ asset($data->file_path) }}" class="btn btn-success" download>Download</a>
    </div>
@endsection