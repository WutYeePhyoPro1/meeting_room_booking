@extends('new_layouts.admin.layout')
@section('content')
    @if (session('fails'))
    <div class="my-4 bg-rose-200 h-10 font-medium text-lg ps-5 pt-1 rounded-lg text-red-600" style="width:99%">
        {{ session('fails') }}
    </div>
    @endif
    <div class="p-5">
        <input type="hidden" id="user_id" value="{{ getAuth()->id }}">
        <form action="{{ route('store_room') }}" enctype="multipart/form-data" method="post">
            @csrf
            <fieldset class="w-[97%] mt-3 border border-slate-500 rounded-md p-5">
                <legend class="px-2">{{ isset($data) ? 'Update' : 'Create' }} Room</legend>

                <div class="flex flex-col mb-5">
                    <label for="name">Name :</label>
                    <input type="text" name="name" class="mt-2 border-1 text-slate-700 border-slate-300 rounded-lg focus:ring-0 focus:border-b-4  focus:border-slate-200 placeholder-slate-200" value="{{ old('name', (isset($data) ? $data->room_name : '')) }}" id="name" placeholder="name...">
                    @error('name')
                        <small class="text-rose-500 ms-2">{{ $message }}</small>
                    @enderror
                </div>
                <input type="hidden" name="id" value="{{ isset($data) ? $data->id : '' }}">
                <div class="flex flex-col mb-5">
                    <label for="branch_id">branch :</label>
                    <select name="branch_id" class="mt-2 border-1 border-slate-300 text-slate-700 rounded-t-lg" id="branch_id">
                        <option value="" selected>Choose branch</option>
                        @foreach ($branches as $item)
                            <option value="{{ $item->id }}" {{ old('branch_id') ? (old('branch_id') == $item->id ? 'selected' : '') : (!isset($data) ?  '' : (($data->branch_id == $item->id) ? 'selected':'')) }}>{{ $item->branch_name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id')
                        <small class="text-rose-500 ms-2">{{ $message }}</small>
                    @enderror
                </div>
                <div class="flex flex-col mb-5">
                    <label for="seat">Avaliable Seat :</label>
                    <input type="number" name="seat" id="seat" class="mt-2 border-1 text-slate-700 border-slate-300 rounded-lg focus:ring-0 focus:border-b-4 focus:border-slate-200 placeholder-slate-200" value="{{ old('seat', (isset($data) ? $data->seat : '')) }}" placeholder="Avaliable Seed...">
                    @error('seat')
                        <small class="text-rose-500 ms-2">{{ $message }}</small>
                    @enderror
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div class="pt-4 flex flex-col">
                        <span class="text-lg underline cursor-pointer tracking-wider hover:text-cus" onclick="$('#room_img').click()">Click To Uploads Room Image (optional)</span>
                        @error('room_image')
                            <small class="text-rose-500 ms-2">{{ $message }}</small>
                        @enderror
                        <input type="file" hidden onchange="readImg(this)"  name="room_image" id="room_img">
                        <div class="">
                            <img id="img_reader"/>
                        </div>
                    </div>
                    <div class="pt-4 flex flex-col">
                        @if (isset($img))
                            <span class="text-lg underline tracking-wider mb-5" >Old Image :</span>
                            <img src="{{ asset("storage/uploads/room_image/".$img->file_name) }}" class="border border-slate-900" style="width:400px"/>
                        @endif
                    </div>
                </div>
                <div class="">
                    <x-button class="bg-cus outline mt-5 outline-1 px-5 py-1 rounded-lg text-white outline-sky-500 hover:outline-offset-1 focus:outline-offset-1 float-right">{{ isset($data) ? 'Update' : 'Save' }}</x-button>
                    <x-button type="button" class="bg-cus1 outline mt-5 outline-1 px-5 py-1 rounded-lg text-white outline-slate-500 hover:outline-offset-1 focus:outline-offset-1 float-right mr-2" id="back">Back</x-button>

                </div>
            </fieldset>
        </form>
    </div>
    @push('js')
        <script>
            $(document).ready(function(e){
                $(document).on('click','#back',function(e){
                    $id = $('#user_id').val();
                    $url = localStorage.getItem($id+'_room');
                    if($url){
                      window.location.href = $url;
                    }else{
                        window.history.back();
                    }
                })
            })
            function readImg(e) {
                if (e.files && e.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#img_reader').attr('src', '');
                        $('#img_reader').attr('src', e.target.result);
                        $('#img_reader').css({
                            'width'  : '400px',
                            'border' : 'solid 1px black',
                            'margin-top' : '20px'
                        })
                    }
                    reader.readAsDataURL(e.files[0]);
                }
            }

        </script>
    @endpush
@endsection
