@extends('layouts.simple.master')

@section('title', 'Patient information')

@section('css')
 <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/autoFill.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/keyTable.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/fixedHeader.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/rowReorder.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select/bootstrap-select.min.css') }}">

@endsection

@section('main_content')
<section x-data="patientDataComponent({{ $patient->id }})">
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>{{__('Patient information')}}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"> <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">{{__('Patients')}}</li>
                        <li class="breadcrumb-item active">{{__('Patient information')}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    {{-- General scores Modal start --}}
    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog"
        aria-labelledby="general_scores" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="general_scores">{{__('General scores')}}</h4><button
                        class="btn-close py-0" type="button" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body dark-modal">
                    
                    
                </div>
            </div>
        </div>
    </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <div class="user-profile">
                        <div class="row"><!-- user profile first-style start-->
                            <div class="col-12">
                                <div class="card user-bio">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                <div class="ttl-info text-start">
                                                    <h6><i class="fa-solid fa-envelope pe-2"></i>{{__('Email')}}</h6>
                                                    <span>{{ $patient->email }}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                <div class="ttl-info text-start">
                                                    <h6><i class="fa-solid fa-id-card pe-2"></i>{{__('Patient ID')}}</h6>
                                                    <span>{{ $patient->patient_id }}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                <div class="ttl-info text-start">
                                                    <h6><i class="fa-solid fa-chart-bar pe-2"></i>{{__('Status')}}</h6>
                                                    <span>{{ $patient->status ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                <div class="ttl-info text-start">
                                                    <h6><i class="fa-solid fa-user pe-2"></i>{{__('Full Name')}}</h6>
                                                    <span>{{ $patient->first_name . ' ' . $patient->last_name ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            {{-- <div class="col-lg-3 col-md-4 col-sm-6">
                                                <div class="ttl-info text-start pb-0">
                                                    <h6><i class="fa-solid fa-list-check pe-2 pointer" data-bs-toggle="modal" data-bs-target=".bd-example-modal-xl"></i></h6>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- user profile menu start-->
                            <div class="col-12">
                                <div class="row scope-bottom-wrapper user-profile-wrapper">
                                    <div class="col-xxl-3 user-xl-25 col-xl-4 box-col-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <ul class="sidebar-left-icons nav nav-pills" id="add-product-pills-tab"
                                                    role="tablist">
                                                    <li class="nav-item"> <a class="nav-link active"
                                                            id="survey-response-tab" data-bs-toggle="pill"
                                                            href="#survey-response" role="tab" @click="fetchSurveyData()"
                                                            aria-controls="survey-response" aria-selected="false">
                                                            <div class="nav-rounded">
                                                                <div class="product-icons"><i
                                                                        class="fa-solid fa-timeline"></i></div>
                                                            </div>
                                                            <div class="product-tab-content">
                                                                <h6>{{__('Survey')}}</h6>
                                                            </div>
                                                        </a></li>
                                                    <li class="nav-item"> 
                                                        <a class="nav-link" id="general-tab" data-bs-toggle="pill" href="#geeneral-assessment" role="tab"   @click="fetchAssessmentData()">
                                                            <div class="nav-rounded">
                                                                <div class="product-icons"><i
                                                                        class="fa-solid fa-list-check"></i></div>
                                                            </div>
                                                            <div class="product-tab-content">
                                                                <h6>{{__('General')}}</h6>
                                                            </div>
                                                        </a></li>
                                                    <li class="nav-item"><a class="nav-link" id="bell-scale-tab"
                                                            data-bs-toggle="pill" href="#bell-scale" role="tab" @click="fetchBellscaleAssesment()"
                                                            aria-controls="bell-scale" aria-selected="false">
                                                            <div class="nav-rounded">
                                                                <div class="product-icons">
                                                                    <i class="fa-regular fa-bell"></i></div>
                                                            </div>
                                                            <div class="product-tab-content">
                                                                <h6>{{__('Bellscale')}}</h6>
                                                            </div>
                                                        </a></li>
                                                    {{-- <li class="nav-item"><a class="nav-link" id="wearables-tab"
                                                            data-bs-toggle="pill" href="#wearables" role="tab"
                                                            aria-controls="wearables" aria-selected="false">
                                                            <div class="nav-rounded">
                                                                <div class="product-icons"><i
                                                                        class="fa-solid fa-gears"></i></div>
                                                            </div>
                                                            <div class="product-tab-content">
                                                                <h6>{{__('Smart Wearables')}}</h6>
                                                            </div>
                                                        </a></li> --}}
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="wearables-tab"
                                                        data-bs-toggle="pill" href="#wearables" role="tab"
                                                        @click="initCharts()"> <div class="nav-rounded">
                                                                <div class="product-icons"><i class="fa-solid fa-gears"></i></div>
                                                            </div>
                                                            <div class="product-tab-content">
                                                                <h6>{{__('Smart Wearables')}}</h6>
                                                            </div>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-9 user-xl-75 col-xl-8 box-col-8e">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="tab-content" id="add-product-pills-tabContent">
                                                    <div class="container-fluid product-report-wrapper tab-pane fade show active" id="survey-response" role="tabpanel" aria-labelledby="survey-response-tab">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="card">
                                                                    <div class="card-body px-0 pt-0">
                                                                        <div class="top-body">
                                                                            <div class="row common-f-start g-sm-3 g-2">
                                                                                <div class="col-auto"><label class="form-label"></label></div>
                                                                                <div class="col-auto">
                                                                                    <div class="range-dropdown" id="reportrange"><span></span></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="product-report">
                                                                            <div class="recent-table table-responsive custom-scrollbar">

                                                                                <div x-show="isLoadingSurvey" class="text-center py-5">
                                                                                    <div class="spinner-border text-primary" role="status">
                                                                                        <span class="visually-hidden">Loading...</span>
                                                                                    </div>
                                                                                    <p class="mt-2">{{__("Loading surveys")}}...</p >
                                                                                </div>

                                                                                <div x-show="showSurveyList && !isLoadingSurvey" x-transition>
                                                                                    <table class="table" id="survey-response-table">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th><span class="c-o-light f-w-600">S/N</span></th>
                                                                                                <th><span class="c-o-light f-w-600">{{__("Title")}}</span></th>
                                                                                                <th><span class="c-o-light f-w-600">{{__("Created Date")}}</span></th>
                                                                                                <th><span class="c-o-light f-w-600">{{__("Frequency")}}</span></th>
                                                                                                <th><span class="c-o-light f-w-600">{{__("Status")}}</span></th>
                                                                                                <th><span class="c-o-light f-w-600">{{__("Action")}}</span></th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody></tbody>
                                                                                    </table>
                                                                                </div>
                                                                                <div x-show="showSurveyResponses && isLoadingSurveyResponse" class="text-center py-5">
                                                                                    <div class="spinner-border text-primary" role="status">
                                                                                        <span class="visually-hidden">{{__("Loading...")}}</span>
                                                                                    </div>
                                                                                    <p class="mt-2">{{__("Loading responses...")}}</p>
                                                                                </div>

                                                                                <div x-show="showSurveyResponses && !isLoadingSurveyResponse" x-transition>
                                                                                    <table class="table" id="view-survey-response-table">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th><span class="c-o-light f-w-600">S/N</span></th>
                                                                                                <th><span class="c-o-light f-w-600">{{__("Question")}}</span></th>
                                                                                                <th><span class="c-o-light f-w-600">{{__("Answer")}}</span></th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody></tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="container-fluid product-report-wrapper tab-pane fade" id="geeneral-assessment" role="tabpanel"
                                                        aria-labelledby="geeneral-assessment-tab">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="card">
                                                                    <div class="card-body px-0 pt-0">
                                                                        <div class="top-body">
                                                                            <div class="row common-f-start g-sm-3 g-2">
                                                                                <div class="col-auto"><label class="form-label"></label></div>
                                                                                <div class="col-auto">
                                                                                    <div class="range-dropdown" id="reportrange"><span></span></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="product-report">
                                                                            <div class="recent-table table-responsive custom-scrollbar">
                                                                                 <div x-show="isLoadingAssessment" class="text-center py-5">
                                                                                    <div class="spinner-border text-primary" role="status">
                                                                                        <span class="visually-hidden">{{__("Loading...")}}</span>
                                                                                    </div>
                                                                                    <p class="mt-2">{{__("Loading General Report")}}...</p>
                                                                                </div>
                                                                                <div x-show="!isLoadingAssessment">
                                                                                    <table class="table" id="general-assessment-table">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th> <span class="c-o-light f-w-600">S/N</span></th>
                                                                                                <th> <span class="c-o-light f-w-600">{{__("Question")}}</span></th>
                                                                                                {{-- <th> <span class="c-o-light f-w-600">Type</span>
                                                                                                </th> --}}
                                                                                                <th> <span class="c-o-light f-w-600">Label</span></th>
                                                                                                <th> <span class="c-o-light f-w-600">{{__("Answer")}}</span></th>
                                                                                                {{-- <th> <span class="c-o-light f-w-600">Action</span></th> --}}
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="container-fluid product-report-wrapper tab-pane fade" id="bell-scale" role="tabpanel"
                                                        aria-labelledby="bell-scale-tab">
                                                         <div class="row">
                                                            <div class="col-12">
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        <h5>{{__("Bellscale Assesments")}}</h5>
                                                                    </div>
                                                                    <div class="card-body px-0 pt-0">
                                                                        <div class="top-body">
                                                                            <div class="row common-f-start g-sm-3 g-2">
                                                                                <div class="col-auto"><label class="form-label"></label></div>
                                                                                <div class="col-auto">
                                                                                    <div class="range-dropdown" id="reportrange"><span></span></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="product-report">
                                                                            <div class="recent-table table-responsive custom-scrollbar">
                                                                                <div x-show="isLoadingBellscaleAssessment" class="text-center py-5">
                                                                                    <div class="spinner-border text-primary" role="status">
                                                                                        <span class="visually-hidden">Loading...</span>
                                                                                    </div>
                                                                                    <p class="mt-2">{{__("Loading Bellscale Report")}}...</p>
                                                                                </div>
                                                                                <div x-show="!isLoadingBellscaleAssessment">
                                                                                <table class="table" id="bellscale-assessment-table">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th> <span class="c-o-light f-w-600">S/N</span></th>
                                                                                            <th> <span class="c-o-light f-w-600">{{__("Question")}}</span></th>
                                                                                            {{-- <th> <span class="c-o-light f-w-600">Type</span>
                                                                                            </th> --}}
                                                                                            
                                                                                            <th> <span class="c-o-light f-w-600">{{__("Answer")}}</span></th>
                                                                                            {{-- <th> <span class="c-o-light f-w-600">Action</span></th> --}}
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        
                                                                                    </tbody>
                                                                                </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    
                                                    {{-- <div class="tab-pane fade" id="bell-scale" role="tabpanel"
                                                        aria-labelledby="bell-scale-tab">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <h5>Bellscale Assesments</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                    <div class="tab-pane fade" id="wearables" role="tabpanel" aria-labelledby="wearables-tab">
                                                        <div class="card">
                                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                                <h5>Smart Wearables</h5>
                                                                <div x-show="isLoading" class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                                            </div>
                                                            
                                                            <div class="card-body">
                                                                <div class="container my-4">
                                                                    
                                                                    <div x-show="isLoading" class="text-center py-5">
                                                                        <div class="loader-box">
                                                                            <div class="loader-3"></div> </div>
                                                                        <p class="mt-3 text-muted">Fetching latest wearable data...</p>
                                                                    </div>

                                                                    <div x-show="!isLoading" x-transition>
                                                                        <span style="color: crimson; font-size: 12px;">Note: these are dummy data used for testing</span>
                                                                        <div class="row g-3 mt-2">
                                                                            <div class="col-md-4">
                                                                                <div class="card shadow-none border">
                                                                                    <div class="card-body">
                                                                                        <h6 class="card-title">Heart Rate</h6>
                                                                                        <div id="hrChart" style="min-height:150px;"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <div class="card shadow-none border">
                                                                                    <div class="card-body">
                                                                                        <h6 class="card-title">SpO₂</h6>
                                                                                        <div id="spo2Chart" style="min-height:150px;"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <div class="card shadow-none border">
                                                                                    <div class="card-body">
                                                                                        <h6 class="card-title">Sleep Stages</h6>
                                                                                        <div id="sleepChart" style="min-height:150px;"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <div class="card shadow-none border">
                                                                                    <div class="card-body">
                                                                                        <h6 class="card-title">HRV (RMSSD)</h6>
                                                                                        <div id="hrvChart" style="min-height:150px;"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <div class="card shadow-none border">
                                                                                    <div class="card-body">
                                                                                        <h6 class="card-title">Temperature</h6>
                                                                                        <div id="tempChart" style="min-height:150px;"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <div class="card shadow-none border">
                                                                                    <div class="card-body">
                                                                                        <h6 class="card-title">Steps</h6>
                                                                                        <div id="stepsChart" style="min-height:150px;"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- user profile menu end-->
                        </div>
                    </div>
                </div><!-- Container-fluid Ends-->
                

                </section>
@endsection
@section('scripts')
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>

<script>
    

    function patientDataComponent(patientId) {
        return {
            patientId: patientId,
            isLoading: false,
            isLoadingAssessment: false,
            isLoadingBellscaleAssessment: false,
            isLoadingSurvey: false,
            isLoadingSurveyResponse: false,
            charts: {},
            surveyData: [],
            assessmentData: [],
            bellscaleAssessmentData: [],
            tableInstance: null,

            showSurveyList: true,
            showSurveyResponses: false,

            selectedSurveyId: null,

         init() {
            const surveyId = this.getSurveyIdFromUrl();

            if (surveyId) {
                this.selectedSurveyId = surveyId;
                this.showSurveyList = false;
                this.showSurveyResponses = true;

                this.fetchSurveyResponseData(surveyId);
            } else {
                this.fetchSurveyData();
            }
        },

            async fetchAssessmentData() {
                this.isLoadingAssessment = true;
                try {
                    const res = await fetch(`/get-general-assessment/${this.patientId}`);
                    const json = await res.json();

                    if (json.success) {
                        this.assessmentData = json.data;
                        this.$nextTick(() => {
                            setTimeout(() => {
                                this.renderDataTable();
                                window.dispatchEvent(new Event('resize'));
                            }, 400); // Small buffer for DOM appearance
                        });
                    }
                } catch (error) {
                    console.error("Error fetching assessment:", error);
                } finally {
                    this.isLoadingAssessment  = false;
                }
            },

            async fetchBellscaleAssesment(){
                this.isLoadingBellscaleAssessment = true;
                try {
                    const res = await fetch(`/bellscale-assesment/${this.patientId}`);
                    const json = await res.json();

                    if (json.success) {
                        this.bellscaleAssessmentData = json.data;

                        this.$nextTick(() => {
                            setTimeout(() => {
                                this.renderBellscaleDataTable();
                                window.dispatchEvent(new Event('resize'));
                            }, 10);
                        });
                    }
                } catch (error) {
                    console.error("Error fetching assessment:", error);
                } finally {
                    this.isLoadingBellscaleAssessment = false;
                }
            },

            async fetchSurveyData() {
                this.isLoadingSurvey = true;
                try {
                    const res = await fetch(`/get-patient-surveys/${this.patientId}`);
                    const json = await res.json();

                    if (json.success) {
                        this.surveyData = json.data;
                      
                        this.$nextTick(() => {
                            setTimeout(() => {
                                this.renderSurveyDataTable();
                                window.dispatchEvent(new Event('resize'));
                            }, 300);
                        });
                    }
                } catch (error) {
                    console.error("Error fetching assessment:", error);
                } finally {
                    this.isLoadingSurvey  = false;
                }
            },

            async fetchSurveyResponseData(surveyId) {
                this.isLoadingSurveyResponse = true;
                try {
                    const res = await fetch(`/get-survey-assessment/${surveyId}/${this.patientId}`);
                    console.log(res);
                    const json = await res.json();

                    if (json.success) {
                        this.responseData = json.data;

                        this.$nextTick(() => {
                            this.renderSurveyResponseDataTable();
                        });
                    }
                } catch (e) {
                    console.error(e);
                }
                finally{
                    this.isLoadingSurveyResponse = false;
                }
            },

            renderDataTable() {
                // Destroy existing instance if it exists
                if ($.fn.DataTable.isDataTable('#general-assessment-table')) {
                    $('#general-assessment-table').DataTable().destroy();
                }

                this.tableInstance = $("#general-assessment-table").DataTable({
                     processing: true,
                      order: [[0, 'asc']],
                    //serverSide: false,
                    data: this.assessmentData, // Injecting Alpine data here
                    columns: [
                       {
                            data: null, // S/N column
                            width: '5%',
                            render: (d, t, r, meta) => meta.row + 1
                        },
                        
                          {
                            data: "question.question",
                            render: function (data, type, row) {
                                return data === null || data === undefined || data === ""
                                    ? "Deleted question"
                                    : data;
                            }
                        },
                        // {
                        //     data: "question.type",
                        //     render: function (data, type, row) {
                        //         return data === null || data === undefined || data === ""
                        //             ? "Deleted question"
                        //             : data;
                        //     }
                        // },
                        {
                            data: 'question.label',
                            width: '15%',
                            className: 'drag-cursor',
                            render: labelObj => {
                                // Check if the object and its properties exist
                                if (labelObj && labelObj.label) {
                                    return `<span class="badge" style="background-color: ${labelObj.color || '#ccc'}">
                                                ${labelObj.label}
                                            </span>`;
                                }
                                return '<span class="text-muted">-</span>';
                            }
                        },
                        { 
                            data: null,
                            render: (data) => {

                                let answer = null;

                                // 1. Direct text answer
                                if (data.answer) {
                                    answer = data.answer;
                                }

                                // 2. Selected option
                                else if (data.option && data.option.option_text) {
                                    answer = data.option.option_text;
                                }

                                // 3. Multiple answers
                                else if (data.answers && Array.isArray(data.answers) && data.answers.length > 0) {
                                    answer = data.answers.join(', ');
                                }

                                // 4. Numeric value
                                else if (data.numeric_answer_value !== null && data.numeric_answer_value !== undefined) {
                                    answer = data.numeric_answer_value;
                                }

                                if (!answer) {
                                    return '<span class="text-muted">No Answer</span>';
                                }

                                // ✅ Check if label is "duration"
                                if (
                                    data.question &&
                                    data.question.type &&
                                    data.question.type.toLowerCase() === 'duration'
                                ) {
                                    return `${answer} minuten`;
                                }

                                return answer;
                            }
                        },
                        // {
                        //     data: null,
                        //     orderable: false,
                        //     render: (data) => `
                        //         <button class="btn btn-sm btn-soft-info"><i class="fa fa-eye"></i></button>
                        //         <button class="btn btn-sm btn-soft-danger"><i class="fa fa-trash"></i></button>
                        //     `
                        // }
                    ],
                    //order: [[1, "asc"]],
                    pageLength: 10,
                    autoWidth: false,
                    layout: {
                        topStart: {
                            buttons: [
                                { extend: "copy", text:"{{__('Copy')}}", className: "btn btn-outline-primary btn-sm", attr: { title: "Copy to clipboard", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "csv", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as CSV", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "excel", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as Excel", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "pdf", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as PDF", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "print", text:"{{__('Print')}}", className: "btn btn-outline-primary btn-sm", attr: { title: "Print", class: "btn btn-outline-primary btn-sm" } },
                                // {
                                //     text: '<i class="fa fa-plus"></i> Add Survey',
                                //     className: 'btn btn-primary btn-sm ms-2',
                                //     action: () => { window.location.href = '/surveys/create'; }
                                // }
                            ],
                        },
                        topEnd: {
                            search: { placeholder: "{{__('Search here')}}..." }
                        }
                    }
                });
            },

             renderBellscaleDataTable() {
                // Destroy existing instance if it exists
                if ($.fn.DataTable.isDataTable('#bellscale-assessment-table')) {
                    $('#bellscale-assessment-table').DataTable().destroy();
                }

                this.tableInstance = $("#bellscale-assessment-table").DataTable({
                     processing: true,
                     order: [[0, 'asc']],
                    //serverSide: false,
                    data: this.bellscaleAssessmentData,
                    columns: [
                       {
                            data: null,
                            width: '5%',
                            render: function(data, type, row, meta) {
                                return meta.row + 1 + meta.settings._iDisplayStart;
                            }
                        },
                        
                          {
                            data: "question.question",
                            width: '50',
                            render: function (data, type, row) {
                                return data === null || data === undefined || data === ""
                                    ? "Deleted question"
                                    : data;
                            }
                        },
                        { 
                            data: null,
                            render: (data) => {
                                // 1. Check for a direct text answer
                                if (data.answer) return data.answer;

                                // 2. Check for a selected option (from the 'option' relationship)
                                if (data.option && data.option.option_text) {
                                    return data.option.option_text;
                                }

                                // 3. Check for multiple answers (array)
                                if (data.answers && Array.isArray(data.answers) && data.answers.length > 0) {
                                    return data.answers.join(', ');
                                }

                                // 4. Check for numeric values (if applicable)
                                if (data.numeric_answer_value !== null && data.numeric_answer_value !== undefined) {
                                    return data.numeric_answer_value;
                                }

                                // 5. Fallback
                                return '<span class="text-muted">No Answer</span>';
                            }
                        }
                    ],
                    pageLength: 10,
                    autoWidth: false,
                    layout: {
                        topStart: {
                            buttons: [
                                { extend: "copy", text: "{{__('Copy')}}", className: "btn btn-outline-primary btn-sm", attr: { title: "Copy to clipboard", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "csv", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as CSV", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "excel", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as Excel", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "pdf", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as PDF", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "print", text: "{{__('Print')}}", className: "btn btn-outline-primary btn-sm", attr: { title: "Print", class: "btn btn-outline-primary btn-sm" } },
                                // {
                                //     text: '<i class="fa fa-plus"></i> Add Survey',
                                //     className: 'btn btn-primary btn-sm ms-2',
                                //     action: () => { window.location.href = '/surveys/create'; }
                                // }
                            ],
                        },
                        topEnd: {
                            search: { placeholder: "{{__('Search here')}}..." }
                        }
                    }
                });
            },

             renderSurveyDataTable() {
                // Destroy existing instance if it exists
                if ($.fn.DataTable.isDataTable('#survey-response-table')) {
                    $('#survey-response-table').DataTable().destroy();
                }

                this.tableInstance = $("#survey-response-table").DataTable({
                    processing: true,
                    //serverSide: false,
                    data: this.surveyData,
                    columns: [
                        { data: null, render: (_, __, ___, meta) => meta.row + 1 },
                        {
                            data: 'title',
                            width: '25%',
                            render: function(data, type, row) {
                                let html = `<div class="fw-bold">${data}</div>`;
                                if (row.description) {
                                    const desc = row.description.length > 50 
                                        ? row.description.substring(0, 50) + '...' 
                                        : row.description;
                                    html += `<small class="text-muted d-block mt-1">${desc}</small>`;
                                }
                                return html;
                            }
                        },
                      {
                            data: "created_at",
                            render: function (data) {
                                if (!data) {
                                    return '<span class="text-muted">Deleted question</span>';
                                }

                                const date = new Date(data);

                                return date.toLocaleDateString('en-GB', {
                                    day: '2-digit',
                                    month: 'short',
                                    year: 'numeric'
                                });
                            }
                        },

                        {
                            data: "frequency",
                            render: function (data) {
                                if (!data || data.length === 0) return "-";
                                return data ?? "-";
                            }
                        },
                       {
                            data: "users",
                            render: function (users) {
                                if (!users || users.length === 0) {
                                    return '<span class="text-muted">-</span>';
                                }

                                return users[0]?.pivot?.status ?? '<span class="text-muted">-</span>';
                            }
                        },

                         {
                            data: 'id',
                            orderable: false,
                            render: (id) => {
                                return `
                                    <button 
                                        class="btn btn-outline-primary btn-sm view-survey"
                                        data-id="${id}">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                `;
                            }
                        }

                    ],

                    order: [[0, "asc"]],
                    pageLength: 10,
                    autoWidth: false,
                    layout: {
                        topStart: {
                            buttons: [
                                { extend: "copy", text: "{{__('Copy')}}", className: "btn btn-outline-primary btn-sm", attr: { title: "Copy to clipboard", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "csv", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as CSV", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "excel", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as Excel", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "pdf", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as PDF", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "print", text: "{{__('Print')}}", className: "btn btn-outline-primary btn-sm", attr: { title: "Print", class: "btn btn-outline-primary btn-sm" } },
                                // {
                                //     text: '<i class="fa fa-plus"></i> Add Survey',
                                //     className: 'btn btn-primary btn-sm ms-2',
                                //     action: () => { window.location.href = '/surveys/create'; }
                                // }
                            ],
                        },
                        topEnd: {
                            search: { placeholder: "{{__('Search here')}}..." }
                        }
                    }
                });

                $('#survey-response-table tbody').off('click', '.view-survey');
                $('#survey-response-table tbody').on('click', '.view-survey', (e) => {
                    const surveyId = e.currentTarget.dataset.id;
                    this.viewSurveyResponses(surveyId);
                });
            },

            async viewSurveyResponses(surveyId) {
                this.selectedSurveyId = surveyId;

                this.showSurveyList = false;
                this.showSurveyResponses = true;

                await this.fetchSurveyResponseData(surveyId);
            },


            
            renderSurveyResponseDataTable() {
                const alpine = this;
                // Destroy existing instance if it exists
                if ($.fn.DataTable.isDataTable('#view-survey-response-table')) {
                    $('#view-survey-response-table').DataTable().destroy();
                }

                this.tableInstance = $("#view-survey-response-table").DataTable({
                    processing: true,
                    data: this.responseData,
                    columnDefs: [
                        { targets: '_all', className: 'text-start' }
                    ],
                    columns: [
                        { data: null, width: '5%', render: (_, __, ___, meta) => meta.row + 1 },
                       // { data: 'survey.title' },
                        { data: 'question.question', width: '40%', defaultContent: 'Deleted question' },
                        // {
                        //     data: 'survey.users',
                        //     render: users => users?.[0]?.pivot?.status ?? '-'
                        // },
                        //{ data: 'question.type', defaultContent: '-' },
                        {
                                data: null, width: '50%',
                                    render: d => {
                                    if (d.answer) return d.answer;
                                    if (d.option && d.option.option_text) {
                                        return d.option.option_text;
                                    if (d.numeric_answer_value) {
                                        return d.numeric_answer_value;
                                    }

                                    return 'No Answer';
                                }
                            }
                        }
                    ],

                    order: [[0, "asc"]],
                    pageLength: 10,
                    autoWidth: false,
                    layout: {
                        topStart: {
                            buttons: [
                                {
                                    text: '<i class="fa fa-arrow-left me-1"></i> {{__("Back to Survey")}}',
                                    className: 'btn btn-primary btn-sm me-2',
                                    action: function () {
                                        alpine.backToSurveyList(); // ✅ ALWAYS works
                                    }
                                },
                                { extend: "copy", text: "{{__('Copy')}}", className: "btn btn-outline-primary btn-sm", attr: { title: "{{__('Copy to clipboard')}}", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "csv", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as CSV", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "excel", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as Excel", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "pdf", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as PDF", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "print", text: "{{__('Print')}}", className: "btn btn-outline-primary btn-sm", attr: { title: "{{__('Print')}}", class: "btn btn-outline-primary btn-sm" } },
                                // {
                                //     text: '<i class="fa fa-plus"></i> Add Survey',
                                //     className: 'btn btn-primary btn-sm ms-2',
                                //     action: () => { window.location.href = '/surveys/create'; }
                                // }
                            ],
                        },
                        topEnd: {
                            search: { placeholder: "Search here..." }
                        }
                    }
                });
            },



            backToSurveyList() {
                this.showSurveyResponses = false;
                this.showSurveyList = true;

                // Destroy response table
                if ($.fn.DataTable.isDataTable('#view-survey-response-table')) {
                    $('#view-survey-response-table').DataTable().destroy();
                }

                this.$nextTick(() => {
                    setTimeout(() => {
                        if ($.fn.DataTable.isDataTable('#survey-response-table')) {
                            $('#survey-response-table').DataTable().destroy();
                        }
                        this.renderSurveyDataTable();
                        window.dispatchEvent(new Event('resize'));
                    }, 100);
                });
            },

            getSurveyIdFromUrl() {
                const params = new URLSearchParams(window.location.search);
                return params.get('survey_id');
            },


            async initCharts() {
                // Prevent double-clicking
                if (this.isLoading) return;
                
                this.isLoading = true;

                try {
                    const res = await fetch(`/wearable-data/${this.patientId}`);
                    const json = await res.json();
                    
                    if (json.success && json.data) {
                        // We wait for Alpine to show the container (x-show="!isLoading") 
                        // before we try to render ApexCharts, otherwise they have 0 width.
                        this.isLoading = false; 
                        
                        this.$nextTick(() => {
                            setTimeout(() => {
                                this.renderAllCharts(json.data);
                                window.dispatchEvent(new Event('resize'));
                            }, 100); // Small buffer for DOM appearance
                        });
                    }
                } catch (error) {
                    console.error("Smart Wearables Error:", error);
                    this.isLoading = false;
                }
            },

            renderAllCharts(data) {
                // Heart Rate
                this.renderApex('hrChart', {
                    chart: { type: "line", height: 150, sparkline: { enabled: true }},
                    series: [{ name: "HR", data: data.heart_rate.value }],
                    xaxis: { categories: data.heart_rate.time },
                    stroke: { curve: 'smooth', width: 2 },
                    fill: {
                        type: "gradient",
                        gradient: { shade: "dark", type: "horizontal", gradientToColors: ["#00E396"], stops: [0, 100] }
                    },
                    colors: ["#ff4d4d"]
                });

                // SpO2
                this.renderApex('spo2Chart', {
                    chart: { type: "line", height: 150, sparkline: { enabled: true }},
                    series: [{ name: "SpO2", data: data.spo2.value }],
                    xaxis: { categories: data.spo2.time },
                    stroke: { curve: 'smooth', width: 2 },
                    colors: ["#008ffb"]
                });

                // Sleep Stage
                this.renderApex('sleepChart', {
                    chart: { type: "area", height: 150, sparkline: { enabled: true }},
                    series: [{ name: "Stage", data: data.sleep.value }],
                    xaxis: { categories: data.sleep.time },
                    stroke: { curve: 'smooth', width: 2 },
                    colors: ["#775dd0"]
                });

                // HRV
                this.renderApex('hrvChart', {
                    chart: { type: "line", height: 150, sparkline: { enabled: false }},
                    series: [{ name: "HRV", data: data.hrv.value }],
                    xaxis: { categories: data.hrv.day },
                    stroke: { curve: 'smooth', width: 2 },
                    colors: ["#00e396"]
                });

                // Temperature
                this.renderApex('tempChart', {
                    chart: { type: "bar", height: 150, sparkline: { enabled: false }},
                    series: [{ name: "Temp", data: data.temperature.value }],
                    xaxis: { categories: data.temperature.day },
                    colors: ["#feb019"]
                });

                // Steps
                this.renderApex('stepsChart', {
                    chart: { type: "line", height: 150, sparkline: { enabled: true }},
                    series: [{ name: "Steps", data: data.steps.value }],
                    xaxis: { categories: data.steps.time },
                    stroke: { curve: 'smooth', width: 2 },
                    colors: ["#3f51b5"]
                });
            },

            renderApex(id, options) {
                const el = document.getElementById(id); // Use getElementById for speed
                if (!el) {
                    console.warn(`Element #${id} not found in DOM`);
                    return;
                }

                el.innerHTML = ''; // Clear previous content
                
                if (this.charts[id]) {
                    try { this.charts[id].destroy(); } catch(e) {}
                }

                this.charts[id] = new ApexCharts(el, options);
                this.charts[id].render();
            }
        };
    }




</script>

<script src="{{ asset('assets/js/counter/custom-counter1.js') }}"></script>
<script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
{{-- <script src="{{ asset('assets/js/common-avatar-change.js') }}"></script> --}}


    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables.select.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/select.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.autoFill.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/autoFill.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.keyTable.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/keyTable.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.buttons.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/buttons.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.fixedHeader.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/fixedHeader.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/responsive.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.rowReorder.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/rowReorder.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/custom.js') }}"></script>
    <script src="{{ asset('assets/js/flat-pickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/js/flat-pickr/custom-flatpickr.js') }}"></script>
    <script src="{{ asset('assets/js/flat-pickr/moment.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/flat-pickr/custom-range-btn.js') }}"></script> --}}
    <script src="{{ asset('assets/js/modalpage/validation-modal.js') }}"></script>
    <script src="{{ asset('assets/js/select/bootstrap-select.min.js') }}"></script>
@endsection