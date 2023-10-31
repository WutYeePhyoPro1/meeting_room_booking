@extends('new_layouts.user.layout')
@section('content')
    @push('css')
        <style>
            .filter_div > span.filter_select{
                background-color: {{ getAuth()->bg_color }};
                color : {{ getAuth()->text_color }};
                }
        </style>
    @endpush
    {{-- <form action="" method="get" class="grid grid-cols-6 gap-10">
    <div class="col-start-2 flex flex-col">
        <label for="from_date">From Date :</label>
        <input type="date" name="from_date" id="from_date" class="border-slate-300 rounded-lg mt-2 focus:ring-0 focus:border-slate-500">
    </div>
    <div class="flex flex-col">
        <label for="to_date">TO Date :</label>
        <input type="date" name="to_date" id="to_date" class="border-slate-300 rounded-lg mt-2 focus:ring-0 focus:border-slate-500">
    </div>
    <div class="flex flex-col">
        <label for="status">Status :</label>
        <select name="status" id="status" class="border-slate-300 rounded-t-lg mt-2 focus:ring-0 focus:border-slate-500">
            <option value="">Choose Status</option>
            <option value=""></option>
            <option value=""></option>
            <option value=""></option>
        </select>
    </div>
    <div class="flex flex-col">
        <x-button class="bg-slate-600 w-24 ps-6 py-3 rounded-lg text-white mt-8 hover:bg-slate-500">Search</x-button>
    </div>
</form> --}}
        <div class="px-4 py-2 mt-3 text-center w-1/2 rounded-2xl mx-auto relative filter_div">
                <span class="absolute w-20 rounded-2xl border-b-2 border-rose-500 ms-3 bottom-0 duration-500 filter_highlight" style="border-color: {{ getAuth()->bg_color }}"></span>
                <span class="text-xl px-10 rounded-2xl py-2 cursor-pointer select-none  duration-500 filter_nav filter_select" data-no="0">All</span>
                <span class="text-xl px-10 rounded-2xl py-2 cursor-pointer select-none  duration-500 filter_nav" data-no="1">Room1</span>
                <span class="text-xl px-10 rounded-2xl py-2 cursor-pointer select-none  duration-500 filter_nav" data-no="2">Room2</span>
                <span class="text-xl px-10 rounded-2xl py-2 cursor-pointer select-none  duration-500 filter_nav" data-no="3">Room3</span>
        </div>

            <div id="table_layout">
                @if (count($booking) > 0)
                <div class="grid grid-cols-3 gap-6 px-6 pb-4 pt-2 mt-4 ">
                    @foreach ($booking as $item)
                        <div class="max-w bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 bg_img1" style="background: linear-gradient(rgba(61, 57, 57, 0.7), rgba(59, 57, 57, 0.7)),url('{{ asset('storage/uploads/room_image/'.$item->room->image->file_name) }}');background-repeat:no-repeat;background-position:center;background-size:cover;color:white;">
                            <div class="text-center py-2 my_booking_card">
                                {{ $item->title }}
                            </div>
                            <hr>
                            <div class="card_div">
                                <div class="grid grid-cols-2 gap-2 mb-9 mt-4 px-4 my_booking_card">
                                    <span >Room :</span>
                                    <span >{{ $item->room->room_name }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 my-9 px-4 my_booking_card">
                                    <span >Date & Time :</span>
                                    <span >{{ $item->date  }}&nbsp;&nbsp;|&nbsp;&nbsp;{{ date('g:i A',strtotime($item->start_time)) .'~'.date('g:i A',strtotime($item->end_time)) }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 my-9 px-4 my_booking_card">
                                    <span >Duration :</span>
                                    <span >{{ $item->duration }}&nbsp;&nbsp; (<b class="remaining_duration" data-start="{{ $item->start_time }}" data-end="{{ $item->end_time }}" data-date="{{ $item->date }}">{{ $item->duration }}</b>)</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 my-9 px-4 my_booking_card">
                                    <span >Remaining Time :</span>
                                    <span data-date="{{ $item->date }}" data-start="{{ $item->start_time }}" class="remaining_time">{{ getRemainingTime($item->date,$item->start_time) }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 my-9 px-4 my_booking_card">
                                    <span >Reason :</span>
                                    <span >{{ $item->reason->reason }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 my-9 px-4 my_booking_card">
                                    <span >Remark :</span>
                                    <span class="break-all">{{ $item->remark }}</span>
                                </div>
                                <div class="flex justify-between mt-9 mb-2 px-4 my_booking_card">
                                    <input type="hidden" class="room_status" value="{{ $item->room->status }}">
                                    <button class="bg-emerald-400 hover:bg-emerald-500 py-2 px-10 rounded-md hidden start_btn" data-id="{{ $item->id }}">Start</button>
                                    <button class="bg-rose-300 hover:bg-rose-400 py-2 rounded-md px-10 end_btn {{ $item->status == 1 ? '' : 'hidden' }}" data-id="{{ $item->id }}" >End</button>
                                    <button class="bg-sky-500 hover:bg-sky-600 py-2 rounded-md px-10 extent_btn {{ $item->status == 1 ? '' : 'hidden' }}" >Extend</button>
                                    <button class="bg-red-500 hover:bg-red-600 py-2 rounded-md px-10 cancel_btn" hidden data-id="{{ $item->id }}">Cancel</button>
                                </div>
                                <input type="hidden" class="booking_status" value="{{ $item->status }}">

                            </div>
                        </div>
                    @endforeach
                </div>
                @else
                <div class="text-center" style="min-height: 80vh">
                    <span class="text-5xl text-slate-300" style="line-height: 80vh">There is no Data</span>
                </div>

            @endif
            </div>

        @push('js')
            <script>
                $(document).ready(function(){
                    // console.log($('.remaining_time').length);
                    var length = $('.remaining_time').length;
                    setInterval(() => {
                        time_before($('.remaining_time'),length);
                        remaining_duration($('.remaining_duration'),length);
                    }, 1000);

                    function time_before(e,length){
                        for($i = 0 ; $i < length ; $i++){
                            $start_time = $(e).eq($i).data('start');
                            $date       = $(e).eq($i).data('date');
                            $start_date = $date+' '+$start_time;
                            $start = new Date($start_date);
                            $start_time = $start.getTime();
                            $now        = new Date();
                            $now        = $now.getTime();
                            $diff       = $start_time-$now;
                            if ($diff <= 0) {
                                clearInterval();
                                // $('.bg_img1').eq($i).hide();
                                $time = '0';
                            } else {
                                $days = Math.floor($diff / (1000 * 60 * 60 * 24));
                                $hours = Math.floor(($diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                $min = Math.floor(($diff % (1000 * 60 * 60)) / (1000 * 60));
                                $sec = Math.floor(($diff % (1000 * 60)) / 1000);

                               $time = `${$days}d ${$hours.toString().padStart(2, "0")}:${$min.toString().padStart(2, "0")}:${$sec.toString().padStart(2, "0")}`;
                            }
                            $(e).eq($i).html($time);
                            $(e).trigger('changeValue');
                        }
                    }

                    function remaining_duration(e,length)
                    {
                        for($i = 0 ; $i < length ; $i++){
                            $start_time = $(e).eq($i).data('start');
                            $end_time = $(e).eq($i).data('end');
                            $date       = $(e).eq($i).data('date');
                            $room_status= $('.room_status').eq($i).val();
                            $start_date = $date+' '+$start_time;
                            $end_date = $date+' '+$end_time;
                            $start = new Date($start_date);
                            $end = new Date($end_date);
                            // console.log($start);
                            $now        = new Date();
                            $now        = $now.getTime();
                            $start_time = $start.getTime();
                            $end_time = $end.getTime();
                            $show_btn_time = $start_time-(1000 * 60 * 5);
                            // console.log($start_time +'_'+ $show_btn_time);
                            if ($now > $show_btn_time) {
                                var diff = $end_time - $now;
                                if (diff > 0 && $room_status == 0) {
                                    // console.log(diff);

                                    if($('.booking_status').eq($i).val() == 0){
                                        $('.start_btn').eq($i).removeClass('hidden');
                                    }
                                }else{
                                    if (!$('.start_btn').eq($i).is(':hidden')) {
                                        $('.start_btn').eq($i).attr('hidden', 'hidden');
                                    }
                                }
                            }
                            // console.log($(e).eq(0).html());
                            // console.log('yes');
                            //             console.log('its show time');

                            if($now > $start_time){
                                $diff = $end_time - $now;
                                // console.log($diff);
                                 if ($diff <= 0) {
                                    // clearInterval();
                                        $id = $('.start_btn').eq($i).data('id');
                                        $.ajax({
                                            url     : '/my_booking/ajax/status/'+$id,
                                            type    : 'GET',
                                            success : function(res){
                                                // console.log('success');
                                            }
                                        })
                                    $time = '0';
                                }else {
                                    // $('.start_btn').eq($i).removeAttr('hidden');
                                    $hours = Math.floor(($diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    $min = Math.floor(($diff % (1000 * 60 * 60)) / (1000 * 60));
                                    $sec = Math.floor(($diff % (1000 * 60)) / 1000);

                                $time = `${$hours.toString().padStart(2, "0")}:${$min.toString().padStart(2, "0")}:${$sec.toString().padStart(2, "0")}`;
                                }
                                $(e).eq($i).html($time);

                            }else{
                                // $(e).eq($i).parent().parent().parent().find('.cancel_btn').attr('hidden',false);
                                $('.cancel_btn').eq($i).removeAttr('hidden');
                            }

                        }
                    }

                    $(document).on('click','.cancel_btn',function(e){
                        $id = $(this).data('id');
                        $this = $(this);
                        Swal.fire({
                            icon : 'warning',
                            text : 'Are You Sure?',
                            showCancelButton: true,
                            cancelButtonText : 'No',
                            confirmButtonText: 'Yes',
                        }).then((result)=>{
                            if(result.isConfirmed){
                                $.ajaxSetup({
                                    headers : { 'X-CSRF_TOKEN' : $("meta[name='__token']").attr('content') }
                                })

                                $.ajax({
                                    url  : "{{ route('booking_cancel') }}",
                                    type : 'POST',
                                    data : {'id' :$id} ,
                                    beforeSend:function(){
                                        $this.text('Loading....');
                                        $this.addClass('pointer-events-none');
                                    },
                                    success: function(res){
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text : 'Booking Cancel Success'
                                        });
                                        location.reload();
                                    },
                                    complete:function(){
                                        $this.text('Cancel');
                                        $this.removeClass('pointer-events-none');
                                    }
                                })

                            }
                        })
                    })

                    $(document).on('click','.filter_nav',function(e){
                        $no = $(this).data('no');
                        $this = $(this);
                        switch ($no){
                            case 0 : $trans = 'translateX(0)';break;
                            case 1 : $trans = 'translateX(127px)';break;
                            case 2 : $trans = 'translateX(275px)';break;
                            case 3 : $trans = 'translateX(425px)';break;
                            default:break;
                        }
                        $.ajax({
                            url     : '/my_booking/ajax/filter/'+$no,
                            type    : 'GET',
                            success : function (res) {
                                if(res.status == 'fail'){
                                    $list = `
                                    <div class="text-center" style="min-height: 80vh">
                                        <span class="text-5xl text-slate-300" style="line-height: 80vh">There is no Data</span>
                                    </div>
                                    `;
                                    $('#table_layout').html($list);
                                }else{
                                    $('#table_layout').html(res);
                                }
                            },
                            error: function(xhr,status,error){
                            },
                            complete:function(){
                                $('.filter_highlight').css('transform',$trans);
                                $this.addClass('filter_select').siblings().removeClass('filter_select');
                            }
                        })

                    })

                    $(document).on('click','.start_btn',function(e){
                        $id = $(this).data('id');
                        $this = $(this);
                        $.ajax({
                            url : '/my_booking/ajax/start/'+$id,
                            type: 'get',
                            success : function(res){
                                $this.addClass('hidden');
                                $this.parent().find('.end_btn').removeClass('hidden');
                                $this.parent().parent().find('.booking_status').val(1);
                            }
                        })
                    })

                    $(document).on('click','.end_btn',function(e){
                        $id = $(this).data('id');
                        $this= $(this);
                        $.ajaxSetup({
                            headers : {'X-CSRF-TOKEN' : $('meta[name=__token]').attr('content')}
                        })
                        $.ajax({
                            url : "{{ route('end_booking') }}",
                            type: 'POST',
                            data: {'data':$id},
                            success:function(res){
                                $this.parent().parent().parent().remove();
                            }
                        })
                    })
                })
            </script>
        @endpush

@endsection
