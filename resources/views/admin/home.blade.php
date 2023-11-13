@extends('new_layouts.admin.layout')
@section('content')
<style>
    .select2-selection__choice {
        padding: 5px 10px;
        line-height: 1.5;
    }

    .select2-selection{
        overflow: auto;
    }
</style>
    <div class="ps-5">
        <div class="p-4">
            <span class="text-2xl font-serif">Dashboard</span>
        </div>
        <div class="mx-auto shadow-lg px-5 pb-2 bg-slate-100 shadow-slate-400" style="position: relative; height:45vh; width:95%">
            <canvas id="myChart" style="width: 100%"></canvas>
        </div>
        <div id="this_year" data-item="{{ json_encode($this_year_data) }}"></div>
        <div id="last_year" data-item="{{ json_encode($last_year_data) }}"></div>
    </div>
    <div class="ps-5 my-10 grid grid-cols-3 gap-5">
        <div class=" ms-10 shadow-lg p-5 bg-slate-100 shadow-slate-400" style="position: relative; height:45vh; width:100%">
            <canvas id="myChart1" style="width: 100%"></canvas>
            <input type="hidden" id="user" data-item="{{ json_encode($user) }}">
            <input type="hidden"  id="data_user" data-item="{{ json_encode($final_data) }}">
            <input type="hidden"  id="color" data-item="{{ json_encode($color) }}">
        </div>
        <div class="col-span-2 ms-10">
            <div class="">
                <form action="{{ getAuth()->employee_id == '000-000000' ? route('home') : route('admin#dashboard') }}" method="GET">
                    <div class="p-5 grid grid-cols-5 gap-8">
                        <div class="flex flex-col">
                            <label for="from_date">From Date :</label>
                            <input type="date" id="from_date" value="{{ request('from_date') }}" name="from_date" class="mt-2 border-1 text-slate-700 border-slate-400 rounded-lg focus:ring-0 focus:border-b-4  focus:border-slate-400 placeholder-slate-300">
                        </div>
                        <div class="flex flex-col">
                            <label for="to_date">To Date :</label>
                            <input type="date" id="to_date" value="{{ request('to_date') }}" name="to_date" class="mt-2 border-1 text-slate-700 border-slate-400 rounded-lg focus:ring-0 focus:border-b-4  focus:border-slate-400 placeholder-slate-300">
                        </div>
                        <div class="flex flex-col">
                            <label for="status">Month :</label>
                            <input type="month" id="month" value="{{ request('month') }}" name="month" class="mt-2 border-1 text-slate-700 border-slate-400 rounded-lg focus:ring-0 focus:border-b-4  focus:border-slate-400 placeholder-slate-300">
                        </div>
                        <div class="flex flex-col">
                            <label for="status" class="mb-1">Status :</label>
                            <select id="status" name="status[]" style="" multiple="multiple">
                                <option value="">Choose Status</option>
                                <option value="6" {{ request('status') ? (in_array(6,request('status')) ? 'selected' : '') : '' }} >Pending</option>
                                <option value="1" {{ request('status') ? (in_array(1,request('status')) ? 'selected' : '') : '' }}>Started</option>
                                <option value="2" {{ request('status') ? (in_array(2,request('status')) ? 'selected' : '') : '' }} >Ended</option>
                                <option value="3" {{ request('status') ? (in_array(3,request('status')) ? 'selected' : '') : '' }} >Cancelled</option>
                                <option value="4" {{ request('status') ? (in_array(4,request('status')) ? 'selected' : '') : '' }} >Missed</option>
                                <option value="5" {{ request('status') ? (in_array(5,request('status')) ? 'selected' : '') : '' }} >Finished</option>
                            </select>
                        </div>
                        <div class="">
                            <x-button class="bg-emerald-600 text-white mt-8 h-10 ps-6 hover:bg-emerald-800">Search</x-button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="grid grid-cols-4 gap-4 px-10">
                <div class="w-full h-24 bg-slate-200 shadow-lg text-center pt-1 text-xl font-serif flex flex-col">
                    <span class="underline">All</span>
                    <span class="mt-3">{{ count($all_data) }}</span>
                </div>
                @foreach ($user_data as $item)
                    <div class="w-full h-24 bg-slate-200 shadow-lg text-center pt-1 text-xl font-serif flex flex-col">
                        <span class="underline">{{ $item->user->name }}</span>
                        <span class="mt-3">{{ $item->count }}</span>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
    <script>
        $(document).ready(function(e){
            $('#status').select2();
        })
        const this_year = JSON.parse(document.getElementById('this_year').dataset.item);
        const last_year = JSON.parse(document.getElementById('last_year').dataset.item);

        const user = JSON.parse(document.getElementById('user').dataset.item);
        const data_user = JSON.parse(document.getElementById('data_user').dataset.item);
        const color = JSON.parse(document.getElementById('color').dataset.item);
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July','August','September','October','November','December'],
                    datasets: [{
                        fill: {
                        target: 'origin',
                        below:  'rgba(255, 159, 64, 0.2)'
                    },
                        label: 'This Year',
                        data: this_year,
                        backgroundColor: [
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    },{
                        fill: {
                        target: 'origin',
                        below:  'rgba(255, 99, 132, 0.2)'
                    },
                        label: 'Last Year',
                        data:last_year,
                        backgroundColor: [

                            'rgba(255, 99, 132, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
            options: {
                maintainAspectRatio : false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Comparism Between This Year And Last Year'
                    }
                },
            }
        });

        var ctx1 = document.getElementById('myChart1').getContext('2d');
        var myChart = new Chart(ctx1, {
            type: 'doughnut',
                data: {
                    labels: user,
                    datasets: [{
                        data:data_user,
                        backgroundColor: color,
                        borderColor: [
                            '#ffff'
                        ],
                        borderWidth: 3,
                        hoverOffset: 10,
                    }]
                },
            options: {
                maintainAspectRatio : false,

            }
        });
      </script>
@endsection
