<div class="grid grid-cols-3 gap-4 px-2 pb-4 pt-2 mt-4 ">
    @foreach ($booking as $item)
        <div class="max-w bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 px-1 overflow-hidden bg_img1" style="background: linear-gradient(rgba(61, 57, 57, 0.7), rgba(59, 57, 57, 0.7)),url('{{ asset('storage/uploads/room_image/'.$item->room->image->file_name) }}');background-repeat:no-repeat;background-position:center;background-size:cover;color:white;">
            <div class="text-center py-2 my_booking_card ">
                <span class="booking_title">{{ $item->title }}</span>
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
                        @if (getAuth()->id != $item->user_id)
                        <button class="bg-emerald-400 hover:bg-emerald-500 py-2 px-10 rounded-md req_btn" data-id="{{ $item->id }}">Request</button>
                        @endif
                    </div>
                </div>
                <div class="w-0 overflow-hidden req_form_div duration-500 whitespace-nowrap">
                    <form action="{{ route('request_booking') }}" method="POST">
                        @csrf
                        <input type="hidden" class="text-black" name="booking_id" value="{{ $item->id }}">

                        <div class="grid grid-cols-2 gap-2 mb-9 mt-4 px-4 my_booking_card">
                            <span >Request Duration :</span>
                            <select class="rounded-t-md h-8 text-black p-0 ps-2 total_duration" name="total_duration" id="">
                                <option value="">Choose Duration</option>
                                @foreach ( avaliable_duration($item->id) as $item )
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach ()
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-2 mb-9 mt-4 px-4 my_booking_card">
                            <span >From :</span>
                            <select class="rounded-t-md h-8 text-black p-0 ps-2 from" name="from" id="">
                                <option value="">Choose From</option>
                                <option value="start">Start Time</option>
                                <option value="end">End Time</option>
                            </select>
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
