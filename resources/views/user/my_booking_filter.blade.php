<div class="grid grid-cols-3 gap-6 px-6 pb-4 pt-2 mt-4 ">
                @foreach ($booking as $item)
                {{-- @dd(check_extendable($item->id)) --}}
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
                                @if (getAuth()->employee_id == '111-111111')
                                    <div class="grid grid-cols-2 gap-2 my-9 px-4 my_booking_card">
                                        <span >owner :</span>
                                        <span >{{ $item->user->name }}</span>
                                    </div>
                                @endif
                                <div class="grid grid-cols-2 gap-2 my-9 px-4 my_booking_card remark">
                                    <span >Remark :</span>
                                    <span class="break-all">{{ $item->remark }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 my-9 px-4 my_booking_card extend_div hidden">

                                </div>
                                <input type="hidden" class="started" value="{{ $item->status == 1 ? 1 : 0 }}">
                                <div class="flex justify-between mt-9 mb-2 px-4 my_booking_card all_btn_gp">
                                    <input type="hidden" id="extendable" value="{{ count(check_extendable($item->id)) > 0 ? 1 : 0 }}">
                                    <input type="hidden" class="room_status" value="{{ $item->room->status }}">
                                    <button class="bg-emerald-400 hover:bg-emerald-500 py-2 px-10 rounded-md hidden start_btn" data-id="{{ $item->id }}">Start</button>
                                    <button class="bg-rose-300 hover:bg-rose-400 py-2 rounded-md px-10 end_btn {{ $item->status == 1 ? '' : 'hidden' }}" data-id="{{ $item->id }}" >End</button>
                                    <button class="bg-sky-500 hover:bg-sky-600 py-2 rounded-md px-10 extend_btn {{ ($item->status == 1 && count(check_extendable($item->id))>0 && !$item->extend_status) ? '' : 'hidden' }}" data-id="{{ $item->id }}">Extend</button>
                                    <button class="bg-red-500 hover:bg-red-600 py-2 rounded-md px-10 cancel_btn" hidden data-id="{{ $item->id }}">Cancel</button>
                                </div>
                                <div class="flex justify-between mt-9 mb-2 px-4 my_booking_card hidden extend_btn_gp">
                                    <button class="bg-red-500 hover:bg-red-600 py-2 rounded-md px-10 back_btn" >Back</button>
                                    <form action="{{ route('extend') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <input type="hidden" name="extend_time" class="extend_time">
                                        <button type="submit" class="bg-emerald-400 hover:bg-emerald-500 py-2 px-10 rounded-md accept_btn">Accept</button>
                                    </form>
                                </div>
                                <input type="hidden" class="booking_status" value="{{ $item->status }}">
                            </div>
                        </div>
                    @endforeach
                </div>