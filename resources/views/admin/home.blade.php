@extends('new_layouts.admin.layout')
@section('content')
    <div class="p-4">
        Dashboard
    </div>
    <div class="" style="width: 500px">
        <canvas id="myChart"></canvas>
    </div>
    <script>
  var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July','August'],
        datasets: [{
            label: 'Last Month',
            data: [15,25, 15, 7, 3, 2, 3, 9],
            backgroundColor: [

                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)'
            ],
            borderColor: [
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        },{
            label: 'This Month',
            data: [15,5, 15, 23, 27, 28, 27, 9],
            backgroundColor: [

                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)'
            ],
            borderColor: [
                'rgba(255, 159, 64, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        }
        ,{
            label: 'now',
            data: [null, null, null, null, null, null ,20, null],
            backgroundColor: [

                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)'
            ],
            borderColor: [
                'rgba(255, 159, 64, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
      </script>
@endsection
