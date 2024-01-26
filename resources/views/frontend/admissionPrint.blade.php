<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body style="page-break-after: auto">
@if(!empty($admissionApplication))
    <div class="" id="admission_application">
        <div id="row_block">
            <div class="col-md-2">
                <div class="office">
                    <strong>Filled by Office</strong>
                    <hr/>
                    <p>Serial No. - </p>
                    <p>Admission Date - </p>
                    <p>Class - </p>
                    <p>Section - </p>
                    <p>Roll - </p>
                    <p>Session - </p>
                </div>
            </div>
            <div id="header" class="col-md-8">
                <img src="{{ asset($app_settings->logo) }}" width="60%"/>
                <p style="margin-top: -2px">Bandar, Chattogram - 4100</p>
                <p>PABX - 2522200-29, Ex-5349,5317</p>
                <p>EIIN : 138384</p>
                <p> Admission Application Form</p>
            </div>
            <div class="col-md-2">
                <img src="{{asset($admissionApplication->file_path)}}" width="140px" class="img img-thumbnail"/>
            </div>
        </div>
        <hr/>
        <div style="clear: both"></div>
        <div id="row_block" style="display:none;">
            <div class="col-md-3">
                <div class="office">
                    <strong>Filled by Office</strong>
                    <hr/>
                    <p>Serial No. - </p>
                    <p>Admission Date - </p>
                    <p>Class - </p>
                    <p>Section - </p>
                    <p>Roll - </p>
                    <p>Session - </p>
                </div>
            </div>
            <div class="col-md-6">
                <div id="header">
                    <p> Admission Application Form</p>
                    <p> Application Form Number - {{$admissionApplication->applicant_form_no}}</p>
                </div>
            </div>
            <div class="col-md-3" style="text-align: right">
                <img src="{{asset('$admissionApplication->file_path')}}" width="140px"/>
            </div>
        </div>
        <div style="clear: both"></div>
        <div class="row_block">
            <div class="col-md-4">
                <p><strong>Which class do you want to admit ? </strong></p>
            </div>
            <div class="col-md-2">
                <p> Class : {{$admissionApplication->stdclass->name}}</p>
            </div>
            <div class="col-md-3">
                <p> Department : {{ $admissionApplication->section->name }} </p>
            </div>
        </div>
        <div style="clear: both"></div>
        <div class="row_block">
            <div class="col-md-4">
                <p><strong>Applicant's Name (Secondary / Equivalent) </strong></p>
            </div>
            <div class="col-md-3">
                <p class="bd_font"> Bangla : {{$admissionApplication->applicant_name_bn}} </p>
            </div>
            <div class="col-md-5">
                <p> English : {{$admissionApplication->applicant_name_en}}</p>
            </div>
        </div>
        <div style="clear: both"></div>
        <div class="row_block">
            <div class="col-md-5">
                <p class="bd_font"><strong>Father's Name </strong>(Bangla) : {{$admissionApplication->father_name_bn}}
                </p>
            </div>
            <div class="col-md-4">
                <p> English : {{$admissionApplication->father_name_en}}</p>
            </div>
            <div class="col-md-3">
                <p> Mobile : {{$admissionApplication->father_mobile}} </p>
            </div>
        </div>
        <div style="clear: both"></div>
        <div class="row_block">
            <div class="col-md-5">
                <p class="bd_font"><strong>Mother's Name </strong>(Bangla) : {{$admissionApplication->mother_name_bn}}
                </p>
            </div>
            <div class="col-md-4">
                <p> English : {{$admissionApplication->mother_name_en}}</p>
            </div>
            <div class="col-md-3">
                <p> Mobile : {{$admissionApplication->mother_mobile}} </p>
            </div>
        </div>
        <div style="clear: both"></div>
        <div class="row_block">
            <div class="col-md-12">
                <p> {{' Date of Birth : ' . $admissionApplication->dob
                . ' , Phone : ' .  $admissionApplication->mobile
                . ' , Email : ' .  $admissionApplication->email
                . ' , Nationality : ' .  $admissionApplication->nationality
                . ' , Religion : ' .  $admissionApplication->religion}}
                </p>
            </div>
        </div>
        <div style="clear: both"></div>
        <div class="row_block">
            <div class="col-md-12">
                <p><strong> Present Address : </strong>
                    {{ ' Village : ' . $admissionApplication->present_village
                       . ', Post Office : ' . $admissionApplication->present_post_office }}</p>
            </div>
            <div style="clear: both"></div>
            <div class="col-md-12">
                <p>
                    {{ ' Thana : ' . $admissionApplication->present_thana
                    . ', District : ' . $admissionApplication->present_district}}
                </p>
            </div>
            <div style="clear: both"></div>
            <div class="col-md-12">
                <p><strong> Parmanent Address : </strong>
                    {{ ' Village : ' . $admissionApplication->parmanent_village
                     . ', Post Office : ' . $admissionApplication->parmanent_post_office}}
                </p>
            </div>
            <div style="clear: both"></div>
            <div class="col-md-12">
                <p>
                    {{' Thana : ' . $admissionApplication->parmanent_thana
                     . ', District : ' . $admissionApplication->parmanent_district}}
                </p>
            </div>
        </div>
        <div style="clear: both"></div>
        <div class="row_block">
            <div class="col-md-12">
                <p><strong> Relatives works in port : </strong>
                    {{ $admissionApplication->std_relation_port_officer }}
                </p>
            </div>
            @if($admissionApplication->std_relation_port_officer == 'Yes')
                <div style="clear: both"></div>
                <div class="col-md-12">
                    <p>
                        {{ 'Name : ' . $admissionApplication->port_officer_name
                        . ', Working Location : ' .  $admissionApplication->port_officer_working_place
                        . ' , Designation : ' .  $admissionApplication->port_officer_designation}}
                    </p>
                </div>
            @endif
        </div>
        <div style="clear: both"></div>
        <div class="row_block">
            <div class="col-md-12">
                <p><strong> Gurdian (Absence of father) : </strong>
                    @if($admissionApplication->alternet_gurdian_name != '')
                        {{' Name : ' . $admissionApplication->alternet_gurdian_name
                        . ' , Phone : ' .  $admissionApplication->alternet_gurdian_phone}}
                </p>
            </div>
            <div style="clear: both"></div>
            <div class="col-md-12">
                <p>
                    {{ ' Relation : ' .  $admissionApplication->alternet_gurdian_relation
                    . ' , Address : ' .  $admissionApplication->alternet_gurdian_address }}
                    @else
                        No
                    @endif
                </p>
            </div>
        </div>
        <div style="clear: both"></div>
        <div class="row_block" style="font-size:9px;">
            <div class="col-md-12">
                <p><strong> Readable Subject : </strong></p>
            </div>
            <div style="clear: both"></div>
            <div>
                <div class="col-md-4"><p><strong>Subject</strong></p></div>
                <div class="col-md-4"><p><strong>Code</strong></p></div>
                <div class="col-md-4"><p><strong>Type</strong></p></div>
                <div style="clear: both"></div>
                @foreach($admissionApplication->readableSubjects as $subject)
                    <div class="col-md-4"><p>{{$subject->sub_name}}</p></div>
                    <div class="col-md-4"><p>{{$subject->sub_code}}</p></div>
                    <div class="col-md-4"><p>Mandatory</p></div>
                    <div style="clear: both"></div>
                @endforeach
                <div class="col-md-4"><p>{{$admissionApplication->optional_subject_name}}</p></div>
                <div class="col-md-4"><p>{{$admissionApplication->optional_subject_code}}</p></div>
                <div class="col-md-4"><p>Optional</p></div>
                <div style="clear: both"></div>
            </div>
        </div>
        <div style="clear: both"></div>
        <div class="row_block">
            <div class="col-md-4"><p>Exam Name : Secondary / Equivalent</p></div>
            <div class="col-md-6"><p>School Name : {{$admissionApplication->passed_school_name}}</p></div>
            <div class="col-md-2"><p>Exam Roll : {{$admissionApplication->exam_roll}}</p></div>
            <div style="clear: both"></div>
            <div class="col-md-3"><p>Registration No. : {{$admissionApplication->reg_no}}</p></div>
            <div class="col-md-3"><p>Board : {{$admissionApplication->exam_board}} </p></div>
            <div class="col-md-2"><p>Session : {{$admissionApplication->exam_session}} </p></div>
            <div class="col-md-3"><p>Passed Year : {{$admissionApplication->passed_year}}</p></div>
        </div>
        <div style="clear: both"></div>
        <div class="row_block" style="font-size:9px;">
            <div class="col-md-12">
                <p><strong> Marks Obtained in Secondary / Equivalent Exam : </strong></p>
            </div>
            <div style="clear: both"></div>
            <div>
                <div class="col-md-5"><p><strong>Subject</strong></p></div>
                <div class="col-md-3"><p><strong>Letter Grade</strong></p></div>
                <div class="col-md-4"><p><strong>GPA</strong></p></div>
                <div style="clear: both"></div>
                @foreach($admissionApplication->sscSubjects as $ssc)
                    <div class="col-md-5"><p>{{$ssc->ssc_sub_name}}</p></div>
                    <div class="col-md-3"><p>{{$ssc->grade}}</p></div>
                    <div class="col-md-4"><p>{{$ssc->gpa}}</p></div>
                    <div style="clear: both"></div>
                @endforeach
            </div>
        </div>
        <div style="clear: both"></div>
        <div class="row_block">
            <div class="col-md-5"><p>GPA (Excluding 4th subject) : {{$admissionApplication->gpa_without_fourth}}</p>
            </div>
            <div class="col-md-4"><p>4th Subject GPA : {{$admissionApplication->fourth_sub_gpa}}</p></div>
            <div class="col-md-3"><p>Total GPA : {{$admissionApplication->grand_gpa}}</p></div>
        </div>
        <div style="clear: both"></div>
        <div class="row_block" style="margin-top:20px;">
            <div class="col-md-9">
                <p>--------------------------------------------------</p>
                <p>Admission Committee's Signature</p>
            </div>
            <div class="col-md-3" style="text-align:right;">
                <p>--------------------------------</p>
                <p>Principal's Signature</p>
            </div>
        </div>
        <div style="clear: both"></div>
    </div>
@else
    <div class="row">
        <div class="col-md-12 text-center">
            <div id="not_found">
                <img src="{{asset('assets/images/empty_box.png')}}" width="200px">
            </div>
            <h2 style="font-family: SolaimanLipi, sans-serif">Not found</h2>
        </div>
    </div>
@endif

<style>

    .bd_font {
        font-family: 'bangla', sans-serif;;
    }

    #admission_application {
        padding-right: 5px;
        font-size: 10px;
        line-height: -10px;
    }

    #header {
        text-align: center;
        line-height: 6px;
    }

    #row_block {
        width: 100%;
        display: block;
        clear: both;
        position: relative;
    }

    .office {
        line-height: 6px;
    }

    .col-md-2 {
        width: 15%;
        float: left;
    }

    .col-md-3 {
        width: 25%;
        float: left;
    }

    .col-md-4 {
        width: 33.33%;
        float: left;
    }

    .col-md-5 {
        width: 40%;
        float: left;
    }

    .col-md-6 {
        width: 50%;
        float: left;
    }

    .col-md-7 {
        width: 55%;
        float: left;
    }

    .col-md-8 {
        width: 70%;
        float: left;
    }

    .col-md-9 {
        width: 70%;
        float: left;
    }

    .col-md-12 {
        width: 100%;
        float: left;
    }


</style>
</body>
</html>
