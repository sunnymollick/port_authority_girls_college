@extends('backend.layouts.master')
@section('title', ' All Event')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <p class="panel-title"> All Events </p>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div id='calendar'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
    <script>
        $('#calendar').fullCalendar({
            // put your options and callbacks here
            defaultView: 'month',
            displayEventTime: false,
            events: [
                    @foreach($events as $event)
                {
                    id: '{{ $event->id }}',
                    title: '{{ $event->name }}',
                    start: '{{ $event->start_date }}',
                    end: '{{ $event->end_date }}',

                },
                @endforeach
            ],
            eventClick: function (info) {
                viewEvent(info.id)
            },
            eventRender: function (info) {

            },
        });


        function viewEvent(id) {

            $("#modal_data").empty();
            $('.modal-title').text('View Event Details'); // Set Title to Bootstrap modal title

            $.ajax({
                url: 'events/' + id,
                type: 'get',
                beforeSend: function () {
                    $('body').plainOverlay('show');
                },
                success: function (data) {
                    $('body').plainOverlay('hide');
                    $("#modal_data").html(data.html);
                    $('#myModal').modal('show'); // show bootstrap modal
                },
                error: function (result) {
                    $("#modal_data").html("Sorry Cannot Load Data");
                }
            });
        }
    </script>
    <style>
        @media screen and (min-width: 768px) {
            #myModal .modal-dialog {
                width: 70%;
                border-radius: 5px;
            }
        }
        /* Event calender style */

        .fc-time {
            display: none;
        }
    </style>
@stop




