@include('backend.partials.modal')
<div class="container footer-top">
    <div class="row">
        <!-- widget -->
        <div class="col-sm-12 col-md-3 footer-widget">
            <h6 class="fw-title">USEFUL LINK</h6>
            <div class="dobule-link">
                <ul>
                    <li><a href="{{ URL :: to('/ourHistory') }}">Our History</a></li>
                    <li><a href="{{ URL :: to('/chairmanMessage') }}">Message from President</a></li>
                    <li><a href="{{ URL :: to('/principalMessage') }}">Message from Principal</a></li>
                    <li><a href="{{ URL :: to('/managementCommittee') }}">Management Committee</a></li>
                    <li><a href="{{ URL :: to('#') }}">Mission & Vision</a></li>
                    <li><a href="{{ URL :: to('#') }}">Publication</a></li>
                </ul>
            </div>
        </div>
        <!-- widget -->
        <div class="col-sm-12 col-md-2 footer-widget">
            <h6 class="fw-title"></h6>
            <div class="dobule-link">
                <ul>
                    <li><a href="{{ URL :: to('/students') }}">Students</a></li>
                    <li><a href="{{ URL :: to('/teachers') }}">Teachers</a></li>
                    <li><a href="{{ URL :: to('/classRoutine') }}">Class Routine</a></li>
                    <li><a href="{{ URL :: to('/classSyllabus') }}">Syllabus</a></li>
                    <li><a href="{{ URL :: to('/academicCalender') }}">Academic Calendar</a></li>
                    <li><a href="{{ URL :: to('/academicEvents') }}">Events Calendar</a></li>
                </ul>
            </div>
        </div>
        <!-- widget -->
        <div class="col-sm-12 col-md-3 footer-widget">
            <br/> <br/><br/>
            <div class="dobule-link">
                <ul>
                    <li><a href="{{ URL :: to('/rulesRegulation') }}">Rules & Regulation</a></li>
                    <li><a href="{{ URL :: to('/jobCircular') }}">Job Circular</a></li>
                    <li><a href="#">Eligibility</a></li>
                    <li><a href="#">Dress Code</a></li>
                    <li><a href="#">How to apply</a></li>
                    <li><a href="#">Admission Form</a></li>
                </ul>
            </div>
        </div>
        <!-- widget -->
        <div class="col-sm-12 col-md-3 footer-widget">
            <h6 class="fw-title">CONTACT</h6>
            <ul class="contact">
                @if(isset($app_settings))
                    <li><p><i class="fa fa-map-marker"></i> {{ $app_settings->address  }}</p></li>
                    <li><p><i class="fa fa-phone"></i> {{ $app_settings->contact  }}</p></li>
                    <li><p><i class="fa fa-envelope"></i> {{ $app_settings->email  }}</p></li>
                    <li><p><i class="fa fa-globe"></i> {{ $app_settings->website  }}</p></li>
                @endif
            </ul>
            <div class="social pt-1">
                <a href="#"><i class="fa fa-twitter-square"></i></a>
                <a href="#"><i class="fa fa-facebook-square"></i></a>
                <a href="#"><i class="fa fa-google-plus-square"></i></a>
                <a href="#"><i class="fa fa-linkedin-square"></i></a>
            </div>
        </div>
    </div>
</div>
<!-- copyright -->
<div class="copyright">
    <div class="container">
        <p>
            Copyright &copy;{{ date('Y') }}
            All rights reserved | Developed by <a href="http://www.w3xplorers.com">SIMS</a>
    </div>
</div>
<a class="top-link hide" href="" id="js-top">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 6">
        <path d="M12 6H0l6-6z"/>
    </svg>
    <!--<span class="screen-reader-text">Top</span>-->
</a>


<!-- AdminLTE App -->
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>

<!-- Menu -->
<script src="{{ asset('assets/js/menu.js') }}"></script>
<script src="{{ asset('assets/js/jquery.plainoverlay.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/js/magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.countdown.js') }}"></script>
<script src="{{ asset('assets/js/masonry.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('assets/js/jquery.printElement.min.js') }}"></script>
<!-- Datepicker library -->
<link rel="stylesheet" href="{{ asset('/assets/plugins/datepicker/datepicker3.css') }}">
<script src="{{ asset('/assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<style>
    /* Back to top css */

   .top-link {
        transition: all .25s ease-in-out;
        position: fixed;
        bottom: 0;
        right: 0;
        display: inline-flex;
        cursor: pointer;
        align-items: center;
        justify-content: center;
        margin: 0 1em 1em 0;
        border-radius: 50%;
        padding: 10px;
        width: 45px;
        height: 45px;
        background-color: #55bd74;
    }

    .top-link:hover {
        background-color: #4992b2;
    }

    .screen-reader-text {
        text-align: center;
        padding-left: 3px;
    }

</style>
<script>
    $.fn.modal.Constructor.prototype.enforceFocus = function () {
    };

    setTimeout(function () {
        $('.alert').fadeOut('slow');
    }, 5000); // <-- time in milliseconds
</script>
<script>
    function notify_view(type, message) {
        $.notify({
            message: message
        }, {
            type: type,
            allow_dismiss: true,
            offset: {x: '30', y: '65'},
            spacing: 10,
            z_index: 1031,
            delay: 200,
            animate: {enter: 'animated fadeInDown', exit: 'animated fadeOutUp'}
        });
    }
</script>