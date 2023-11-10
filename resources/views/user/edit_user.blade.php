@extends('new_layouts.user.layout')
@section('content')
<input type="hidden" id="check_status" value="{{ Session::has('success') ? 1 : 0 }}">
    <div class="p-5 w-[50%] mx-auto mt-5">
        <form action="{{ route('user_change_password') }}" method="POST" class="">
            @csrf
            <fieldset class="mt-5 border border-slate-500 rounded-md p-5">
                <legend class="px-4">Change Password</legend>

                    <div class="flex flex-col my-5 relative">
                        <label for="old_pass">Old Password :</label>
                        <input type="password" name="old_pass" class="mt-2 border-1 text-slate-700 border-slate-300 rounded-lg focus:ring-0 focus:border-b-4  focus:border-slate-200 placeholder-slate-200" value="{{ old('name',(isset($data) ? $data->name : '')) }}" id="old_pass"  placeholder="Old Password...">
                        <i class="material-symbols-outlined absolute text-slate-300 right-3 top-8  text-4xl cursor-pointer" onclick="change_password('#old_pass','#ic1')" id="ic1">visibility</i>
                        @error('old_pass')
                            <small class="text-rose-600 ms-1">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="flex flex-col my-5 relative">
                        <label for="new_pass">New Password :</label>
                        <input type="password" name="new_pass" class="mt-2 border-1 text-slate-700 border-slate-300 rounded-lg focus:ring-0 focus:border-b-4  focus:border-slate-200 placeholder-slate-200" value="{{ old('name',(isset($data) ? $data->name : '')) }}" id="new_pass" placeholder="New Password...">
                        <i class="material-symbols-outlined absolute text-slate-300 right-3 top-8  text-4xl cursor-pointer" id='ic2' onclick="change_password('#new_pass','#ic2')">visibility</i>
                        @error('new_pass')
                            <small class="text-rose-600 ms-1">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="flex flex-col my-5 relative">
                        <label for="con_pass">Confirm Password :</label>
                        <input type="password" name="con_pass" class="mt-2 border-1 text-slate-700 border-slate-300 rounded-lg focus:ring-0 focus:border-b-4  focus:border-slate-200 placeholder-slate-200" value="{{ old('name',(isset($data) ? $data->name : '')) }}" id="con_pass" placeholder="Confirm Password...">
                        <i class="material-symbols-outlined absolute text-slate-300 right-3 top-8  text-4xl cursor-pointer" id="ic3" onclick="change_password('#con_pass','#ic3')">visibility</i>
                        @error('con_pass')
                            <small class="text-rose-600 ms-1">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="">
                        <x-button type="submit" class="bg-cus outline outline-1 px-5 py-1 rounded-lg text-white outline-sky-500 hover:outline-offset-1 focus:outline-offset-1 float-right">Save</x-button>
                    </div>
            </fieldset>
        </form>
        {{-- <div class="grid grid-cols-4 gap-4 mt-5">
            <div class="flex flex-col mb-5">
                <label for="bg_color">Background Color :</label>
                <input type="color" name="bg_color" class="mt-3" id="bg_color" value="{{ getAuth()->bg_color }}">
            </div>
            <div class="flex flex-col mb-5 col-span-2">
                <label>Text Color :</label>
                <div class="mt-3">
                    white : <input type="radio" name="text_color"  class="me-7 text_color" value="white" {{ getAuth()->text_color == 'white' ? 'checked' : '' }}>
                    black : <input type="radio" name="text_color"  class="text_color ms-1" value="black" {{ getAuth()->text_color == 'black' ? 'checked' : '' }}>
                </div>

            </div>
            <div class="-translate-x-40">
                <i class="material-symbols-outlined font-extrathin cursor-pointer" title="color တွေပြောင်းပြီးရင်
                save နှိပ်စရာမလိုပါ">info</i>
            </div>
        </div> --}}
        <div class="grid grid-cols-4 gap-4 mt-5">
            <i class="material-symbols-outlined py-3 cursor-pointer bg-emerald-200 font-thin duration-500 rounded-full lg:w-1/2 md:w-full xl:w-1/2 2xl:w-1/3 pl-3 select-none text-5xl user_icon {{ getAuth()->icon == 'support_agent' ? 'bg-sky-600 pointer-events-none text-white' : '' }}">support_agent</i>
            <i class="material-symbols-outlined py-3 cursor-pointer bg-emerald-200 font-thin duration-500 rounded-full lg:w-1/2 md:w-full xl:w-1/2 2xl:w-1/3 pl-3 select-none text-5xl user_icon {{ getAuth()->icon == 'face' ? 'bg-sky-600 pointer-events-none text-white' : '' }}">face</i>
            <i class="material-symbols-outlined py-3 cursor-pointer bg-emerald-200 font-thin duration-500 rounded-full lg:w-1/2 md:w-full xl:w-1/2 2xl:w-1/3 pl-3 select-none text-5xl user_icon {{ getAuth()->icon == 'child_care' ? 'bg-sky-600 pointer-events-none text-white' : '' }}">child_care</i>
            <i class="material-symbols-outlined py-3 cursor-pointer bg-emerald-200 font-thin duration-500 rounded-full lg:w-1/2 md:w-full xl:w-1/2 2xl:w-1/3 pl-3 select-none text-5xl user_icon {{ getAuth()->icon == 'face_2' ? 'bg-sky-600 pointer-events-none text-white' : '' }}">face_2</i>
            <i class="material-symbols-outlined py-3 cursor-pointer bg-emerald-200 font-thin duration-500 rounded-full lg:w-1/2 md:w-full xl:w-1/2 2xl:w-1/3 pl-3 select-none text-5xl user_icon {{ getAuth()->icon == 'face_3' ? 'bg-sky-600 pointer-events-none text-white' : '' }}">face_3</i>
            <i class="material-symbols-outlined py-3 cursor-pointer bg-emerald-200 font-thin duration-500 rounded-full lg:w-1/2 md:w-full xl:w-1/2 2xl:w-1/3 pl-3 select-none text-5xl user_icon {{ getAuth()->icon == 'face_4' ? 'bg-sky-600 pointer-events-none text-white' : '' }}">face_4</i>
            <i class="material-symbols-outlined py-3 cursor-pointer bg-emerald-200 font-thin duration-500 rounded-full lg:w-1/2 md:w-full xl:w-1/2 2xl:w-1/3 pl-3 select-none text-5xl user_icon {{ getAuth()->icon == 'face_5' ? 'bg-sky-600 pointer-events-none text-white' : '' }}">face_5</i>
            <i class="material-symbols-outlined py-3 cursor-pointer bg-emerald-200 font-thin duration-500 rounded-full lg:w-1/2 md:w-full xl:w-1/2 2xl:w-1/3 pl-3 select-none text-5xl user_icon {{ getAuth()->icon == 'face_6' ? 'bg-sky-600 pointer-events-none text-white' : '' }}">face_6</i>
            <i class="material-symbols-outlined py-3 cursor-pointer bg-emerald-200 font-thin duration-500 rounded-full lg:w-1/2 md:w-full xl:w-1/2 2xl:w-1/3 pl-3 select-none text-5xl user_icon {{ getAuth()->icon == 'person' ? 'bg-sky-600 pointer-events-none text-white' : '' }}">person</i>
            <i class="material-symbols-outlined py-3 cursor-pointer bg-emerald-200 font-thin duration-500 rounded-full lg:w-1/2 md:w-full xl:w-1/2 2xl:w-1/3 pl-3 select-none text-5xl user_icon {{ getAuth()->icon == 'person_2' ? 'bg-sky-600 pointer-events-none text-white' : '' }}">person_2</i>
        </div>
    </div>

    @push('js')
        <script>
            $(document).ready(function(e){

                $status = $('#check_status').val();
                if($status == 1){
                    Swal.fire({
                        icon : 'success',
                        title: 'Create Success'
                    })
                }

                $(document).on('change','#bg_color',function(e){
                    $val = $(this).val();
                    $this = $(this);

                    $.ajax({
                        url     : "{{ route('change_bg_color') }}",
                        type    : 'POST',
                        data    : {_token: '{{ csrf_token() }}','data' : $val},
                        success : function(res){
                            $this.val($val);
                        }
                    })
                })

                $(document).on('change','.text_color',function(e){
                    // console.log('yes');
                    $val = $('.text_color:checked').val();
                    $this = $(this);
                    $.ajax({
                        url     : "{{ route('change_text_color') }}",
                        type    : 'POST',
                        data    : {_token: '{{ csrf_token() }}','data' : $val},
                        success : function(res){
                            $this.val($val);
                        }
                    })
                })

                $(document).on('click','.user_icon',function(){
                    $val = $(this).text();
                    $this = $(this);

                    $.ajax({
                        url : "{{ route('change_icon') }}",
                        type: 'post',
                        data: {_token: '{{ csrf_token() }}','data' : $val},
                        success: function(res){
                            $this.addClass('bg-sky-600 pointer-events-none text-white').siblings().removeClass('bg-sky-600 pointer-events-none text-white');
                            $('#profile_icon').text($val);
                        }
                    })
                })

            })
            function change_password(id1,id2){
                $x = $(id1).attr('type');
                if($x == 'password'){
                    $(id2).text('visibility_off')
                    $(id1).attr('type','text');
                }else{
                    $(id2).text('visibility')
                    $(id1).attr('type','password');
                }
            }
        </script>
    @endpush
@endsection
