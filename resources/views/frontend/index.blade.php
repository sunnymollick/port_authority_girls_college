@extends('frontend.layouts.master')
@section('title', 'Home')
@section('content')
    <!--=== Slider Start ===---->

    @include('frontend.layouts.slider')

    <!--=== Slider End ===---->

    <!--=== Welcome Chairman Message and Notice Board Start ===---->
    <div class="container p-top-50 p-bottom-50 p-right-40">
        <div class="row">
            <!-- Home message -->
            <div class="col-md-3 col-sm-12">
                <div class="card">
                    <div class="card-body message_body">
                        <h4>{{ $data['chairman-message']->title }} </h4>
                        <hr/>
                        <p class="justify">
                            <img src="{{ asset($data['chairman-message']->file_path) }}"
                                 class="img-thumbnail text-center"
                                 width="240"
                                 alt=""/><br/>
                            {!! $data['chairman-message']->summery !!} <br/>
                            <a href="{{ URL :: to('/chairmanMessage') }}" class="text-green">Read More ></a>
                        </p>
                    </div>
                </div>
                <div class="card"><br/>
                    <div class="card-body message_body">
                        <h4>{{ $data['principal-message']->title }}</h4>
                        <hr/>
                        <p class="justify">
                            <img src="{{ asset($data['principal-message']->file_path) }}"
                                 class="img-thumbnail text-center" width="240"
                                 alt=""/>
                            <br/>
                            {!! $data['principal-message']->summery !!} <br/>
                            <a href="{{ URL :: to('/principalMessage') }}" class="text-green"> Read More > </a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-sm-12"><br/>
                <div class="post-item">
                    <div class="post-content">
                        <h4>Welcome To @if(isset($app_settings)){{ $app_settings->name  }} @endif </h4>
                        <hr/>
                        <p class="justify">
                            <img src="{{ asset($data['our-history']->file_path) }}" class="img-thumbnail"
                                 width="300"
                                 alt=""/>
                            {!! $data['our-history']->summery !!} <br/>
                            <a href="{{ URL :: to('/ourHistory') }}" class="text-green text-bold"> Read More > </a>
                        </p>
                    </div>
                </div>
                <div class="post-item">
                    <div class="post-content">
                        <div class="col-sm-12 col-md-7">
                            @include('frontend.layouts.notice')
                        </div>
                        <div class="col-sm-12 col-md-5">
                            <div class="widget">
                                <h4 class="card-title"> Teacher's Notice Board</h4>
                                <hr/>
                                @if($teachers_notice)
                                    <div class="recent-post-widget">
                                        @foreach($teachers_notice as $tn)
                                            <div class="rp-item">
                                                <img class="rp-thumb set-bg"
                                                     src="{{ asset('assets/images/blog/notice.png') }}"/>
                                                <div class="rp-content">
                                                    <a href="{{ URL :: to('/viewNews/'.$tn->id) }}">
                                                        <h6><strong style="font-size: 16px">{{ $tn->title }}</strong>
                                                        </h6>
                                                    </a>
                                                    <p>
                                                        <i class="fa fa-clock-o"></i> {{ date('dS F, Y', strtotime($tn->created_at))  }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <br/>
                            
                            <h4 class="card-title"> Important Links</h4>
                            <hr/>
                            <div class="card" id="important_links">
                                <div class="card-body">
                                    <ul>
                                        <li><a href="http://www.educationboard.gov.bd/"
                                               target="_blank"><i
                                                    class="fa fa-anchor" aria-hidden="true"></i> Education Board</a>
                                        </li>
                                        <li><a href="http://dhakaeducationboard.portal.gov.bd/"
                                               target="_blank"><i
                                                    class="fa fa-anchor" aria-hidden="true"></i> Dhaka Education
                                                Board</a></li>
                                        <li><a href="http://www.moedu.gov.bd/" target="_blank"><i
                                                    class="fa fa-anchor"
                                                    aria-hidden="true"></i>
                                                Ministry of Education</a></li>
                                        <li><a href="http://banbeis.gov.bd/new/" target="_blank"><i
                                                    class="fa fa-anchor"
                                                    aria-hidden="true"></i>
                                                Banbeis</a></li>
                                        <li><a href="http://www.dshe.gov.bd/" target="_blank"><i
                                                    class="fa fa-anchor"
                                                    aria-hidden="true"></i>
                                                Directorate of Secondary &amp; Higher Education</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card">
                                <hr/>
                                <div class="card-body">
                                    <a href="{{ URL :: to('/onlineAdmission') }}"><img
                                            src="{{ asset('assets/images/online-admission.jpg') }}"
                                            class="img-thumbnail" width="300"
                                            alt=""/></a><br/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-top-60">
            <div class="col-sm-12 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ URL :: to('/prospectus') }}"><img
                                src="{{ asset('assets/images/prospectus.jpg') }}"
                                class="img-thumbnail"
                                alt=""/></a><br/>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ URL :: to('/academicCalender') }}"><img
                                src="{{ asset('assets/images/calender.jpg') }}"
                                class="img-thumbnail"
                                alt=""/></a><br/>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ URL :: to('/classRoutine') }}"><img
                                src="{{ asset('assets/images/routine.jpg') }}"
                                class="img-thumbnail"
                                alt=""/></a><br/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--=== Welcome Chairman Message and Notice Board End ===---->

    <!--=== Count Board Start ===---->

    <section class="fact-section spad set-bg" data-setbg="{{ asset('assets/images/school_bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-4 fact">
                    <div class="fact-icon">
                        <i class="fa fa-home fa-1x"></i>
                    </div>
                    <div class="fact-text">
                        <h2>{{ $app_settings->stablished }}</h2>
                        <p> Stablished Year</p>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 fact">
                    <div class="fact-icon">
                        <i class="fa fa-briefcase fa-1x"></i>
                    </div>
                    <div class="fact-text">
                        <h2>{{ $teacher }}</h2>
                        <p>TEACHERS</p>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 fact">
                    <div class="fact-icon">
                        <i class="fa fa-users fa-1x"></i>
                    </div>
                    <div class="fact-text">
                        <h2>{{ $students  }}</h2>
                        <p>STUDENTS Of {{ date('Y') }} Session </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=== Count Board End ===---->


    <!--=== Gallery Start ===---->
    {{--@include('frontend.layouts.gallery')--}}
    <!--=== Gallery Start ===---->

    <!--=== Latest News Start ===---->
    @include('frontend.layouts.latest_news')
    <!--=== Latest News Start ===---->


    <!--=== Birthday Start ===---->
    @include('frontend.layouts.birthday')
    <!--=== Birthday Start ===---->
@endsection
