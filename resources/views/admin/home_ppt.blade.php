<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Meeting Room Booking System</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script> -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="ps-5">
        <div class="mx-auto shadow-lg px-5 pb-2 bg-slate-100 shadow-slate-400 mt-4" style="position: relative; height:45vh; width:95%">
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
                        text: 'Comparison Between This Year And Last Year'
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
</body>
</html>
