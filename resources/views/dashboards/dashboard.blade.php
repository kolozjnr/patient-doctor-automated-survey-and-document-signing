@extends('layouts.simple.master')

@section('title', 'ECC Dashboard')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/vector-map1/jsvectormap.min.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Dashboard</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"> <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">ECC</li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div><!-- Container-fluid starts-->
    <div class="container-fluid dashboard-5">
        <div class="row">
            <div class="col-12 od-xl-1">
                <div class="row">
                    <div class="col s-xxl-3 box-col-4">
                        <div class="card social-widget widget-hover">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="social-icons">
                                            <i class="fa-solid fa-bed"></i>
                                            {{-- <img src="{{ asset('assets/images/dashboard-5/social/1.png') }}" alt="facebook icon"> --}}
                                            </div><span>{{__('Patients')}}</span>
                                    </div><span class="font-success f-12 d-xxl-block">+22.9%</span>
                                </div>
                                <div class="social-content">
                                    <div>
                                        <h5 class="mb-1 counter" data-target="{{ $totalPatients }}">{{ $totalPatients ?? 0 }}</h5>
                                        {{-- <span class="f-light">Likes</span> --}}
                                    </div>
                                    {{-- <div class="social-chart">
                                        <div id="radial-facebook"></div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col s-xxl-3 box-col-4">
                        <div class="card social-widget widget-hover">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="social-icons">
                                            {{-- <img src="{{ asset('assets/images/dashboard-5/social/2.png') }}" alt="instagram icon"> --}}
                                            <i class="fa-solid fa-question"></i>
                                            </div><span>{{__('Questions')}}</span>
                                    </div>
                                    {{-- <span class="font-danger f-12 d-xxl-block">-27.4%</span> --}}
                                </div>
                                <div class="social-content">
                                    <div>
                                        <h5 class="mb-1 counter" data-target="{{ $totalQuestions }}">{{ $totalQuestions ?? 0 }}</h5>
                                        {{-- <span class="f-light">Followers</span> --}}
                                    </div>
                                    {{-- <div class="social-chart">
                                        <div id="radial-instagram"></div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col s-xxl-3 box-col-4">
                        <div class="card social-widget widget-hover">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="social-icons">
                                            {{-- <img src="{{ asset('assets/images/dashboard-5/social/3.png') }}" alt="twitter icon"> --}}
                                            <i class="fa-solid fa-clipboard-list"></i>
                                            </div>
                                                <span>{{__('Survey')}}</span>
                                    </div><span class="font-success f-12 d-xxl-block">+76.10%</span>
                                </div>
                                <div class="social-content">
                                    <div>
                                        <h5 class="mb-1 counter" data-target="{{ $totalSurveys }}">{{ $totalSurveys ?? 0 }}</h5>
                                        
                                    </div>
                                    {{-- <div class="social-chart">
                                        <div id="radial-twitter"></div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col s-xxl-3 box-col-4">
                        <div class="card social-widget widget-hover">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="social-icons">
                                            {{-- <img src="{{ asset('assets/images/dashboard-5/social/4.png') }}" alt="twitter icon"> --}}
                                            <i class="fa-solid fa-users"></i>
                                            </div><span>{{__('Employees')}}</span>
                                    </div><span class="font-success f-12 d-xxl-block">+62.08%</span>
                                </div>
                                <div class="social-content">
                                    <div>
                                        <h5 class="mb-1 counter" data-target="{{ $totalEmployees }}">{{ $totalEmployees ?? 0 }}</h5>
                                        {{-- <span class="f-light">Followers</span> --}}
                                    </div>
                                    {{-- <div class="social-chart">
                                        <div id="radial-youtube"></div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-12 col-xl-11 od-xl-7 box-col-7">
                <div class="card">
                    <div class="card-header card-no-border">
                        <div class="header-top">
                            <h5 class="m-0"></h5>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="subscriber-chart-container">
                            <div id="dashboard-chart"> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Container-fluid Ends-->
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script src="{{ asset('assets/js/counter/counter-custom.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables.select.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/select.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/vector-map1/jsvectormap.js') }}"></script>
    <script src="{{ asset('assets/js/vector-map1/world.js') }}"></script>
    <script src="{{ asset('assets/js/vector-map1/custom-vectormap.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard/dashboard_5.js') }}"></script>
@endsection
