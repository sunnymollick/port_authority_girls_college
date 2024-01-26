@extends('frontend.layouts.fullwidth_master')
@section('title', ' Executive Committee')
@section('content')
    <div class="container p-top-50 p-bottom-50 p-right-40">
        <div class="section-title text-center">
            <h4>Our Honourable Executive Committee</h4> <hr/>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="member">
                    <div class="member-pic set-bg"
                         data-setbg="{{ asset('assets/images/committee/mohammad_sohail.jpeg') }}">
                    </div>
                    <h5>Rear Admiral Mohammad Sohail</h5>
                    <p>OSP,NUP,PPM,psc, Chairman of Chittagong Port Authority & Chattogram Bandar Mohila College</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="member">
                    <div class="member-pic set-bg"
                         data-setbg="{{ asset('assets/images/committee/Mohammad_Mahbubur_Rahman.jpeg') }}">
                    </div>
                    <h5>Commodore Mohammad Mahbubur Rahman</h5>
                    <p>(E),psc,BN Member(Engineering) of Chittagong Port Authority & Chattogram Bandar Mohila College</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="member">
                    <div class="member-pic set-bg"
                         data-setbg="{{ asset('assets/images/committee/Md_Habibur_Rahman.jpeg') }}">
                    </div>
                    <h5>Md. Habibur Rahman</h5>
                    <p>Join Secretary, Member (Administration and Planing) of Chittagong Port Authority & Chattogram Bandar Mohila College</p>
                </div>
            </div>
            
        </div>
        <hr/>
        <div class="row">
            <!--<div class="col-md-2 col-sm-12">-->
            <!--</div>-->
            <div class="col-md-4 col-sm-12">
                <div class="member">
                    <div class="member-pic set-bg"
                         data-setbg="{{ asset('assets/images/committee/M_Fazlar_Rahman.jpeg') }}">
                    </div>
                    <h5>Commodore M Fazlar Rahman</h5>
                    <p>(C),BSP,psc,BN, Member (Harbour & Marine) of Chittagong Port Authority & Chattogram Bandar Mohila College</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="member">
                    <div class="member-pic set-bg"
                         data-setbg="{{ asset('assets/images/committee/Mohammad_Shahidul_Alam.jpeg') }}">
                    </div>
                    <h5>Mohammad Shahidul Alam</h5>
                    <p>Addition Secretary, Member (Finance) of Chittagong Port Authority & Chattogram Bandar Mohila College.</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="member">
                    <div class="member-pic set-bg"
                         data-setbg="{{ asset('assets/images/committee/Mominur_Rashid.jpeg') }}">
                    </div>
                    <h5>Md. Mominur Rashid</h5>
                    <p>Deputy Secretary, Director (Admin) of Chittagong Port Authority & Chattogram Bandar Mohila College.</p>
                </div>
            </div>
            
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-4 col-sm-12">
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="member">
                    <div class="member-pic set-bg"
                         data-setbg="{{ asset('assets/images/committee/Morzina_Khanom.jpeg') }}">
                    </div>
                    <h5>Morzina Khanom</h5>
                    <p>Principal Of Chattogram Bandar Mohila College</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
            </div>
        </div>
    </div>
@endsection
