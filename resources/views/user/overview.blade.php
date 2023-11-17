@extends('new_layouts.user.layout')
@section('content')
    <div id='calendar' class="w-full px-5 mt-10"></div>
    <div class="grid grid-cols-3 gap-6 px-32 mt-10">
        <div class="flex">
            <div class="flex ">
                <div class="w-5 h-5" style="background-color: #13ABB2"></div>
                <span class="ps-4">Room 1</span>
            </div>
            <div class="flex ms-5">
                <div class="w-5 h-5" style="background-color: #658FCF"></div>
                <span class="ps-4">Room 2</span>
            </div>
            <div class="flex ms-5">
                <div class="w-5 h-5" style="background-color: #936AB9"></div>
                <span class="ps-4">Room 3</span>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    contentHeight: "auto",
                    slotMinTime : "08:30:00",
                    slotMaxTime : "18:00:00",
                    weekNumbers  : true,
                    eventOverlap:true,
                    dayMaxEvents: true,
                    initialView: 'timeGridWeek',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'timeGridWeek,timeGridDay,dayGridMonth'
                    },
                    events: [
                            @foreach ($book as $item)
                                {
                                    title: '{{ $item->room->room_name }}',
                                    start: "{{ $item->date.'T'.$item->start_time }}",
                                    end: "{{ $item->date.'T'.$item->end_time }}",
                                    color: "{{ $item->room_id == 1 ? '#13ABB2' : ($item->room_id == 2 ? '#658FCF' : '#936AB9') }}",
                                    textColor: '#ffffff',
                                },
                            @endforeach
                        ]
                })

                    calendar.render();
            })
        </script>
    @endpush
@endsection
