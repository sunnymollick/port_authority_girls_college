<section class="topbar">
    <div class="container">
        <div class="row">
            <div class="col-md-10 pull-left">
                <p>
                    @if(isset($app_settings))<i class="fa fa-phone"></i> {{$app_settings->contact}} || Email : <i
                        class="fa fa-envelope"></i>
                    {{$app_settings->email}}
                    @endif</p>
            </div>
            <!-- <div class="col-md-2 pull-right front-account-login-menu">
                <ul style="list-style: none;">
                    <li class="dropdown user user-menu">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #fff">
                            <i class="fa fa-user"></i>&nbsp; Account Login &nbsp;<i class="fa fa-caret-down"> </i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href='/login'><span>Admin Login</span></a></li>
                            <li><a href='/student_login/login'><span>Student Login</span></a></li>
                            <li><a href='/parent_login/login'><span>Parent Login</span></a></li>
                            <li><a href='/teacher_login/login'><span>Teacher Login</span></a></li>
                        </ul>
                    </li>
                </ul>
            </div>-->

        </div>
    </div>
</section>
<section class="logo_bar">
    <div class="container">
        <div class="row">
            <!-- logo -->
            <a href="{{ URL :: to('/') }}" class="site-logo"><img src="{{ asset($app_settings->logo) }}" alt=""
                                                                  width="500px"></a>
            <div class="header-info"><br/>
                <a href="https://portal.cloudcampus24.com/" target="_blank">
                    <img src="{{asset('assets/images/bkash.jpg')}}" class="img img-thumbnail" alt=""
                         width="200px"></a>
                <h3 class="btn btn-success text-bold text-white"><a href="https://cbmc.edu.bd/academicNotices">Payment User<br/>ID & Password</a></h3>
                
                <h3 class="btn btn-success text-bold text-white"><a href="https://cbmc.edu.bd/howApply">How to Bkash</br> Payment</a></h3>
                <h3 class="btn btn-success text-bold text-white"><a href="{{ URL :: to('/onlineAdmission') }}"> Online
                        Admission
                        <br/>{{ config('running_session') }} </a></h3>
            </div>
        </div>
    </div>
</section>
<nav class="nav-section">
    <div class="container">
        <div class="row">
            <div id='cssmenu'>
                <ul>
                    <li class='active'><a href='{{ URL :: to('/') }}'><span>Home</span></a></li>
                    <li class='has-sub'><a href='#'><span>About</span></a>
                        <ul>
                            <li><a href='{{ URL :: to('/ourHistory') }}'><span>Our History</span></a></li>
                            <li>
                                <a href='{{ URL :: to('/chairmanMessage') }}'><span>Message from honourable Chairman </span></a>
                            </li>
                            <li>
                                <a href='{{ URL :: to('/principalMessage') }}'><span>Message from The Principal</span></a>
                            </li>
                            <li><a href='{{ URL :: to('/managementCommittee') }}'><span>Executive Committee</span></a>
                            </li>
                            <li><a href='{{ URL :: to('/visionMission') }}'><span>Mission & Vision</span></a></li>
                            <li><a href="#">Achievements</a></li>
                            <li><a href="#">Publication</a></li>
                        </ul>
                    </li>
                    <li class='has-sub'><a href="#">Academic</a>
                        <ul>
                            <li><a href="{{ URL :: to('/students') }}">Students</a></li>
                            <li><a href="{{ URL :: to('/teachers') }}">Teachers</a></li>
                            <li><a href="{{ URL :: to('/prospectus') }}">Prospectus</a></li>
                            <li><a href="{{ URL :: to('/classRoutine') }}">Class Routine</a></li>
                            <li><a href="{{ URL :: to('/classSyllabus') }}">Syllabus</a></li>
                            <li><a href="{{ URL :: to('/academicCalender') }}">Academic Calendar</a></li>
                            <!--<li><a href="{{ URL :: to('/academicEvents') }}">Events Calendar</a></li>-->
                            <li><a href="{{ URL :: to('/rulesRegulation') }}">Rules & Regulation</a></li>
                        </ul>
                    </li>

                    <li><a href="#">Result</a></li>
                    <li class='has-sub'><a href="#">Admission</a>
                        <ul>
                            <li><a href="{{ URL :: to('/onlineAdmission') }}">Online Admission Form</a></li>
                            <li><a href="{{ URL :: to('/admissionResult') }}">Admission Test Result</a></li>
                            <li><a href="{{ URL :: to('/eligibility') }}">Eligibility</a></li>
                            <li><a href="#">Dress Code</a></li>
                            <li><a href="{{ URL :: to('/howApply') }}">How to Bkash Payment</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ URL :: to('/downloads') }}">Downloads</a></li>
                    <li><a href="{{ URL :: to('/gallery') }}">Gallery</a></li>
                    <li class='has-sub'><a href="#">News & Notice</a>
                        <ul>
                            <li><a href="{{ URL :: to('/academicNotices') }}"> Notice</a></li>
                            <li><a href="{{ URL :: to('/academicNews') }}"> Recent News</a></li>
                        </ul>
                    </li>
                    <li class='has-sub'><a href="#">Careers</a>
                        <ul>
                            <li><a href="{{ URL :: to('/jobCircular') }}">Job Circular</a></li>
                            <li><a href="{{ URL :: to('/submitResume') }}">Submit Resume</a></li>
                        </ul>
                    </li>
                    
                    <li class='has-sub'><a href='/login'><span>Admin Login</span></a>
                        <ul>
                            <li><a href='/student_login/login'><span>Student Login</span></a></li>
                            <li><a href='/parent_login/login'><span>Parent Login</span></a></li>
                            <li><a href='/teacher_login/login'><span>Teacher Login</span></a></li>
                        </ul>
                    </li>
                    
                    <li class='last'><a href='{{ URL :: to('/contact') }}'><span>Contact</span></a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<!-- Header section end -->
<script type="text/javascript">
    $(document).ready(function () {
        $('#cssmenu ul li').each(function () {
            if (window.location.href.indexOf($(this).find('a:first').attr('href')) > -1) {
                $(this).addClass('active').siblings().removeClass('active');
            }
        });
        $('.has-sub ul li').each(function () {
            if (window.location.href.indexOf($(this).find('a:first').attr('href')) > -1) {
                $('#cssmenu ul li').removeClass('active');
                $(this).closest('ul').closest('li').addClass('active');
                $(this).addClass('active').siblings().removeClass('active');
            }
        });
    });
</script>

