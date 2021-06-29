@extends('layouts.app', ['menu' => 'dashboard'])

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables-1.10.20/jquery.dataTables.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6">
            <a href="{{ route('projects.index') }}" class="text-decoration-none">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-2">
                                <div class="icon-big text-center icon-warning">
                                    <i class="fa fa-sitemap text-primary"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-10">
                                <div class="numbers">
                                    <p class="card-category">Total Project</p>
                                    <p class="card-title">{{ $totalProject }}
                                    </p><p>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <hr>
                        <div class="stats">
                            Total project created
                        </div>
                    </div>
                </div>
            </a>
        </div>
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
    </div>
@endsection

@section('script')
    <!-- Chart JS -->
    <script src="{{ asset('assets/plugins/chart/chartjs.min.js') }}"></script>
@endsection
