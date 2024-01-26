@extends('frontend.layouts.right_master')
@section('title', 'Prospectus')
@section('content')
    <div class="row">
        <div class="section-title">
            <h4>Prospectus</h4>
            <hr>
        </div>
        <div>
            <object data="{{ asset('assets/images/prospectus.pdf') }}" type="application/pdf" width="100%" height="600px">
                <iframe src="https://docs.google.com/gview?url={{ asset('assets/images/prospectus.pdf') }}&embedded=true" style="width:100%; height:500px;" frameborder="0"></iframe>
                <br/><br/>
                <a class="btn btn-success" href="{{ asset('assets/images/prospectus.pdf') }}" download>Click here to Download</a>
                <br/><br/>
            </object>
        </div>
    </div>
@endsection