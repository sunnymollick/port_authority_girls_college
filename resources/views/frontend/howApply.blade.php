@extends('frontend.layouts.right_master')
@section('title', 'How To Apply')
@section('content')
    <div class="row">
        <div class="section-title">
            <h4>{{$data->title}}</h4>
            <hr>
        </div>
        <p class="justify">
            {!! $data->description !!}
        </p>
    </div>
@endsection