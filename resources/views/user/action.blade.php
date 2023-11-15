@extends('new_layouts.user.layout')
@section('content')

<div class="mt-10 px-5">
    <input type="hidden" value="{{ getAuth()->id }}" id="user_id">
    <table class="table-fixed " style="width: 99%">
        <thead class="h-12 bg-slate-300 z-0">
            <tr>
                <th class="text-left ps-3 rounded-tl-lg">No</th>
                <th class="text-left">Name</th>
                <th class="text-left">Status</th>
                <th class="text-left">Avaliable Seat</th>
                <th class="text-left">Boss</th>
                <th class="text-left rounded-tr-lg">Guest</th>
            </tr>
        </thead>
        <tbody class="font-light admin_userbody">
            @foreach ($data as $item)
                <tr class="h-10 hover:bg-slate-100">
                    <td class="ps-4">{{ $data->firstItem()+$loop->index }}</td>
                    <td>{{ $item->room_name }}</td>
                    <td class="status">{{ $item->boss == 1 ? 'Boss In' : ($item->guest == 1 ? 'Guest In' : ($item->status == 1 ? 'Occupied' : 'Available')) }}</td>
                    <td>{{ $item->seat }} seats</td>
                    <td>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="{{ $item->id }}" class="sr-only peer boss_in" class="sr-only peer boss_in " {{ $item->boss == 1 ? 'checked' : '' }} {{ $item->status == 1 || $item->guest == 1 ? 'disabled' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                          </label>
                    </td>
                    <td>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="{{ $item->id }}" class="sr-only peer guest_in"class="sr-only peer guest_in " {{ $item->guest == 1 ? 'checked' : '' }} {{ $item->status == 1 || $item->boss == 1 ? 'disabled' : '' }}>
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

            $(document).on('click','.boss_in',function(){
                $val = $(this).val();
                $this = $(this);
                $.ajax({
                    url : '/admin/room/boss/'+$val,
                    type: 'get',
                    success: function(res){
                        if(res.status == 1){
                            $this.parent().parent().parent().find('.status').text('Boss In');
                            $this.parent().parent().parent().find('.guest_in').attr('disabled',true);

                        }else if(res.status == 0){
                            $this.parent().parent().parent().find('.status').text('Avaliable');
                            $this.parent().parent().parent().find('.guest_in').attr('disabled',false);
                        }
                    }
                })
            })

            $(document).on('click','.guest_in',function(){
                $val = $(this).val();

                $.ajax({
                    url : 'admin/room/guest/'+$val,
                    type: 'get',
                    success: function(res){
                        if(res.status == 1){
                            $this.parent().parent().parent().find('.status').text('Guest In');
                            $this.parent().parent().parent().find('.boss_in').attr('disabled',true);

                        }else if(res.status == 0){
                            $this.parent().parent().parent().find('.status').text('Avaliable');
                            $this.parent().parent().parent().find('.boss_in').attr('disabled',false);
                        }
                        
                    }
                })
            })
        })
    </script>
@endpush
@endsection
