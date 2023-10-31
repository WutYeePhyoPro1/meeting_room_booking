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
                    <button class="bg-emerald-400 hover:bg-emerald-500 py-2 px-10 rounded-md start_btn" {{ $item->status == 1 ? 'hidden' : 'hidden' }}>Start</button>
                    <button class="bg-rose-300 hover:bg-rose-400 py-2 rounded-md px-10 {{ $item->status == 1 ? '' : 'hidden' }}" >End</button>
                    <button class="bg-red-500 hover:bg-red-600 py-2 rounded-md px-10 cancel_btn" hidden data-id="{{ $item->id }}">Cancel</button>
                </div>

            </div>
        </div>
    @endforeach
</div>
