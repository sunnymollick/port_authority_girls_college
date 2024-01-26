@extends('frontend.layouts.right_master')
@section('title', 'Academic Calendar')
@section('content')
    <h4>Academic Calender</h4>
    <hr/>
    <h5>Academic Calender of {{config('running_session') . ' Session'}}</h5> <br/>
    
    <div class="card">
        <embed src="{{ asset($calender->file_path) }}" width="100%" height="600px" />
    </div>
    <br/>
    <a class="btn btn-success" href="{{ asset($calender->file_path) }}" target="_blank" download> Download </a>
     <br/> <br/>
@endsection