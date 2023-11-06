@extends('new_layouts.user.layout')
@section('content')
        {{-- <div class="relative w-full bg-emerald-600 overflow-hidden" style="height: 70vh">
            <span class="absolute z-10 bottom-5 ps-10 pt-1 whitespace-nowrap bg-gray-100 cursor-pointer font-semibold uppercase rounded-lg text-4xl hover:bg-slate-500 hover:text-white duration-500" style="width: 200px ;height:50px;right:45%">Room 1</span>
            <img src="{{ asset('images/background_img/office1.jpg') }}" class="object-cover w-full" style="height:inherit;" alt="">
        </div> --}}
        <div class="grid grid-cols-3 gap-6 pt-10 px-32">

            @foreach ($room as $item)
                <div class="max-w bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 overflow-hidden room_card">
                    <div class="h-4/6 overflow-hidden">
                        <a href="javascript:{}" >
                            <img class="rounded-t-lg object-cover h-full w-full duration-500 hover:scale-125 booking_a" src="{{ asset('storage/uploads/room_image/'.$item->image->file_name) }}" alt="" />
                        </a>
                    </div>
                    <div class="pt-5 px-5 flex flex-col">
                        <x-button class="px-3 text-sm font-medium justify-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 hover:ring-2 hover:ring-offset-2  dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 booking_btn" data-id="{{ $item->id }}">
                            {{ __('Book Now') }}
                        </x-button>
                        <span class="w-full mt-2 text-xl text-center font-serif ">{{ $item->seat }} Seats Avaliable</span>
                        <span class="w-full mt-2 text-xl text-center">{{ $item->room_name }}( <b class="room_status"></b> )</span>
                    </div>
                </div>
                <input type="hidden" class="room_id" value="{{ $item->id }}">
            @endforeach

        </div><hr class="mt-2">

        <div class="mt-3 ps-3">
            <span class="text-2xl italic font-medium uppercase underline">Meeting &nbsp; Plan &nbsp; This &nbsp; Month</span>

            <table class="table-responsive mt-4" style="width: 99%">
                <thead class="h-12 bg-slate-300 z-0">
                    <tr>
                        <th class="text-left ps-2 rounded-tl-lg w-12">No</th>
                        <th class="text-left w-20">Room</th>
                        <th class="text-left">Date</th>
                        <th class="text-left">Meeeting Title</th>
                        <th class="text-left">Start Time</th>
                        <th class="text-left">End Time</th>
                        <th class="text-left">Duration</th>
                        <th class="text-left">Meeting By</th>
                        <th class="text-left">Status</th>
                        <th class="text-left">Reason</th>
                        <th class="text-left rounded-tr-lg">Extended Time</th>
                    </tr>
                </thead>
                <tbody class="font-light booking_body">
                    @foreach ($bookings as $item)
                        <tr class="h-10 hover:bg-slate-100">
                            <td class="ps-2">{{ $bookings->firstItem()+$loop->index }}</td>
                            <td >{{ $item->room->room_name }}</td>
                            <td class="">{{ $item->date }}</td>
                            <td class="">{{ $item->title }}</td>
                            <td>{{ $item->start_time }}</td>
                            <td>{{ $item->end_time }}</td>
                            <td>{{ $item->duration }}</td>
                            <td>{{ $item->user->name }}</td>
                            <td>
                                @switch($item->status)
                                @case(0)
                                    Pending
                                    @break
                                @case(1)
                                    Started
                                    @break
                                @case(2)
                                    Ended
                                    @break
                                @case(3)
                                    Canceled
                                    @break
                                @case(4)
                                    Missed
                                    @break
                                @case(5)
                                    Finished
                                    @break
                                    @default
                                @endswitch
                            </td>
                            <td>{{ $item->reason->reason }}</td>
                            <td>
                                @if ($item->extend_status)
                                {{ $item->extended_duration }}
                            @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="flex justify-center text-xs mt-2 bg-white">
                {{ $bookings->links() }}

        </div>

        @push('js')
            <script>
                $(document).ready(function(e){
                    room_status();
                    setInterval(() => {
                        room_status();
                    }, 60000);

                    $.ajaxSetup({
                        headers : {'X-CSRF-TOKEN' : $('meta[name = __token]').attr('content')}
                    })

                    $.ajax({
                        url : "{{ route('booking_status') }}",
                        type: "POST",
                        success:function(res){
                            console.log('change status success');
                        }
                    })

                    $(document).on('click','.booking_btn',function(e){
                        $id = $(this).data('id');
                        $url = 'booking/'+$id;
                        window.location.href = $url;
                    })

                    $(document).on('click','.booking_a',function(e){
                        e.preventDefault();
                        // console.log('hello');
                        $(this).parent().parent().parent().find('.booking_btn').click();
                    })

                    function room_status(){
                        $('.room_id').each((i,e)=>{
                        $val  = $(e).val();
                        $.ajax({
                            url     : '/room/ajax/'+$val,
                            type    : 'GET',
                            beforeSend:function(){
                                $('.room_status').eq(i).removeClass('text-emerald-500 text-rose-500')
                            },
                            success : function(res){
                                if(res.status == 'Boss In'){
                                    $('.room_status').eq(i).addClass('text-indigo-500');
                                    $('.room_status').eq(i).text(res.status);
                                }else if(res.status == 'Avaliable'){
                                    $('.room_status').eq(i).addClass('text-emerald-500');
                                    $('.room_status').eq(i).text(res.status);
                                }else if(res.status == 'Occupied'){
                                    $('.room_status').eq(i).addClass('text-rose-500');
                                    $('.room_status').eq(i).text(res.status+'('+res.user+')');
                                }else if(res.status == 'Not Avaliable'){
                                    $('.room_status').eq(i).addClass('text-amber-500');
                                    $('.room_status').eq(i).text(res.status);
                                }
                            }
                        })
                    })
                    }

                })
            </script>
        @endpush
@endsection
