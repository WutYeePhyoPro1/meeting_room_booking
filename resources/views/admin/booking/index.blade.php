@extends('new_layouts.admin.layout')
@section('content')

    <div class="my-4 mx-2 flex justify-between">
        <span class="text-xl">Booking History</span>
    </div>
    <div class="mt-3">
        <input type="hidden" value="{{ getAuth()->id }}" id="user_id">
        <table class="table-fixed " style="width: 99%">
            <thead class="h-12 bg-slate-300 z-0">
                <tr>
                    <th class="text-left ps-2 rounded-tl-lg">No</th>
                    <th class="text-left">Room Name</th>
                    <th class="text-left">Start Time</th>
                    <th class="text-left">End Time</th>
                    <th class="text-left">Meeting Title</th>
                    <th class="text-left">Meeting By</th>
                    <th class="text-left">Reason</th>
                    <th class="text-left">Remark</th>
                    <th class="text-left">Status</th>
                    <th class="text-left rounded-tr-lg">Extent Status</th>
                </tr>
            </thead>
            <tbody class="font-light admin_userbody">
                {{-- @foreach ($data as $item)
                    <tr class="h-10 hover:bg-slate-100">
                        <td class="ps-2">{{ $data->firstItem()+$loop->index}}</td>
                        <td class="">{{ $item->name }}</td>
                        <td class="">{{ $item->employee_id }}</td>
                        <td class="">{{ $item->departments->name }}</td>
                        <td>
                            @if ($item->employee_id != '000-000000')
                             <button class="bg-amber-300 hover:bg-amber-400 rounded-lg  px-3 py-1" id="edit" title="edit" data-id="{{ $item->id }}"><i class="material-symbols-outlined text-base">edit</i></button>
                             <button class="bg-rose-400 hover:bg-rose-500  rounded-lg  px-3 py-1" onclick="confirmmessage({{ $item->id }})" data-id="{{ $item->id }}"><i class="material-symbols-outlined text-base translate-y-0.5">delete</i></button>
                            @endif
                        </td>
                    </tr>
                @endforeach --}}
            </tbody>
        </table>
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
                        window.location.href = 'user/delete/'+$id;
                    }
                })
            }
        </script>
    @endpush
@endsection
