@extends('new_layouts.admin.layout')
@section('content')
    @if (session('success'))
        <div class="my-4 bg-emerald-100 h-10 font-medium text-lg ps-5 pt-1 rounded-lg text-green-600" style="width:99%">
            {{ session('success') }}
        </div>
    @endif
    <div class="mt-4 mx-2 flex justify-between">
        <span class="text-xl">User List</span>
        <button class="px-3 py-1 bg-emerald-500 text-white rounded-lg text-lg mr-5 hover:bg-emerald-600" id="add_user" title="add">+</button>
    </div>
    <div class="mt-3">
        <input type="hidden" value="{{ getAuth()->id }}" id="user_id">
        <table class="table-fixed " style="width: 99%">
            <thead class="h-12 bg-slate-300 z-0">
                <tr>
                    <th class="text-left ps-2 rounded-tl-lg">No</th>
                    <th class="text-left">Name</th>
                    <th class="text-left">Employee ID</th>
                    <th class="text-left">Department</th>
                    <th class="text-left rounded-tr-lg">Action</th>
                </tr>
            </thead>
            <tbody class="font-light admin_userbody">
                @foreach ($data as $item)
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
                @endforeach
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
