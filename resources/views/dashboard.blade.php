@extends('layout.app')
@section('judul', 'Halaman Dashboard')
@section('dashboard','active')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="mb-sm-4 d-flex flex-wrap align-items-center text-head">
            <h2 class="mb-3 me-auto">Dashboard</h2>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a>{{ config('app.name') }}</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">@yield('judul')</a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-xxl-4">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header border-0">
                                <div class="col-xl-12">
                                    <div class="card-header border-0">
										<div>
											<h4 class="fs-20 mb-1">Status</h4>
											<span>value & status tanaman Penanaman Otomatis</span>
										</div>
									</div>
                                </div>
                            </div>
                            <div class="card-body pb-0">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div class="menu">
                                                <span class="font-w500 fs-16 d-block mb-2">Value</span>
                                                <h2 id="valueText">Checking...</h2>
                                            </div>
                                            <div class="d-inline-block position-relative donut-chart-sale">
                                                <span class="donut1" data-peity='{ "fill": ["rgb(98, 79, 209,1)", "rgba(247, 245, 255)"],   "innerRadius": 35, "radius": 10}'>1/</span>
                                                <small class="text-black">
                                                    <img src="icons/value.png" width="30" fill="none">
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div class="menu">
                                                <span class="font-w500 fs-16 d-block mb-2">Status Keputusan Fuzzy</span>
                                                <h2 id="statusText">Checking...</h2>
                                            </div>
                                            <div class="d-inline-block position-relative donut-chart-sale">
                                                <span class="donut1" id="donutChart" data-peity='{ "fill": ["orange", "rgba(247, 245, 255)"], "innerRadius": 35, "radius": 10}'>1/1</span>
                                                <small class="text-black">
                                                    <img src="icons/status.png" width="30" fill="none">
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-xxl-8">
                <div class="row">
                    <div class="col-xl-12">
                            <div class="card">
                                <div class="card-header border-0 flex-wrap pb-0">
                                    <div class="mb-sm-0 mb-2">
                                        <h4 class="fs-20">Data Monitoring</h4>
                                        <span>Aplikasi Penyiraman Otomatis NodeMCU secara Realtime</span>
                                    </div>
                                </div>
                                <canvas id="temperatureChart" class="p-5" style="width: 40%"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var ctx = document.getElementById('temperatureChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Temperature (°C)',
                data: [],
                fill: false,
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.3
            }, {
                label: 'Humidity (%)',
                data: [],
                fill: false,
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                tension: 0.3
            },{
                label: 'Pressure (hPa)',
                data: [],
                fill: false,
                borderColor: 'rgb(255, 205, 86)',
                backgroundColor: 'rgba(255, 205, 86, 0.2)',
                tension: 0.3
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: false
                    }
                }]
            }
        }
    });

    function updateChart() {
        $.ajax({
            url: 'api',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log(data);
                chart.data.labels = [];
                chart.data.datasets[0].data = [];
                chart.data.datasets[1].data = [];
                chart.data.datasets[2].data = [];
                var status  = data[3].status;
                var value   = data[3].value;

                data.forEach(function(row, index) {
                    chart.data.labels.push(row.timestamp);
                    chart.data.datasets[0].data.push(row.temperature);
                    chart.data.datasets[1].data.push(row.humidity);
                    chart.data.datasets[2].data.push(row.pressure);
                    if (index === data.length - 1) {
                        var formattedStatus = row.status.charAt(0).toUpperCase() + row.status.slice(1).toLowerCase();
                        var formattedValue = row.value.charAt(0).toUpperCase() + row.value.slice(1).toLowerCase();
                        $('#statusText').text(formattedStatus);
                        $('#valueText').text(formattedValue);
                    }
                });

                chart.update();
            },
            error: function(xhr, status, error) {
                console.error("Error: " + xhr.responseText);
            }
        });
    }
    setInterval(updateChart, 4000);
</script>


@endsection
