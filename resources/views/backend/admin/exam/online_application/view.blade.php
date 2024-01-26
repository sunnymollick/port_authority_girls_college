<div class="row" id="application">
    <div class="section-title">
        <div class="col-md-12" style="padding: 10px; background: #e0f6ff">
            <div class="col-md-3">
                <strong>Filled by Office</strong>
                <p>Serial No. - </p>
                <p>Admission Date - </p>
                <p>Class - </p>
                <p>Section - </p>
                <p>Roll - </p>
                <p>Session - </p>
            </div>
            <div class="col-md-5 text-center">
                <img src="{{ asset($app_settings->logo) }}" width="90%"/>
                <p>Bandar, Chattogram - 4100</p>
                <p>PABX - 2522200-29, Ex-5349,5317</p>
                <p>EIIN : 138384</p>
                <p> Admission Application Form</p>
            </div>
            <div class="col-md-4" style="text-align: right">
                <img src="{{asset($admissionApplication->file_path)}}" width="160px"/>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <table class="table table-hover table-bordered">
            <tr>
                <td>Want to admit</td>
                <td>:</td>
                <td> {{'Class  : ' . $admissionApplication->stdclass->name . ', Section : ' . $admissionApplication->section->name}}
                </td>
            </tr>
            <tr>
                <td>Applicant's Name</td>
                <td>:</td>
                <td>English : {{$admissionApplication->applicant_name_en}},
                    Bangla : {{$admissionApplication->applicant_name_bn}}
                </td>
            </tr>
            <tr>
                <td>Father's Name</td>
                <td>:</td>
                <td>English : {{$admissionApplication->father_name_en}},
                    Bangla : {{$admissionApplication->father_name_bn}},
                    Father's Mobile : {{$admissionApplication->father_mobile}}
                </td>
            </tr>
            <tr>
                <td>Mother's Name</td>
                <td>:</td>
                <td>English : {{$admissionApplication->mother_name_en}},
                    Bangla : {{$admissionApplication->mother_name_bn}},
                    Mother's Mobile : {{$admissionApplication->mother_mobile}}
                </td>
            </tr>
            <tr>
                <td>Present Address Information</td>
                <td>:</td>
                <td> {{ ' Village : ' . $admissionApplication->present_village . ', Post Office : ' . $admissionApplication->present_post_office .
                ', Thana : ' . $admissionApplication->present_thana . ', District : ' . $admissionApplication->present_district}}
                </td>
            </tr>
            <tr>
                <td>Parmanent Address Information</td>
                <td>:</td>
                <td> {{ ' Village : ' . $admissionApplication->parmanent_village . ', Post Office : ' . $admissionApplication->parmanent_post_office .
                ', Thana : ' . $admissionApplication->parmanent_thana . ', District : ' . $admissionApplication->parmanent_district}}
                </td>
            </tr>
            <tr>
                <td>Relatives works in port</td>
                <td>:</td>
                <td>
                    {{ $admissionApplication->std_relation_port_officer }}
                    @if($admissionApplication->std_relation_port_officer == 'Yes')
                        {{ ', Name : ' . $admissionApplication->alternet_gurdian_name .
                       ' , Working Location : ' .  $admissionApplication->port_officer_working_place . ' , Designation : ' .  $admissionApplication->port_officer_designation}}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Other Gurdian Information</td>
                <td>:</td>
                <td>
                    {{' Name : ' . $admissionApplication->alternet_gurdian_name .
                    ' , Phone : ' .  $admissionApplication->alternet_gurdian_phone . ' , Relation : ' .  $admissionApplication->alternet_gurdian_relation
                    . ' , Address : ' .  $admissionApplication->alternet_gurdian_address}}
                </td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td>:</td>
                <td> {{$admissionApplication->dob}}
                </td>
            </tr>
            <tr>
                <td>Mobile</td>
                <td>:</td>
                <td> {{$admissionApplication->mobile}}
                </td>
            </tr>
            <tr>
                <td>Email</td>
                <td>:</td>
                <td> {{$admissionApplication->email}}
                </td>
            </tr>
            <tr>
                <td>Nationality</td>
                <td>:</td>
                <td> {{$admissionApplication->nationality}}
                </td>
            </tr>
            <tr>
                <td>Religion</td>
                <td>:</td>
                <td> {{$admissionApplication->religion}}
                </td>
            </tr>
            <tr>
                <td>Readable Subject</td>
                <td>:</td>
                <td>
                    <table id="form_table" class="table table-striped table-hover table-bordered">
                        <thead>
                        <tr>
                            <th> Subject Name</th>
                            <th> Subject Code</th>
                            <th> Type</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($admissionApplication->readableSubjects as $subject)
                            <tr>
                                <td>{{$subject->sub_name}}</td>
                                <td>{{$subject->sub_code}}</td>
                                <td> Mandatory</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>{{$admissionApplication->optional_subject_name}}</td>
                            <td>{{$admissionApplication->optional_subject_code}}</td>
                            <td> Optional</td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>Secondary / Equivalent</td>
                <td>:</td>
                <td>
                    School Name : {{$admissionApplication->passed_school_name}} <br/>
                    Exam Roll : {{$admissionApplication->exam_roll}} ,
                    Registration No. : {{$admissionApplication->reg_no}} <br/>
                    Board : {{$admissionApplication->exam_board}} ,
                    Session : {{$admissionApplication->exam_session}} ,
                    Passed Year : {{$admissionApplication->passed_year}} <br/>
                </td>
            </tr>
            <tr>
                <td>Marks Obtianed</td>
                <td>:</td>
                <td>
                    <table id="form_table" class="table table-striped table-hover table-bordered">
                        <thead>
                        <tr>
                            <th> Subject Name</th>
                            <th> Letter Grade</th>
                            <th> GPA</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($admissionApplication->sscSubjects as $ssc)
                            <tr>
                                <td>{{$ssc->ssc_sub_name}}</td>
                                <td>{{$ssc->grade}}</td>
                                <td>{{$ssc->gpa}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>Result</td>
                <td>:</td>
                <td>
                    GPA (Excluding 4th subject) : {{$admissionApplication->gpa_without_fourth}} <br/>
                    4th Subject GPA : {{$admissionApplication->fourth_sub_gpa}} <br/>
                    Total GPA : {{$admissionApplication->grand_gpa}} <br/>
                </td>
            </tr>
            <tr>
                <td>Admission Date</td>
                <td>:</td>
                <td>
                    {{ date('dS F, Y, h:i:s A', strtotime($admissionApplication->created_at))  }}
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="clearfix"></div>
<div class="col-md-12">
    <a class='btn btn-success' target="_blank" href='/admissionPrint/{{$admissionApplication->id}}'>Download</a>
</div>
<div class="clearfix"></div>
<style>
    #application table td {
        font-size: 13px;
        line-height: 25px;
    }
</style>
