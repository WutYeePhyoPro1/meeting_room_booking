@extends('new_layouts.admin.layout')
@section('content')
<div class="px-8 py-5 mt-4">
    <div class=" w-1/2 p-2 overflow-hidden rounded-lg mt-4 duration-500 whitespace-nowrap mx-auto shadow-lg " id="booking_div" style="background-color: rgb(255,250,223)">
        <input type="hidden" id="succ_msg" value="{{ Session::has('create') ? 1 : (Session::has('update') ? 2 : 0)}}">
        <input type="hidden" id="user_id" value="{{ getAuth()->id }}">
        <div class="text-center py-1">
            <span>Edit Booking</span><hr>
        </div>
        <form action="{{ route('booking_store') }}" method="POST" class="mt-3" id="booking_form">
            @csrf
            <div class="flex flex-col">
                <label for="title">Title <span class="text-red-600">*</span>:</label>
                <input type="text" class="h-7 mt-2 border-slate-200 rounded-md focus:ring-0 focus:border-b-4 focus:border-slate-400" name="title" id="title" value="{{ old('title',$data->title) }}">
                @error('title')
                    <small class="ml-2 text-red-600">{{ $message }}</small>
                @enderror
            </div>
            <div class="grid grid-cols-2 gap-2 mt-5">
                <div class="flex flex-col ">
                    <label for="">Room <span class="text-red-600">*</span>:</label>
                    <span class="h-7 w-full bg-white mt-2 border border-slate-300 rounded-md text-center select-none">{{ $data->room->room_name }}</span>
                    <input type="hidden" name="room_id" id="room_id" value="{{ $data->id }}">
                </div>
                <div class="flex flex-col">
                    <label for="date">Date <span class="text-red-600">*</span>:</label>
                    <input type="date" min="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d", strtotime(date("Y-m-d") . " +6 days"))?>" class="h-7 mt-2 border-slate-200 rounded-md focus:ring-0 focus:border-b-4 focus:border-slate-400" value="{{ $data->date }}" name="date" id="date">
                    @error('date')
                        <small class="ml-2 text-red-600">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2 mt-5">
                <div class="flex flex-col">
                    <label for="start_time">Start Time <span class="text-red-600">*</span>:</label>
                    <select id="start_time" name="start_time" class="h-10 mt-2 border-slate-200 rounded-t-md focus:ring-0 focus:border-slate-400 time_interval">
                        <option value="">Choose StartTime</option>
                    </select>
                    <input type="hidden" id="original_start" value="{{ $data->start_time }}">
                    @error('start_time')
                        <small class="ml-2 text-red-600">{{ $message }}</small>
                    @enderror
                </div>
                <div class="flex flex-col">
                    <label for="end_time">End Time <span class="text-red-600">*</span>:</label>
                    <select id="end_time" name="end_time" class="h-10 mt-2 border-slate-200 rounded-t-md focus:ring-0 focus:border-slate-400 time_interval">
                        <option value="">Choose EndTime</option>

                    </select>
                    <input type="hidden" id="original_end" value="{{ $data->end_time }}">
                    @error('end_time')
                        <small class="ml-2 text-red-600">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <input type="hidden" name="booking_id" id="booking_id">
            <div class="text-center mt-3">
                <span>duration ( <b id="total_duration">00:00:00</b> )</span>
                <input type="hidden" name="duration" id="duration" value="{{ old('duration') }}">
                <input type="hidden" id="og_duration" value="{{ $data->duration }}">
            </div>
            <div class="flex flex-col mt-5">
                <label for="reason">Title <span class="text-red-600">*</span>:</label>
                <select name="reason_id" id="reason" class="h-10 mt-2 border-slate-200 rounded-t-md focus:ring-0  focus:border-slate-400">
                    <option value="">Choose Reason</option>
                    @foreach ($reason as $item)
                        <option value="{{ $item->id }}" {{ old('reason_id',$data->reason_id) == $item->id ? 'selected' : '' }}>{{ $item->reason }}</option>
                    @endforeach
                </select>
                @error('reason')
                    <small class="ml-2 text-red-600">{{ $message }}</small>
                @enderror
            </div>
            <div class="flex flex-col mt-5">
                <label for="remark">Remark :</label>
                <textarea name="remark" id="remark" class="mt-2 border-slate-200 rounded-md focus:ring-0 focus:border-b-4 focus:border-slate-400" cols="30" rows="3">{{ old('remark',$data->remark) }}</textarea>
                @error('remark')
                    <small class="ml-2 text-red-600">{{ $message }}</small>
                @enderror
            </div>
            <div class="text-right mt-4" id="btn_div">
                <x-button type="submit" class="bg-emerald-400 w-24 h-10 ps-6 focus:ring-yellow-600 hover:bg-emerald-600">Update</x-button>
            </div>
            <input type="hidden" id="id" name="id" value="{{ $data->id }}">
        </form>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function(e){
            var id = $('#id').val();
            $.ajax({
            type : "GET",
            url  :  "/booking/ajax/event_click/"+id,
            beforeSend:function(){
                $('.time_interval').html('');
            },
            success: function(res){
                $list = '<option value="">Choose StartTime</option>';
                $list1= '<option value="">Choose EndTime</option>';
                $gl_vl = res.time;
                for($j = 0 ; $j <= res.time.length-1 ; $j++)
                {
                    $list += `
                    <option value="${res.time[$j]}" ${res.time[$j] == res.data.start_time ? 'selected':''}>${res.format_time[$j]}</option>
                    `;
                }
                for($i = 0 ; $i <= res.time.length-1 ; $i++)
                {
                    $list1 += `
                    <option value="${res.time[$i]}" ${res.time[$i] == res.data.end_time ? 'selected':''}>${res.format_time[$i]}</option>
                    `;

                }
                $('#start_time').prepend($list);
                $('#end_time').prepend($list1);
                $('#duration').val(res.data.duration);
                $('#total_duration').text(res.data.duration);

            },
            error: function(xhr,status,error){
                console.error('Event Click Error :' + error)
                Swal.fire({
                    icon: 'error',
                    title: 'Fail',
                    text : 'Fail To Check Event!!'
                })
            }
        })

        $(document).on('change','.time_interval',function(e){
                    $start_time = $('#start_time').val();
                    $end_time   = $('#end_time').val();
                    $og_start   = $('#original_start').val();
                    $og_end     = $('#original_end').val();
                    $og_dur     = $('#og_duration').val();
                    $s = $start_time.split(":");
                    $e = $end_time.split(":");
                    $s_total    = parseInt($s[0]*3600) + parseInt($s[1]*60) + parseInt($s[2]);
                    $e_total    = parseInt($e[0]*3600) + parseInt($e[1]*60) + parseInt($e[2]);
                    if($start_time == "" || $end_time == ""){
                        $('#total_duration').text('00:00:00');
                    }else{
                        if($s_total && $e_total){
                            $diff       = $e_total - $s_total;
                            if($diff <= 0){
                                Swal.fire({
                                    icon : "error",
                                    text : "end time က start time ထက်  စောနေပါ သည်"
                                })
                                $('#start_time').val($og_start);
                                $('#end_time').val($og_end);
                                $('#total_duration').text($og_dur);
                                $('#duration').val($og_dur);
                            }else{
                                $time = new Date($diff * 1000).toISOString().substring(11, 19)
                                $('#total_duration').text($time);
                                $('#duration').val($time);
                                $('#duration').trigger('textChanged');
                            }
                        }
                    }
                })
        })
    </script>
@endpush
@endsection
