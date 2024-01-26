@extends('frontend.layouts.master')
@section('title', 'Contact Us')
@section('content')
    <div class="container p-top-50 p-bottom-50 p-right-40">
        <div class="section-title text-center">
            <h3>Feel Free to contact us</h3>
            <hr/>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="map-section">
                    <div class="contact-info-warp">
                        @if(isset($app_settings))
                            <div class="contact-info">
                                <h4>Address</h4>
                                <p>{{ $app_settings->address  }}</p>
                            </div>
                            <div class="contact-info">
                                <h4>Phone</h4>
                                <p>{{ $app_settings->contact  }}</p>
                            </div>
                            <div class="contact-info">
                                <h4>Email</h4>
                                <p>{{ $app_settings->email  }}</p>
                            </div>
                            <div class="contact-info">
                                <h4>Website</h4>
                                <p><a href="{{ $app_settings->website  }}">{{ $app_settings->website  }}</a></p>
                            </div>
                        @endif
                    </div>
                    <!-- Google map -->
                    <div class="map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3690.892619659538!2d91.78867007830102!3d22.319900472823!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30acded5a181eceb%3A0xc9b737d1e6305c82!2sChattogram%20Port%20Women%20College!5e0!3m2!1sen!2sbd!4v1597647692724!5m2!1sen!2sbd"
                            width="98%" height="500" frameborder="0" style="border:0;" allowfullscreen=""
                            aria-hidden="false" tabindex="0"></iframe>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12">
                <div class="contact-form spad pb-0">
                    <div class="section-title text-center">
                        <h3>We Appreciate your feedback</h3>
                    </div>
                    <form class="comment-form --contact">
                        <div class="col-md-4 col-sm-12">
                            <input type="text" placeholder="Your Name">
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <input type="text" placeholder="Your Email">
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <input type="text" placeholder="Subject">
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <textarea placeholder="Message"></textarea>
                            <div class="text-center">
                                <button class="site-btn">SUBMIT</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
