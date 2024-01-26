@extends('frontend.layouts.fullwidth_master')
@section('title', ' Teachers')
@section('content')
    <div class="container p-top-50 p-bottom-50 p-right-40">
        <div class="section-title text-center">
            <h4>Our Honourable Teachers</h4> <hr/>
        </div>
        <div class="row">
			@foreach($teacher as $value)
			<div class="col-md-3 col-sm-12 p-bottom-50">
				<div class="member">
					<div class="member-pic set-bg" data-setbg="{{ asset($value->file_path) }}"></div>
					<h5>{{ $value->name }}</h5>
					<p>{{ $value->designation }}</p>
				</div>
			</div>
			@endforeach
		</div>
    </div>
@endsection
