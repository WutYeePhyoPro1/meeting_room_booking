@extends('new_layouts.user.layout')
@section('content')
<div id="table_layout">
    @if (count($data) > 0)
    <div class="grid grid-cols-3 gap-6 px-10 pb-4 pt-2 mt-6">
        @foreach ($data as $item)
            <div class="max-w bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 px-3">
                <div class="text-center py-2">
                    <span>Booking Request</span>
                </div><hr>
                <div class="grid grid-cols-2 gap-6 mt-3 ms-7">
                    <span>Request By :</span>
                    <span>{{ $item->user->name }}</span>
                </div>
                <div class="grid grid-cols-2 gap-6 mt-3 ms-7">
                    <span>Request Duration :</span>
                    <span>{{ $item->total_duration }}</span>
                </div>
                <div class="grid grid-cols-2 gap-6 mt-3 ms-7">
                    <span>Request time :</span>
                    <div class="flex flex-col">
                        <span>{{ calculate_req_time($item)}}</span>
                        <span>{{ '('.date('g:i A',strtotime($item->booking->start_time)) .'~'.date('g:i A',strtotime($item->booking->end_time)).')' }}</span>
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
@endsection
