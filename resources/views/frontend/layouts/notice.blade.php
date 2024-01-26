<!-- widget -->
@php
    $notices = \App\Models\News::where('category', 'Notice Board')->orderby('created_at', 'desc')->take(6)->get();
@endphp
<div class="widget">
    @if($notices)
        <h4>Notice Board</h4>
        <hr/>
        <div class="recent-post-widget">
            @foreach($notices as $notice)
                <div class="rp-item">
                    <img class="rp-thumb set-bg" src="{{ asset('assets/images/blog/notice.png') }}"/>
                    <div class="rp-content">
                        <a href="{{ URL :: to('/viewNews/'.$notice->id) }}">
                            <h6><strong style="font-size: 16px">{{ $notice->title }}</strong></h6></a>
                        <p><i class="fa fa-clock-o"></i> {{ date('dS F, Y', strtotime($notice->created_at))  }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <br/>
        <div>
            <a href="{{ URL :: to('/academicNotices') }}" class="btn btn-primary btn-block">View all Notices</a>
        </div>
    @endif
</div>