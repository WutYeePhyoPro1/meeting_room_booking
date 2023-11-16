@extends('new_layouts.admin.layout')
@section('content')
    @if (session('success'))
    <div class="my-4 bg-emerald-100 h-10 font-medium text-lg ps-5 pt-1 rounded-lg text-green-600" style="width:99%">
        {{ session('success') }}
    </div>
    @endif
<div class="mt-4 mx-2 flex justify-between">
    <span class="text-xl">User List</span>
    <button class="px-3 py-1 bg-emerald-500 text-white rounded-lg text-lg mr-5 hover:bg-emerald-600" id="add_room" title="add">+</button>
</div>
<div class="mt-3">
    <input type="hidden" value="{{ getAuth()->id }}" id="user_id">
    <table class="table-fixed " style="width: 99%">
        <thead class="h-12 bg-slate-300 z-0">
            <tr>
                <th class="text-left ps-3 rounded-tl-lg">No</th>
                <th class="text-left">Name</th>
                <th class="text-left">Branch</th>
                <th class="text-left">Avaliable Seat</th>
                <th class="text-left ">Action</th>
                <th class="text-left">Boss</th>
                <th class="text-left rounded-tr-lg">Guest</th>
            </tr>
        </thead>
        <tbody class="font-light admin_userbody">
            @foreach ($data as $item)
                <tr class="h-10 hover:bg-slate-100">
                    <td class="ps-4">{{ $data->firstItem()+$loop->index }}</td>
                    <td>{{ $item->room_name }}</td>
                    <td>{{ $item->branches->branch_name }}</td>
                    <td>{{ $item->seat }} seats</td>
                    <td>
                        <button class="bg-amber-300 hover:bg-amber-400 rounded-lg  px-3 py-1" id="edit" title="edit" data-id="{{ $item->id }}"><i class="material-symbols-outlined text-base">edit</i></button>
                        <button class="bg-rose-400 hover:bg-rose-500 rounded-lg  px-3 py-1" onclick="confirmmessage({{ $item->id }})" data-id="{{ $item->id }}"><i class="material-symbols-outlined text-base translate-y-0.5">delete</i></button>
                    </td>
                    <td>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="{{ $item->id }}" class="sr-only peer boss_in" {{ $item->boss == 1 ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                          </label>
                    </td>
                    <td>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="{{ $item->id }}" class="sr-only peer guest_in" {{ $item->guest == 1 ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                          </label>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@push('js')
    <script>
        $(document).ready(function(e){
            $user_id = $('#user_id').val();
            localStorage.removeItem($user_id+'_room');
            $(document).on("click",'#add_room',function(e){
                $url = window.location.href;
                localStorage.setItem($user_id+'_room',$url);
                window.location.href = "{{ route('create_room') }}";
            })

            $(document).on("click",'#edit',function(e){
                $id = $(this).data('id');
                $url = window.location.href;
                localStorage.setItem($user_id+'_room',$url);
                window.location.href = "room/edit/"+$id;
            })

            $(document).on('click','.boss_in',function(){
                $val = $(this).val();

                $.ajax({
                    url : 'room/boss/'+$val,
                    type: 'get',
                    success: function(res){

                    }
                })
            })

            $(document).on('click','.guest_in',function(){
                $val = $(this).val();

                $.ajax({
                    url : 'room/guest/'+$val,
                    type: 'get',
                    success: function(res){

                    }
                })
            })
        })

        function confirmmessage($id){
                Swal.fire({
                    text  : 'Are You Sure!!',
                    icon  : 'question',
                    showCancelButton:true,
                }).then((result)=>{
                    if(result.isConfirmed){
                        window.location.href = 'room/delete/'+$id;
                    }
                })
            }
    </script>
@endpush
@endsection
