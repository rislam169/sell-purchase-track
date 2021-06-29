@extends('layouts.app',  ['menu' => 'report'])

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables-1.10.20/jquery.dataTables.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6">
            <a href="{{ route('budget.index', 'show') }}" class="text-decoration-none">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-2">
                                <div class="icon-big text-center icon-warning">
                                    <i class="fa fa-credit-card-alt text-warning"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-10">
                                <div class="numbers">
                                    <p class="card-category">Total Budget</p>
                                    <p class="card-title">{{ number_format($totalBudgetAmount, 2) }}
                                    </p><p>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <hr>
                        <div class="stats">
                            Total budget amount
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6">
            <a href="{{ route('expense.index', 'show') }}" class="text-decoration-none">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-2">
                                <div class="icon-big text-center icon-warning">
                                    <i class="fa fa-credit-card text-success"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-10">
                                <div class="numbers">
                                    <p class="card-category">Total Expense</p>
                                    <p class="card-title">{{ number_format($totalExpenseAmount, 2) }}
                                    </p><p>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <hr>
                        <div class="stats">
                            Total expense amount
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6">
            <a href="{{ route('expense.index', 'show') }}" class="text-decoration-none">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-2">
                                <div class="icon-big text-center icon-warning">
                                    <i class="fa fa-money text-success"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-10">
                                <div class="numbers">
                                    <p class="card-category">Total Profit</p>
                                    <p class="card-title">{{ number_format($totalBudgetAmount - $totalExpenseAmount, 2) }}
                                    </p><p>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <hr>
                        <div class="stats">
                            Total profit amount
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card ">
                <div class="card-header ">
                    <h5 class="card-title">Monthly Statistics</h5>
                    <p class="card-category">Running Month Performance</p>
                </div>
                <div class="card-body ">
                    <canvas id="chartEmail"></canvas>
                </div>
                <div class="card-footer ">
                    <div class="legend">
                        <i class="fa fa-circle text-warning"></i> Budget
                        <i class="fa fa-circle text-success"></i> Expense
                    </div>
                    <hr>
                    <div class="stats">
                        <i class="fa fa-pie-chart"></i> Chart shows the performance
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12">
            <div class="card card-chart">
                <div class="card-header">
                    <h5 class="card-title">Yearly Statistics</h5>
                    <p class="card-category">This Year Performance</p>
                </div>
                <div class="card-body">
                    <canvas id="speedChart" width="400" height="100"></canvas>
                </div>
                <div class="card-footer">
                    <div class="chart-legend">
                        <i class="fa fa-circle text-success"></i> Expense
                        <i class="fa fa-circle text-warning"></i> Budget
                    </div>
                    <hr />
                    <div class="card-stats">
                        <i class="fa fa-line-chart"></i> Chart shows this year performance
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Chart JS -->
    <script src="{{ asset('assets/plugins/chart/chartjs.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var chart = {
                initChartsPages: function () {
                    chartColor = "#FFFFFF";

                    ctx = document.getElementById('chartEmail').getContext("2d");

                    myChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: ['Expense', 'Budget'],
                            datasets: [{
                                label: "Emails",
                                pointRadius: 0,
                                pointHoverRadius: 0,
                                backgroundColor: [
                                    '#6bd098',
                                    '#fcc468',
                                ],
                                borderWidth: 0,
                                data: [{{ $totalExpenseAmount }}, {{ $totalBudgetAmount }}]
                            }]
                        },

                        options: {

                            legend: {
                                display: true
                            },

                            pieceLabel: {
                                render: 'percentage',
                                fontColor: ['white'],
                                precision: 2
                            },

                            tooltips: {
                                enabled: true
                            },

                            scales: {
                                yAxes: [{

                                    ticks: {
                                        display: false
                                    },
                                    gridLines: {
                                        drawBorder: false,
                                        zeroLineColor: "transparent",
                                        color: 'rgba(255,255,255,0.05)'
                                    }

                                }],

                                xAxes: [{
                                    barPercentage: 1.6,
                                    gridLines: {
                                        drawBorder: false,
                                        color: 'rgba(255,255,255,0.1)',
                                        zeroLineColor: "transparent"
                                    },
                                    ticks: {
                                        display: false,
                                    }
                                }]
                            },
                        }
                    });

                    var speedCanvas = document.getElementById("speedChart");

                    var dataFirst = {
                        data: Object.values(JSON.parse('{!! $totalExpenseStats !!}')),
                        fill: false,
                        label: "Expense",
                        borderColor: '#6bd098',
                        backgroundColor: 'transparent',
                        pointBorderColor: '#6bd098',
                        pointRadius: 4,
                        pointHoverRadius: 4,
                        pointBorderWidth: 8,
                    };

                    var dataSecond = {
                        data: Object.values(JSON.parse('{!! $totalBudgetStats !!}')),
                        fill: false,
                        label: "Budget",
                        borderColor: '#fbc658',
                        backgroundColor: 'transparent',
                        pointBorderColor: '#fbc658',
                        pointRadius: 4,
                        pointHoverRadius: 4,
                        pointBorderWidth: 8
                    };

                    var speedData = {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                        datasets: [dataFirst, dataSecond]
                    };

                    var chartOptions = {

                        legend: {
                            display: true,
                            position: 'top',
                        }
                    };

                    var lineChart = new Chart(speedCanvas, {
                        type: 'line',
                        hover: false,
                        data: speedData,
                        options: chartOptions
                    });
                }
            }
            // Javascript method's body can be found in assets/assets-for-demo/js/demo.js
            chart.initChartsPages();
        });
    </script>
@endsection
