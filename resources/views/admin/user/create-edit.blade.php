@extends('new_layouts.admin.layout')
@section('content')
    @if (session('fails'))
    <div class="my-4 bg-rose-200 h-10 font-medium text-lg ps-5 pt-1 rounded-lg text-red-600" style="width:99%">
        {{ session('fails') }}
    </div>
    @endif
    <div class="p-5">
        <input type="hidden" id="user_id" value="{{ getAuth()->id }}">
        <form action="{{ route('store_user') }}" autocomplete="off" method="post">
            @csrf
            <fieldset class="w-[97%] mt-3 border border-slate-500 rounded-md p-5">
                <legend class="px-2">{{ isset($data) ? 'Update' : 'Create' }} User</legend>

                <div class="flex flex-col mb-5">
                    <label for="name">Name :</label>
                    <input type="text" name="name" class="mt-2 border-1 text-slate-700 border-slate-300 rounded-lg focus:ring-0 focus:border-b-4  focus:border-slate-200 placeholder-slate-200" value="{{ old('name',(isset($data) ? $data->name : '')) }}" id="name" placeholder="name...">
                    @error('name')
                        <small class="text-rose-500 ms-2">{{ $message }}</small>
                    @enderror
                </div>
                <div class="flex flex-col mb-5">
                    <label for="employee_id">Employee Id :</label>
                    <input type="text" name="employee_id" class="mt-2 border-1 text-slate-700 border-slate-300 rounded-lg focus:ring-0 focus:border-b-4  focus:border-slate-200 placeholder-slate-200" value="{{ old('employee_id',(isset($data) ? $data->employee_id : '')) }}" id="employee_id" placeholder="employee id...">
                    @error('employee_id')
                    <small class="text-rose-500 ms-2">{{ $message }}</small>
                @enderror
                </div>
                <div class="flex flex-col mb-5">
                    <label for="password">Password :</label>
                    <input type="password" name="password" class="mt-2 border-1 text-slate-700 border-slate-300 rounded-lg focus:ring-0 focus:border-b-4  focus:border-slate-200 placeholder-slate-200" value="{{ old('password',(isset($data) ? $data->password_str : '')) }}" id="password" placeholder="password...">
                    @error('password')
                    <small class="text-rose-500 ms-2">{{ $message }}</small>
                @enderror
                </div>
                <input type="hidden" name="id" value="{{ isset($data) ? $data->id : '' }}">
                <div class="flex flex-col mb-5">
                    <label for="department_id">Department :</label>
                    <select name="department_id" class="mt-2 border-1 border-slate-300 text-slate-700 rounded-t-lg" id="department_id">
                        <option value="" selected>Choose Department</option>
                        @foreach ($department as $item)
                            <option value="{{ $item->id }}" {{ old('department_id') ? (old('department_id') == $item->id ? 'selected' : '') : (!isset($data) ?  '' : (($data->department_id == $item->id) ? 'selected':'')) }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <small class="text-rose-500 ms-2">{{ $message }}</small>
                    @enderror
                </div>
                <div class="grid grid-cols-6 gap-4">
                    <div class="flex flex-col mb-5">
                        <label for="bg_color">Background Color :</label>
                        <input type="color" name="bg_color" class="mt-3" id="bg_color" value="{{ old('bg_color',(isset($data)? $data->bg_color : '#0000ff')) }}">
                        @error('bg_color')
                            <small class="text-rose-500 ms-2">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="flex flex-col mb-5">
                        <label>Text Color :</label>
                        <div class="mt-3">
                            white : <input type="radio" name="text_color" class="me-7" value="white" {{ old('text_color') == 'white' ? 'checked' : (isset($data) ? ($data->text_color == 'white' ? 'checked' : '') : 'checked') }}>
                            black : <input type="radio" name="text_color" class="" value="black" {{ old('text_color') == 'black' ? 'checked' : (isset($data) ? ($data->text_color == 'black' ? 'checked' : '') : '') }}>
                        </div>
                        @error('text_color')
                            <small class="text-rose-500 ms-2">{{ $message }}</small>
                        @enderror
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
                    $url = localStorage.getItem($id+'_user');
                    if($url){
                      window.location.href = $url;
                    }else{
                        window.history.back();
                    }
                })
            })
        </script>
    @endpush
@endsection
