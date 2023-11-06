@extends('new_layouts.user.layout')
@section('content')
    <div class="w-1/3 mx-auto mt-10">
            <div class="max-w bg-whiterounded-lg" style="box-shadow: 3px 4px 4px rgb(0, 0, 0,0.4) , -2px -2px 4px rgb(0, 0, 0,0.4)">
                <div class="mt-10 pt-3 ms-3">
                    <span class="px-10 ring-2 ring-offset-1 ring-slate-200 py-2 rounded-lg" style="background-color: {{ $data->user->bg_color }};color:{{ $data->user->text_color }}">{{ $data->user->name }}</span>
                    <input type="hidden" id="req_booking_id" value="{{ $data->id }}">
                </div>

                <input type="hidden" id="success" data-msg="{{ Session::has('success') ? Session::get('success') : '' }}" value="{{ Session::has('success') ? 1 : 0 }}">

                <div class="flex flex-col text-center mb-10">
                    <i class="material-symbols-outlined text-8xl cursor-auto select-none">{{ $data->user->icon }}</i>
                </div>
                {{-- {{ $data->booking }} --}}
                <div class="overflow-hidden max-h-full main_div">
                    <div class="grid grid-cols-2 gap-6 mt-3 ms-10">
                        <span>Request Duration </span>
                        <span>: {{ $data->total_duration }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-6 mt-3 ms-10">
                        <span>Request time </span>
                        <div class="flex flex-col">
                            <span>: {!! calculate_req_time($data)!!}</span>
                            <span>&nbsp;{{ '('.date('g:i A',strtotime($data->booking->original_start ? $data->booking->original_start : $data->booking->start_time)) .'~'.date('g:i A',strtotime($data->booking->original_end ? $data->booking->original_end : $data->booking->end_time)).')' }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-6 mt-3 ms-10">
                        <span>Request Date </span>
                        <span>: {{ $data->booking->date }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-6 mt-3 ms-10">
                        <span>Room </span>
                        <span>: {{ $data->booking->room->room_name }}</span>
                    </div>
                    @if ( $data->request_status == 0 || $data->request_status == 5)
                        <form action="" method="POST">
                            @csrf
                            <input type="hidden" name="req_id" id="req_id" value="{{ $data->id }}">
                            <input type="hidden" name="req_user_id" id="req_user_id" value="{{ $data->request_user }}">
                            <div class="{{$data->request_status == 5 ? 'grid-cols-2' : 'grid-cols-3' }} grid  mt-10">
                                <button type="submit" formaction="{{ route('request_reject') }}" name="action" value="reject" class="font-semibold text-slate-500 hover:bg-rose-500 duration-500 hover:text-white border py-4">Reject</button>
                                @if ($data->request_status == 0)
                                    <button type="button" class="font-semibold text-slate-500 hover:bg-sky-500 duration-500 hover:text-white border py-4 resent_btn">Resend</button>
                                @endif
                                <button type="submit" formaction="{{ route('request_accept') }}" name="action" value="accept" class="font-semibold text-slate-500 hover:bg-emerald-500 duration-500 hover:text-white border py-4">Accept</button>
                            </div>
                        </form>
                    @elseif ( $data->request_status == 1 )
                        @if ($data->request_user == getAuth()->id)
                            <div class="mt-10 px-10 mb-4">
                                <span class="font-semibold text-emerald-500">Your Request Have Been Accepted By {{ $data->approve->name}}</span>
                            </div>
                        @else
                            <div class="mt-10 px-10 mb-4">
                                <span class="font-semibold text-emerald-500">You Have Accept Request From {{ $data->user->name }}</span>
                            </div>
                        @endif
                    @elseif ( $data->request_status == 2 )
                        <div class="mt-10 px-10 mb-4">
                            <span class="font-semibold text-rose-500">The System Has Reject Since Exceed the meeting start time {{ $data->booking->date .' '. date('g:i A',strtotime($data->booking->start_time)) }}</span>
                        </div>
                    @elseif ( $data->request_status == 3 && getAuth()->id != $data->request_user)
                        <div class="mt-10 px-10 mb-4">
                            <span class="font-semibold text-rose-500">You Had Rejected This Request</span>
                        </div>
                    @elseif( $data->request_status == 3 && getAuth()->id == $data->request_user)
                        <div class="mt-10 px-10 mb-4">
                            <span class="font-semibold text-rose-500">You Had been rejected</span>
                        </div>
                        @elseif( $data->request_status == 4)
                        <div class="mt-10 px-10 mb-4">
                            <span class="font-semibold text-sky-500">You Resended Your New Condition To {{ $data->user->name }}</span>
                        </div>
                    @endif
                </div>
                @if ($data->request_status == 0)
                <div class="resent_div max-h-0 overflow-hidden">
                    <div class="grid grid-cols-2 gap-6 mt-3 ms-10">
                        <span class="mt-2">Request Duration </span>
                        <select class="rounded-t-md h-8 text-black p-0 ps-2 me-10 mb-3 focus:border-slate-500 focus:ring-0 resend_duration" >
                            @foreach (avaliable_duration($data->booking->id) as $item)
                            <option value="{{ $item }}" {{ $item == $data->total_duration ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" id="from" value="{{ $data->from }}">
                        <input type="hidden" id="start_time" value="{{ $data->booking->start_time }}">
                        <input type="hidden" id="end_time" value="{{ $data->booking->end_time }}">
                        <input type="hidden" id="duration" value="{{ $data->total_duration }}">
                    </div>
                    <div class="grid grid-cols-2 gap-6 my-3 ms-10">
                        <span>Request time </span>
                        <div class="flex flex-col">
                            <span id="from_to">: {!! calculate_req_time($data)!!}</span>
                            <span>&nbsp;{{ '('.date('g:i A',strtotime($data->booking->start_time)) .'~'.date('g:i A',strtotime($data->booking->end_time)).')' }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 mt-10">
                        <button class="font-semibold text-slate-500 hover:bg-rose-500 duration-500 hover:text-white border py-4 cancel_btn">Cancel</button>
                        <button class="font-semibold text-slate-500 hover:bg-sky-500 duration-500 hover:text-white border py-4 send_btn">Send</button>
                    </div>
                </div>
                @endif
            </div>
    </div>
    @push('js')
        <script>
            $(document).ready(function(e){
                read();

                $success = $('#success').val();
                if($success == 1){
                    $msg = $('#success').data('msg');
                    Swal.fire({
                        icon: 'success',
                        text: $msg,
                    })
                }

                function read(){
                    $id = $('#req_booking_id').val();
                    $.ajax({
                        url : '/booking/ajax/read_noti/'+$id,
                        type: 'get',
                        success:function(res){

                        }
                    })
                }

                $(document).on('change','#duration',function(e){
                    $val    = $(this).val();
                    $from   = $('#from').val();
                    $start  = new Date(`1970-01-01 ${$('#start_time').val()}`);
                    $end    = new Date(`1970-01-01 ${$('#end_time').val()}`);
                    $dura = $val.split(':');
                    $total_sec = $dura[0]*1000*60*60 + $dura[1]*1000*60 + $dura[2]*1000;
                    $start_str = $start.getTime();
                    $end_str   = $end.getTime();

                    if($from == 'start')
                    {
                        $end_str = $start_str + $total_sec;
                        $end = new Date().setTime($end_str);
                    }else if($from == 'end')
                    {
                        $end_str = $end_str - $total_sec;

                        $start = new Date().setTime($end_str)
                    }
                    $start = moment($start).format('hh:mm A');
                    $end = moment($end).format('hh:mm A');

                    $msg  = `From <b class="text-emerald-500">${$start}</b> To <b class="text-emerald-500">${$end}</b>`;
                    $('#from_to').html($msg);
                })

                $(document).on('click','.resent_btn',function(e){
                    $('.main_div').removeClass('max-h-full');
                    $('.main_div').addClass('max-h-0');
                    $('.resent_div').removeClass('max-h-0');
                    $('.resent_div').addClass('max-h-full');
                })

                $(document).on('click','.cancel_btn',function(e){
                    $('.resent_div').removeClass('max-h-full');
                    $('.resent_div').addClass('max-h-0');
                    $('.main_div').removeClass('max-h-0');
                    $('.main_div').addClass('max-h-full');
                })

                $(document).on('click','.send_btn',function(e){
                    $from = $('#from').val();
                    $duration = $('.resend_duration').val();
                    $og_duration = $('#duration').val();
                    $id         = $('#req_booking_id').val();
                    $data = {
                        'duration'  : $duration,
                        'from'      : $from,
                        'id'        : $id
                    };
                    if($duration == $og_duration){
                        Swal.fire({
                            icon : 'error',
                            text : 'condition ပြောင်းပြီးမှ send လုပ်ပါ'
                        })
                    }else{
                        $.ajaxSetup({
                            headers : { 'X-CSRF-TOKEN' : $('meta[name=__token]').attr('content') }
                        })

                        $.ajax({
                            url : "{{ route('resend_noti') }}",
                            type: 'POST',
                            data: $data,
                            success:function(res){
                                Swal.fire({
                                    icon : 'success',
                                    text : 'Resend Success',
                                    confirmButtonText : 'OK'
                                }).then((result)=>{
                                    if(result.isConfirmed){
                                        window.location.reload();
                                    }
                                })
                            }
                        })
                    }
                })
            })
        </script>
    @endpush
@endsection
