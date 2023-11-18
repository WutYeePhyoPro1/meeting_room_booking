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

        <input type="hidden" id="success" value="{{ Session::has('success') ? Session::get('success') : '' }}">
        <input type="hidden" id="error" value="{{ $errors->any() ? 1 : 0 }}">
        <div class="px-4 py-2 mt-3 text-center w-1/2 rounded-2xl mx-auto relative filter_div">
            <span class="absolute w-20 rounded-2xl border-b-2 border-rose-500 ms-3 bottom-0 duration-500 filter_highlight" style="border-color: {{ getAuth()->bg_color }}"></span>
            <span class="text-xl px-10 rounded-2xl py-2 cursor-pointer select-none  duration-500 filter_nav filter_select" data-no="0">All</span>
            <span class="text-xl px-10 rounded-2xl py-2 cursor-pointer select-none  duration-500 filter_nav" data-no="1">Room1</span>
            <span class="text-xl px-10 rounded-2xl py-2 cursor-pointer select-none  duration-500 filter_nav" data-no="2">Room2</span>
            <span class="text-xl px-10 rounded-2xl py-2 cursor-pointer select-none  duration-500 filter_nav" data-no="3">Room3</span>
        </div>
        <div id="table_layout">
            @if (count($booking) > 0)
            <div class="grid grid-cols-3 gap-4 px-2 pb-4 pt-2 mt-4 ">
                @foreach ($booking as $item)
                    <div class="max-w bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 px-1 overflow-hidden bg_img1" style="background: linear-gradient(rgba(61, 57, 57, 0.7), rgba(59, 57, 57, 0.7)),url('{{ asset('storage/uploads/room_image/'.$item->room->image->file_name) }}');background-repeat:no-repeat;background-position:center;background-size:cover;color:white;">
                        <div class="text-center py-2 my_booking_card ">
                            <span class="booking_title">{{ $item->title }}
                                @if (is_request($item->id))
                                    @switch(is_request($item->id)->request_status)
                                        @case(0)
                                            <span class="text-amber-500">(Pending)</span>
                                            @break
                                        @case(1)
                                            <span class="text-emerald-500">(Accept)</span>
                                            @break
                                        @case(2)
                                            <span class="text-rose-500">(Reject)</span>
                                            @break
                                        @case(3)
                                            <span class="text-rose-500">(Reject)</span>
                                            @break
                                        @case(4)
                                            <span class="text-sky-500">(Resend)</span>
                                            @break
                                        @case(5)
                                            <span class="text-sky-500">(Resended)</span>
                                            @break
                                        @default

                                    @endswitch
                                @endif
                            </span>
                        </div>
                        <hr>
                        <div class="flex">
                            <div class="card_div w-full duration-500 whitespace-nowrap overflow-hidden">
                                <div class="grid grid-cols-2 gap-2 mb-9 mt-4 px-4 my_booking_card">
                                    <span >Room :</span>
                                    <span >{{ $item->room->room_name }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 my-9 px-4 my_booking_card">
                                    <span >Date & Time :</span>
                                    <span >{{ $item->date  }}&nbsp;&nbsp;|&nbsp;&nbsp;{{ date('g:i A',strtotime($item->start_time)) .'~'.date('g:i A',strtotime($item->end_time)) }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 my-9 px-4 my_booking_card">
                                    <span >Remaining Time :</span>
                                    <span data-date="{{ $item->date }}" data-start="{{ $item->start_time }}" class="remaining_time">{{ getRemainingTime($item->date,$item->start_time) }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 my-9 px-4 my_booking_card">
                                    <span >Owner :</span>
                                    <span >{{ $item->user->name }}</span>
                                </div>
                                <div class="flex justify-between mt-9 mb-2 px-4 my_booking_card">
                                    @if (auth()->id() != $item->user_id && $item->start_time < \Carbon\Carbon::now())
                                    <button class="bg-emerald-400 hover:bg-emerald-500 py-2 px-10 rounded-md req_btn {{ is_request($item->id) ? (is_request($item->id)->request_status != 0 ? 'hidden' : '') : '' }}" data-id="{{ $item->id }}">Request</button>
                                    @endif
                                </div>
                            </div>
                            <div class="w-0 overflow-hidden req_form_div duration-500 whitespace-nowrap">
                                <form action="{{ route('request_booking') }}" method="POST">
                                    @csrf
                                    <input type="hidden" class="text-black" name="booking_id" value="{{ $item->id }}">

                                    <div class="grid grid-cols-2 gap-2 mb-9 mt-4 px-4 my_booking_card">
                                        <span >Request Time :</span>
                                        <div class="slide_container">
                                            <input type="range" class="left_range" value="0" min="0" max="{{ get_step($item->id) }}" step="1" id="">
                                            <input type="range" class="right_range" value="{{ get_step($item->id) }}" min="0" max="{{ get_step($item->id) }}" step="1" id="">

                                            <div class="slider">
                                                <div class="track"></div>
                                                <div class="range"></div>
                                                <div class="thumb left"></div>
                                                <div class="thumb right"></div>
                                            </div>
                                            <input type="hidden" class="from" name="from" value="start">
                                            <input type="hidden" class="total_duration" name="total_duration" value="{{ $item->duration }}">
                                            <div class="meg mt-2 text-center">
                                                <span class="from_time" data-from="{{ $item->start_time }}">{{ date('g:i A',strtotime($item->start_time)) }}</span>&nbsp;&nbsp;~&nbsp;&nbsp;
                                                <span class="to_time" data-to="{{ $item->end_time }}">{{ date('g:i A',strtotime($item->end_time)) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2 mb-9 mt-4 px-4 my_booking_card">
                                        <span >reason :</span>
                                        <textarea name="reason" class="text-black reason" placeholder="reason..." id="" cols="30" rows="3"></textarea>
                                    </div>
                                    <div class="gmb-9 my-4 px-4 flex justify-between">
                                        <button type="button" class="bg-rose-500 px-5 py-2 rounded-md cancel_btn">Cancel</button>
                                        <button type="submit" class="bg-emerald-500 px-6 py-2 rounded-md mr-5 send_btn">Send</button>
                                    </div>
                                </form>
                            </div>
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

    @push('css')
        <script>
            $(document).ready(function(e){
                $success = $('#success').val();
                $error = $('#error').val();
                if($error == 1)
                {
                    Swal.fire({
                        'icon' : 'error',
                        'text' : 'ကျေးဇူပြုပြီး အချက်အလက်အပြည့်အစုံဖြည့်ပါ'
                    });
                }
                if($success != ''){
                    Swal.fire({
                        'icon' : 'success',
                        'text' : $success
                    });
                }

                var length = $('.remaining_time').length;
                    setInterval(() => {
                        time_before($('.remaining_time'),length);
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
                                $('.bg_img1').eq($i).hide();
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
                            url     : '/today_booking/ajax/filter/'+$no,
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

                    $(document).on('click','.req_btn',function(e){
                        $val = $(this).data('id');
                        $this = $(this);
                        $max = $(this).parent().parent().parent().find('.right_range').attr('max');
                        $step_width = (100 / $max).toFixed(5);

                        $right_range = $this.parent().parent().parent().find('.right_range');
                        $left_range = $this.parent().parent().parent().find('.left_range');
                        $range = $this.parent().parent().parent().find('.range');
                        $right = $this.parent().parent().parent().find('.thumb.right');
                        $left  = $this.parent().parent().parent().find('.thumb.left');
                        $from_time = $this.parent().parent().parent().find('.from_time');
                        $to_time = $this.parent().parent().parent().find('.to_time');

                        $og_start = $this.parent().parent().parent().find('.from_time').data('from');
                        $og_end = $this.parent().parent().parent().find('.to_time').data('to');
                        $.ajax({
                            type : 'get',
                            url  : '/today_booking/ajax/booking_request/'+$val,
                            success: function(res){
                                if(res.status == 'exist')
                                {
                                    $this.parent().parent().parent().find('.slide_container').addClass('exist');
                                    $this.parent().parent().parent().find('.reason').prop('disabled',true);
                                    $this.parent().parent().parent().find('.reason').text(res.data.request_reason);
                                    $this.parent().parent().parent().find('.send_btn').attr('hidden',true);
                                    $all  = res.data.total_duration.split(':');
                                    $total= $all[0]*3600000 + $all[1]*60000 + $all[2]*1000;
                                    $step = $total/(30*60*1000);
                                    $real_val = $max-$step;
                                    $width = $real_val == 1 || $real_val == 0 ? $step_width*$real_val : ($step_width*$real_val - 4);
                                    if(res.data.from == 'start'){
                                        $start_time = new Date(moment().format('YYYY-MM-DD') +' '+$og_start).getTime();
                                        $final_end= moment($start_time+$total).format('h:mm A');

                                        $range.css('right',$width +'%');
                                        $right.css('right',$width +'%');
                                        $right_range.val($step);
                                        $left_range.val(0);
                                        $to_time.text($final_end);
                                    }else{
                                        $end_time = new Date(moment().format('YYYY-MM-DD') +' '+$og_end).getTime();
                                        $final_start= moment($end_time-$total).format('h:mm A');

                                        $range.css('left',$width +'%');
                                        $left.css('left',$width +'%');
                                        $left_range.val($real_val);
                                        $right_range.val($max);
                                        $from_time.text($final_start);
                                    }
                                    $status = '';
                                    $text_color = '';
                                    switch (res.data.request_status){
                                        case(0) : $status = 'Pending' ; $text_color = 'text-amber-500';break;
                                        case(1) : $status = 'Accept'; $text_color = 'text-emerald-500';break;
                                        case(2) : $status = 'Reject'; $text_color = 'text-rose-500';break;
                                        case(3) : $status = 'Reject'; $text_color = 'text-rose-500';break;
                                        case(4) : $status = 'Waiting your answer'; $text_color = 'text-rose-500';break;
                                        default :break;
                                    }
                                }
                            },
                            complete:function(){
                                $this.parent().parent().removeClass('w-full');
                                $this.parent().parent().addClass('w-0');
                                $this.parent().parent().parent().find('.req_form_div').removeClass('w-0');
                                $this.parent().parent().parent().find('.req_form_div').addClass('w-full');
                            }
                        })

                    })

                    $(document).on('click','.cancel_btn',function(e){
                        $(this).parent().parent().parent().removeClass('w-full');
                        $(this).parent().parent().parent().addClass('w-0');
                        $(this).parent().parent().parent().parent().find('.card_div').removeClass('w-0');
                        $(this).parent().parent().parent().parent().find('.card_div').addClass('w-full');
                        $(this).parent().parent().parent().parent().parent().find('.req_status').remove();
                    })

                    $(document).on('input','.left_range',function(e){
                        $val = $(this).val();
                        $max = $(this).attr('max');
                        $start_time = $(this).parent().find('.from_time').data('from');
                        $end_time = $(this).parent().find('.to_time').data('to');

                        $step_width = (100 / $max).toFixed(5);
                        $val = $val == $max ? $val - 1 : $val;
                        $(this).val($val);
                        $width = $val == 1 || $val == 0 ? $step_width*$val : ($step_width*$val - 4);
                        $start_str = new Date(moment().format('YYYY-MM-DD') +' '+$start_time).getTime();
                        $end_str = new Date(moment().format('YYYY-MM-DD') +' '+$end_time).getTime();

                        $sec = (1000*60*30)*$val;
                        $final_start = $start_str+$sec;

                        $duration = moment.utc($end_str-$final_start).format('HH:mm:ss');

                        $left_thumb = $(this).parent().find('.thumb.left');
                        $right_thumb = $(this).parent().find('.thumb.right');
                        $range = $(this).parent().find('.range');
                        $right = $(this).parent().find('.right_range');


                        $right.val($max);
                        $left_thumb.css('left',$width+'%');
                        $right_thumb.css('right',0);
                        $range.css('left',$width+'%');
                        $range.css('right',0);
                        $(this).parent().find('.from_time').text(moment($final_start).format('h:mm A'))
                        $(this).parent().find('.to_time').text(moment($end_str).format('h:mm A'))
                        $(this).parent().find('.from').val('end');
                        $(this).parent().find('.total_duration').val($duration);
                    })

                    $(document).on('input','.right_range',function(e){
                        $val = $(this).val();
                        $max = $(this).attr('max');
                        $start_time = $(this).parent().find('.from_time').data('from');
                        $end_time = $(this).parent().find('.to_time').data('to');

                        $for_show = $max - $val;
                        $step_width = (100 / $max).toFixed(5);
                        $for_show = $for_show == $max ? Math.floor($for_show - 1) : $for_show;
                        $val = $val == 0 ? Math.floor($val + 1) : $val;
                        $(this).val($val);
                        $width = $for_show == 1 || $for_show == 0 ? $step_width*$for_show : ($step_width*$for_show - 4);
                        $start_str = new Date(moment().format('YYYY-MM-DD') +' '+$start_time).getTime();
                        $end_str = new Date(moment().format('YYYY-MM-DD') +' '+$end_time).getTime();

                        $sec = (1000*60*30)*$for_show;
                        $final_end = $end_str-$sec;

                        $duration = moment.utc(Math.abs($start_str-$final_end)).format('HH:mm:ss');

                        $left_thumb = $(this).parent().find('.thumb.left');
                        $right_thumb = $(this).parent().find('.thumb.right');
                        $range = $(this).parent().find('.range');
                        $left = $(this).parent().find('.left_range');


                        $left.val(0);
                        $left_thumb.css('left',0);
                        $right_thumb.css('right',$width+'%');
                        $range.css('right',$width+'%');
                        $range.css('left',0);
                        $(this).parent().find('.from_time').text(moment($start_str).format('h:mm A'))
                        $(this).parent().find('.to_time').text(moment($final_end).format('h:mm A'))
                        $(this).parent().find('.from').val('start');
                        $(this).parent().find('.total_duration').val($duration);
                        // console.log($duration);
                    })
            })
        </script>
    @endpush
@endsection
