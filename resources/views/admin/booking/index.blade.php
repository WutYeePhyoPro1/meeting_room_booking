@extends('new_layouts.admin.layout')
@section('content')

    <div class="my-4 mx-2 flex justify-between">
        <span class="text-xl">All Booking</span>
    </div>
    <form action="{{ route('admin#booking') }}" method="GET">
        <div class="p-5 grid grid-cols-5 gap-8">
            <div class="flex flex-col">
                <label for="room">Room :</label>
                <select name="room" id="room" class="mt-2 border-1 border-slate-400 text-slate-700 rounded-t-lg focus:border-slate-400 focus:ring-0">
                    <option value="">Choose Room</option>
                    @foreach ($rooms as $item)
                    <option value="{{ $item->id }}" {{ $item->id == request('room') ? 'selected' : '' }} >{{ $item->room_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <label for="from_date">From Date :</label>
                <input type="date" id="from_date" value="{{ request('from_date') }}" name="from_date" class="mt-2 border-1 text-slate-700 border-slate-400 rounded-lg focus:ring-0 focus:border-b-4  focus:border-slate-400 placeholder-slate-300">
            </div>
            <div class="flex flex-col">
                <label for="to_date">To Date :</label>
                <input type="date" id="to_date" value="{{ request('to_date') }}" name="to_date" class="mt-2 border-1 text-slate-700 border-slate-400 rounded-lg focus:ring-0 focus:border-b-4  focus:border-slate-400 placeholder-slate-300">
            </div>
            <div class="flex flex-col">
                <label for="status">Status :</label>
                <select name="status" id="status" class="mt-2 border-1 border-slate-400 text-slate-700 rounded-t-lg focus:border-slate-400 focus:ring-0">
                    <option value="">Choose Status</option>
                    <option value="6" {{ request('status') == 6 ? 'selected' : ''}} >Pending</option>
                    <option value="1" {{ request('status') == 1 ? 'selected' : '' }} >Started</option>
                    <option value="2" {{ request('status') == 2 ? 'selected' : '' }} >Ended</option>
                    <option value="3" {{ request('status') == 3 ? 'selected' : '' }} >Canceled</option>
                    <option value="4" {{ request('status') == 4 ? 'selected' : '' }} >Missed</option>
                    <option value="5" {{ request('status') == 5 ? 'selected' : '' }} >Finished</option>
                </select>
            </div>
            <div class="flex flex-col">
                <x-button class="bg-emerald-600 text-white w-1/2 mt-8 h-10 ms-5 ps-14 hover:bg-emerald-800">Search</x-button>
            </div>
        </div>
    </form>
    <div class="mt-3 ps-2">
        <input type="hidden" value="{{ getAuth()->id }}" id="user_id">
        <table class="table-fixed " style="width: 99%">
            <thead class="h-12 bg-slate-300 z-0">
                <tr>
                    <th class="text-left ps-2 w-10 rounded-tl-lg">No</th>
                    <th class="text-left">Room Name</th>
                    <th class="text-left">Date</th>
                    <th class="text-left">Start Time</th>
                    <th class="text-left">End Time</th>
                    <th class="text-left">Meeting Title</th>
                    <th class="text-left">Meeting By</th>
                    <th class="text-left">Reason</th>
                    <th class="text-left">Remark</th>
                    <th class="text-left">Status</th>
                    <th class="text-left">Extent Status</th>
                    <th class="text-right pr-10 rounded-tr-lg">Action</th>
                </tr>
            </thead>
            <tbody class="font-light admin_userbody">
                @foreach ($data as $item)
                    <tr class="h-14 hover:bg-slate-100">
                        <td class="ps-2">{{ $data->firstItem()+$loop->index}}</td>
                        <td class="">{{ $item->room->room_name }}</td>
                        <td class="">{{ $item->date }}</td>
                        <td class="">{{ $item->start_time }}</td>
                        <td class="">{{ $item->end_time }}</td>
                        <td class="">{{ $item->title }}</td>
                        <td class="">{{ $item->user->name }}</td>
                        <td class="">{{ $item->reason->reason }}</td>
                        <td class="">{{ $item->remark }}</td>
                        <td class="">
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
                        <td class="">
                            @if ($item->extend_status)
                            {{ $item->extended_duration }}
                        @endif
                        </td>
                        <td class="text-right pr-1">
                            @if ($item->status == 1 || $item->status == 0)
                            <a href="{{ route('admin#editbooking',['id'=>$item->id]) }}"><button class="bg-amber-300 hover:bg-amber-400 rounded-lg  px-3 py-1" id="edit" title="edit" data-id="{{ $item->id }}"><i class="material-symbols-outlined text-base mt-1">edit</i></button></a>
                            <a href="javascript:confirmmessage({{ $item->id }})"><button class="bg-rose-300 hover:bg-rose-400 rounded-lg  px-3 py-1" id="cancel" title="cancel" data-id="{{ $item->id }}"><i class="material-symbols-outlined text-base mt-1">cancel</i></button></a>
                            @endif
                            <a href="javascript:{}">
                                <button class="bg-sky-300 hover:bg-sky-400 rounded-lg  px-3 py-1" id="detail" title="detail" data-id="{{ $item->id }}"><i class="material-symbols-outlined text-base mt-1">info</i></button>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if (request('room') || request('status') || request('from_date') || request('to_date') )
            <div class="mt-3">
                <a href="{{ route('admin#booking') }}"><x-button class="bg-sky-800 hover:bg-sky-900 text-white">Go To Default</x-button></a>
            </div>
        @endif
        <div class="flex justify-center mt-3">
            {{ $data->appends(request()->query())->links() }}
        </div>
    </div>
    @push('js')
        <script>
            $(document).ready(function(e){
                $id  = $('#user_id').val();
                localStorage.removeItem('admin_detail');

                $(document).on('click','#detail',function(e){
                    $book_id = $(this).data('id');
                    $url = window.location.href;
                    localStorage.setItem('admin_detail',$url);
                    window.location.href = 'booking/detail/'+$book_id;
                })
            })

            function confirmmessage($id){
                Swal.fire({
                    text  : 'Are You Sure!!',
                    icon  : 'question',
                    showCancelButton:true,
                }).then((result)=>{
                    if(result.isConfirmed){

                                $.ajax({
                                    url  : "{{ route('booking_cancel') }}",
                                    type : 'POST',
                                    data : {_token: '{{ csrf_token() }}','id' :$id} ,
                                    success: function(res){
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text : 'Booking Cancel Success',
                                            confirmButtonText : 'Ok',
                                        }).then((result)=>{
                                            if(result.isConfirmed){
                                                location.reload();
                                            }
                                        })

                                    },
                                    complete:function(){
                                        $this.text('Cancel');
                                        $this.removeClass('pointer-events-none');
                                    }
                                })
                    }
                })
            }
        </script>
    @endpush
@endsection
