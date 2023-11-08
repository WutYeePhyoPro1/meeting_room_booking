@extends('new_layouts.admin.layout')
@section('content')

    <div class="my-4 mx-2 flex justify-between">
    <span class="text-xl">All Booking</span>
    </div>
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
                            <a href="{{ route('admin#detailbooking',['id'=>$item->id]) }}">
                                <button class="bg-sky-300 hover:bg-sky-400 rounded-lg  px-3 py-1" id="detail" title="detail" data-id="{{ $item->id }}"><i class="material-symbols-outlined text-base mt-1">info</i></button>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="flex justify-center mt-3">
            {{ $data->links() }}
        </div>
    </div>
    @push('js')
        <script>
            $(document).ready(function(e){
                $id  = $('#user_id').val();
                localStorage.removeItem($id+'_user');

                $(document).on('click','#add_user',function(e){
                    $url = window.location.href;
                    localStorage.setItem($id+'_user',$url);
                    window.location.href = "{{ route('create_user') }}";
                })

                $(document).on('click','#edit',function(e){
                    $url = window.location.href;
                    localStorage.setItem($id+'_user',$url);
                    $id1 = $(this).data('id');
                    window.location.href = "user/edit/"+$id1;
                })
            })

            function confirmmessage($id){
                Swal.fire({
                    text  : 'Are You Sure!!',
                    icon  : 'question',
                    showCancelButton:true,
                }).then((result)=>{
                    if(result.isConfirmed){
                        $.ajaxSetup({
                                    headers : { 'X-CSRF_TOKEN' : $("meta[name='__token']").attr('content') }
                                })

                                $.ajax({
                                    url  : "{{ route('booking_cancel') }}",
                                    type : 'POST',
                                    data : {'id' :$id} ,
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
