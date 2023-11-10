@extends('new_layouts.admin.layout')
@section('content')
<div class="w-1/2 mx-auto mt-5">
    <x-button class="bg-transparent text-slate-900 hover:text-white px-5 hover:bg-slate-400 focus:ring-slate-800" id="back_btn">
        <i class="material-symbols-outlined">
            arrow_back
            </i>
    </x-button>
</div>
<div class="w-1/2 mx-auto px-6 pt-2">
    {{-- @dd(check_extendable($item->id)) --}}
            <div class="max-w bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 bg_img1" style="background: linear-gradient(rgba(61, 57, 57, 0.7), rgba(59, 57, 57, 0.7)),url('{{ asset('storage/uploads/room_image/'.$data->room->image->file_name) }}');background-repeat:no-repeat;background-position:center;background-size:cover;color:white;">
                <div class="text-center py-2 my_booking_card">
                    {{ $data->title }}
                </div>
                <hr>
                <div class="card_div">
                    <div class="grid grid-cols-2 gap-2 mb-9 mt-4 px-4 my_booking_card">
                        <span >Room :</span>
                        <span >{{ $data->room->room_name }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 my-9 px-4 my_booking_card">
                        <span >Date & Time :</span>
                        <span >{{ $data->date  }}&nbsp;&nbsp;|&nbsp;&nbsp;{{ date('g:i A',strtotime($data->start_time)) .'~'.date('g:i A',strtotime($data->end_time)) }}</span>
                    </div>
                    @if ($data->original_start)
                        <div class="grid grid-cols-2 gap-2 my-9 px-4 my_booking_card">
                            <span >Original Time :</span>
                            <span >{{ date('g:i A',strtotime($data->original_start)) .'~'.date('g:i A',strtotime($data->original_end)) }}</span>
                        </div>
                    @endif
                    <div class="grid grid-cols-2 gap-2 my-9 px-4 my_booking_card">
                        <span >Duration :</span>
                        <span >{{ $data->duration }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 my-9 px-4 my_booking_card">
                        <span >Reason :</span>
                        <span >{{ $data->reason->reason }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 my-9 px-4 my_booking_card remark">
                        <span >Remark :</span>
                        <span class="break-all">{{ $data->remark }}</span>
                    </div>
                </div>
            </div>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-5">
        @foreach ($req_book as $item)
        <div class="mx-auto mt-10">
            <div class="max-w bg-whiterounded-lg" style="box-shadow: 3px 4px 4px rgb(0, 0, 0,0.4) , -2px -2px 4px rgb(0, 0, 0,0.4)">
                <div class="mt-10 pt-3 ms-3">
                    <span class="px-10 ring-2 ring-offset-1 ring-slate-200 py-2 rounded-lg" style="background-color: {{ $item->user->bg_color }};color:{{ $item->user->text_color }}">{{ $item->user->name }}</span>
                </div>

                <div class="flex flex-col text-center">
                    <i class="material-symbols-outlined text-8xl cursor-auto select-none">{{ $item->user->icon }}</i>
                </div>
                <div class="flex flex-col text-center my-2 underline">
                    <span>{{ $item->booking->title }}</span>
                </div>
                {{-- {{ $item->booking }} --}}
                <div class="overflow-hidden max-h-full main_div">
                    <div class="grid grid-cols-2 gap-6 mt-3 ms-10">
                        <span>Request Duration </span>
                        <span>: {{ $item->total_duration }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-6 mt-3 ms-10">
                        <span>Request time </span>
                        <div class="flex flex-col">
                            <span>: {!! calculate_req_time($item)!!}</span>
                            <span>&nbsp;{{ '('.date('g:i A',strtotime($item->booking->original_start ? $item->booking->original_start : $item->booking->start_time)) .'~'.date('g:i A',strtotime($item->booking->original_end ? $item->booking->original_end : $item->booking->end_time)).')' }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-6 mt-3 ms-10">
                        <span>Request Date </span>
                        <span>: {{ $item->booking->date }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-6 mt-3 ms-10">
                        <span>Room </span>
                        <span>: {{ $item->booking->room->room_name }}</span>
                    </div>
                    @if ( $item->request_status == 0 || $item->request_status == 5)
                        <div class="mt-10 px-10 mb-4">
                            <span class="font-semibold text-amber-500">Request is Waiting Decision from {{ $item->booking->user->name }}</span>
                        </div>
                    @elseif ( $item->request_status == 1 )
                        <div class="mt-10 px-10 mb-4">
                            <span class="font-semibold text-emerald-500">{{ $item->approve->name }} Have Accept Request From {{ $item->user->name }}</span>
                        </div>
                    @elseif ( $item->request_status == 2 )
                        <div class="mt-10 px-10 mb-4">
                            <span class="font-semibold text-rose-500">The System Has Reject Since Exceed the meeting start time {{ $item->booking->date .' '. date('g:i A',strtotime($item->booking->start_time)) }}</span>
                        </div>
                    @elseif ( $item->request_status == 3 && getAuth()->id != $item->request_user)
                        <div class="mt-10 px-10 mb-4">
                            <span class="font-semibold text-rose-500">{{ $item->approve->name }} Had Rejected This Request</span>
                        </div>
                        @elseif( $item->request_status == 4)
                        <div class="mt-10 px-10 mb-4">
                            <span class="font-semibold text-sky-500">{{ $item->approve->name }} Resended Your New Condition To {{ $item->user->name }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    @endforeach
    </div>

    @push('js')
        <script>
            $(document).ready(function(){
                $(document).on('click','#back_btn',function(e){
                    $url = localStorage.getItem('admin_detail');
                    localStorage.removeItem('admin_detail');
                    if($url){
                        window.location.href = $url;
                    }else{
                        window.history.back();
                    }
                })
            })
        </script>
    @endpush
@endsection
