@extends('layouts.simple.master')

@section('title', 'Reports')

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
                <section x-data="ReportComponent()">
 <div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>{{__('Report')}}</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"> <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                            </svg></a></li>
                    <li class="breadcrumb-item">{{__('ECC')}}</li>
                    <li class="breadcrumb-item active">{{__('Report')}}</li>
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
            <!-- user profile menu start-->
            <div class="col-12" x-show="surveyReportData">
                <div class="card user-bio">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="ttl-info text-start">
                                    <h6> <i class="fa-solid fa-user-tie pe-2"></i>{{__("Survey Title")}}</h6><span class="mb-sm-3">
                                        <h5 class="mb-0" x-text="surveyReportData?.survey_title"></h5>
                                                            <small class="text-muted" x-text="surveyReportData?.survey_description"></small>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="ttl-info text-start">
                                    <h6><i class="fa fa-users pe-2"></i>{{__("Patients")}}</h6>
                                    <span x-text="surveyReportData?.total_users"></span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="ttl-info text-start">
                                    <h6><i class="fa-solid fa-clock-rotate-right pe-2"></i>{{__("Frequency")}}</h6>
                                    <span x-text="surveyReportData?.frequency"></span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="ttl-info text-start">
                                    <h6><i class="fa-solid fa-chart-bar pe-2"></i>{{__("Status")}}</h6><span class="badge text-start" 
                                                                    :class="surveyReportData?.status === 'active' ? 'text-success' : 'text-secondary'"
                                                                    x-text="surveyReportData?.status"></span>
                                </div>
                            </div>
                            {{-- <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="ttl-info text-start pb-0">
                                    <h6><i class="fa-solid fa-location-arrow pe-2"></i>Location</h6>
                                    <span>B69 Libby Street Beverly Hills</span>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="row scope-bottom-wrapper user-profile-wrapper">
                    <div class="col-xxl-3 user-xl-25 col-xl-4 box-col-4">
                        <div class="card">
                            <div class="card-body">
                                <ul class="sidebar-left-icons nav nav-pills" id="add-product-pills-tab"
                                    role="tablist">
                                    <li class="nav-item"> <a class="nav-link active"
                                            id="survey-response-tab" data-bs-toggle="pill"
                                            href="#survey-response" role="tab" @click="fetchSurvey()"
                                            aria-controls="survey-response" aria-selected="false">
                                            <div class="nav-rounded">
                                                <div class="product-icons"><i
                                                        class="fa-solid fa-timeline"></i></div>
                                            </div>
                                            <div class="product-tab-content">
                                                <h6>{{__('Survey Report')}}</h6>
                                            </div>
                                        </a></li>
                                    <li class="nav-item"> 
                                        <a class="nav-link" id="general-tab" data-bs-toggle="pill" href="#geeneral-assessment" role="tab"   @click="fetchGeneralQuestionReport('general')">
                                            <div class="nav-rounded">
                                                <div class="product-icons"><i
                                                        class="fa-solid fa-list-check"></i></div>
                                            </div>
                                            <div class="product-tab-content">
                                                <h6>{{__('Questions Report')}}</h6>
                                            </div>
                                        </a></li>
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
                                                    {{-- <div class="card-header">
                                                        <h5>Survey Response</h5>
                                                    </div> --}}
                                                    <div class="card-body px-0 pt-0">
                                                       <div class=" top-body">
                                                                <div class="row common-f-start g-sm-3 g-2">
                                                                <div class="col-auto" x-show="!isLoadingAssessment">
                                                                    <select id="filter-item" class="form-select w-auto">
                                                                        <option value="">{{__("All Filters")}}</option>
                                                                    </select>

                                                                    {{-- <div class="range-dropdown" id="reportrange"><span></span></div> --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="product-report">
                                                            <div class="recent-table table-responsive custom-scrollbar">
                                                                <div x-show="showSurveyList" x-transition>
                                                                    <table class="table" id="survey-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>{{__("S/N")}}</th>
                                                                            <th>{{__("Title")}}</th>
                                                                            <th>{{__("Frequency")}}</th>
                                                                            <th>{{_("Patients")}}</th>
                                                                            <th>{{__("Questions")}}</th>
                                                                            {{-- <th>Next Delivery</th> --}}
                                                                            {{-- <th>Status</th> --}}
                                                                            <th>{{__("Actions")}}</th>
                                                                        </tr>
                                                                    </thead>
                                                                    
                                                                    <tbody>
                                                                        
                                                                    </tbody>
                                                                </table>
                                                                </div>
                                                            <div x-show="showPatients" x-transition>
                                                                <div x-show="isLoadingAssessment" class="text-center py-5">
                                                                <div class="spinner-border text-primary" role="status">

                                                                    </div> 
                                                                </div>
                                                                <div x-show="showSurveyPatientsList" x-transition>
                                                                    <table class="table" id="survey-patients-table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>{{__("S/N")}}</th>
                                                                                <th>{{__("Patient ID")}}</th>
                                                                                <th>{{__("Full Name")}}</th>
                                                                                <th>{{__("Email")}}</th>
                                                                                <th>{{__("Survey Status")}}</th>
                                                                                <th>{{__("Actions")}}</th>

                                                                            </tr>
                                                                        </thead>
                                                                        
                                                                        <tbody>
                                                                            
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>

                                                               <div x-show="showReport" x-transition>
                                                                <div x-show="isLoadingAssessment" class="text-center py-5">
                                                                <div class="spinner-border text-primary" role="status">

                                                                    </div> 
                                                                </div>
                                                                    <table x-show="!isLoadingAssessment" class="table table-striped table-hover" id="survey-report-table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>{{__("S/N")}}</th>
                                                                                <th>{{__("Question")}}</th>
                                                                                <th>{{__("Type")}}</th>
                                                                                <th>{{__("Label")}}</th>
                                                                                <th>{{__("Responses")}}</th>
                                                                                <th>{{__(("Results"))}}</th>
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
                                                    {{-- <div class="card-header">
                                                        <h5>General Question Assesment</h5>
                                                    </div> --}}
                                                    <div class="card-body px-0 pt-0">
                                                            <div class=" top-body">
                                                                <div class="row common-f-start g-sm-3 g-2">
                                                                <div class="col-auto" x-show="!isLoadingAssessment">
                                                                    <select id="question-label-filter" class="form-select w-auto">
                                                                        <option value="">All question labels</option>
                                                                    </select>

                                                                    {{-- <div class="range-dropdown" id="reportrange"><span></span></div> --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="product-report">
                                                            <div class="recent-table table-responsive custom-scrollbar">
                                                                <div x-show="!isLoadingAssessment">
                                                                    <table id="general-questions-report-table" class="table table-striped table-hover w-100">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>S/N</th>
                                                                                <th>Question</th>
                                                                                <th>Type</th>
                                                                                <th>Label</th>
                                                                                <th>Responses</th>
                                                                                <th>Results</th>
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
    

    function ReportComponent() {
        return {
            isLoading: false,
            isLoadingAssessment: false,
            charts: {},
            assessmentData: [], 
            surveyData: [],
            surveyPatientsData: [],
           // surveyAnswersData: [],
            tableInstance: null,

            surveyReportData: null,
            showReport: false,
            showPatients: false,
            reportTableInstance: null,

            showSurveyList: true,
            showSurveyResponses: false,
            showSurveyPatientsList: false,

            selectedSurveyId: null,

         init() {

            this.$nextTick(() => {
                this.renderDataTable();
                this.initQuestionLabelFilter();
                this.fetchSurvey();
            });

        },
        destroyTable(tableId) {
            if ($.fn.DataTable.isDataTable(`#${tableId}`)) {
                $(`#${tableId}`).DataTable().destroy();
            }
        },

        async fetchSurvey() {
                this.surveyReportData = null;
                this.isLoadingAssessment = true;
                try {
                    const res = await fetch(`/reports/get-surveys`);
                    const json = await res.json();

                    if (json.success) {
                        this.surveyData = json.data;
                        this.$nextTick(() => {
                            setTimeout(() => {
                                this.renderSurveyDataTable();
                                window.dispatchEvent(new Event('resize'));
                            }, 100);
                        });

                        const FilterSet = new Set();
                        this.surveyData.forEach(q => {
                            if (q.frequency) {
                                FilterSet.add(frequency);
                            }
                        });
                        this.populateFilter([...FilterSet]);
                    }
                } catch (error) {
                    console.error("Error fetching assessment:", error);
                } finally {
                    this.isLoadingAssessment  = false;
                }
            },

            async fetchSurveyUsers(surveyId) {
                this.surveyReportData = null;
                this.isLoadingAssessment = true;
                this.showPatients = true;
                try {
                    const res = await fetch(`reports/get-survey-users/${surveyId}`);
                    console.log(res);
                    const json = await res.json();

                    if (json.success) {
                        
                        this.showSurveyPatientsList = true;
                        this.surveyPatientsData = json.data;

                        this.$nextTick(() => {
                            this.renderSurveyUsers();
                        });
                    }
                } catch (e) {
                    console.error(e);
                }
                finally{
                    this.isLoadingAssessment = false;
                }
            },

            async fetchSurveyAnswers(userId, surveyId) {
                this.isLoadingAssessment = true;
                //this.showReport = true;

                try {
                    const res = await fetch(`/reports/get-survey-answers/${surveyId}/user/${userId}`
                    );

                    const json = await res.json();

                    if (json.success) {
                        this.surveyReportData = json.data;
                        this.showPatients = true;

                        this.$nextTick(() => {
                            setTimeout(() => {
                                this.renderSurveyReportTable();
                                window.dispatchEvent(new Event('resize'));
                            }, 100);
                        });
                    }
                } catch (e) {
                    console.error(e);
                } finally {
                    this.isLoadingAssessment = false;
                }
            },


            async fetchGeneralQuestionReport(module) {
                this.surveyReportData = null;
                this.isLoadingAssessment = true;
                try {
                    const res = await fetch(`/reports/questions-report/${module}`);
                    const json = await res.json();

                    if (json.success) {
                        this.assessmentData = json.data;
                        this.$nextTick(() => {
                            setTimeout(() => {
                                this.renderDataTable();
                                window.dispatchEvent(new Event('resize'));
                            }, 100);
                        });

                        const labelSet = new Set();
                        this.assessmentData.forEach(q => {
                            if (q.label && q.label.label) {
                                labelSet.add(q.label.label);
                            }
                        });
                        this.populateQuestionLabelFilter([...labelSet]);
                    }
                } catch (error) {
                    console.error("Error fetching assessment:", error);
                } finally {
                    this.isLoadingAssessment  = false;
                }
            },

            renderSurveyDataTable() {
                // Destroy existing instance if it exists
                // if ($.fn.DataTable.isDataTable('#survey-table')) {
                //     $('#survey-table').DataTable().destroy();
                // }

                this.destroyTable('survey-table');

                this.tableInstance = $("#survey-table").DataTable({
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
                            data: 'frequency',
                            width: '5%',
                            render: function(data, type, row) {
                                const badges = {
                                    'once': 'secondary',
                                    'daily': 'primary',
                                    'weekly': 'success',
                                    'monthly': 'info',
                                    'custom': 'warning'
                                };

                                // Make sure data is a string
                                const value = data || '';  

                                const badge = badges[value] || 'secondary';
                                const text = value ? value.charAt(0).toUpperCase() + value.slice(1) : '-';
                                
                                return `<span class="">${text}</span>`;
                            }
                        },

                        {
                            data: 'batch_patient_count',
                            width: '5%',
                            orderable: false,
                            render: function(data, type, row) {
                                return `<span class="badge badge-light-dark">${data} patient${data !== 1 ? 's' : ''}</span>`;
                            }
                        },
                        {
                            data: 'questions_count',
                            width: '5%',
                            orderable: false,
                            render: function(data, type, row) {
                                return `${data} question${data !== 1 ? 's' : ''}`;
                            }
                        },
                        // {
                        //     data: 'survey_delivery_date',
                        //     width: '10%',
                        //     render: function(data, type, row) {
                        //         if (!data) return '<span class="text-muted">N/A</span>';
                        //         const date = new Date(data);
                        //         return date.toLocaleDateString('en-US', { 
                        //             year: 'numeric', 
                        //             month: 'short', 
                        //             day: 'numeric' 
                        //         });
                        //     }
                        // },
                        // {
                        //     data: 'cron_status',
                        //     width: '10%',
                        //     render: function(data, type, row) {
                        //         const statusClass = data === 'active' ? 'success' : 'danger';
                        //         const text = data.charAt(0).toUpperCase() + data.slice(1);
                        //         return `<span class="badge badge-${statusClass}">${text}</span>`;
                        //     }
                        // },
                        {
                            data: 'id',
                            width: '15%',
                            orderable: false,
                            render: function(data, type, row) {
                                return `
                                    <div class="btn-group btn-group-sm" role="group">
                                         <button 
                                        class="btn btn-outline-primary btn-sm view-survey"
                                        data-id="${data}">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    </div>
                                `;
                            }
                        }

                    ],

                    order: [[1, "asc"]],
                    pageLength: 10,
                    autoWidth: false,
                    layout: {
                        topStart: {
                            buttons: [
                                { extend: "copy", className: "btn btn-outline-primary btn-sm", attr: { title: "Copy to clipboard", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "csv", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as CSV", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "excel", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as Excel", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "pdf", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as PDF", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "print", className: "btn btn-outline-primary btn-sm", attr: { title: "Print", class: "btn btn-outline-primary btn-sm" } },
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

                $('#survey-table tbody').off('click', '.view-survey');
                $('#survey-table tbody').on('click', '.view-survey', (e) => {
                    const surveyId = e.currentTarget.dataset.id;
                    this.viewSurveyUsers(surveyId);
                });
            },

         async viewSurveyUsers(surveyId) {
             //alert(`View responses for survey ID: ${surveyId}`);
              this.selectedSurveyId = surveyId;

                this.showSurveyList = false;
                this.showSurveyResponses = true;

                await this.fetchSurveyUsers(surveyId);
         },

         renderSurveyUsers() {
                const alpine = this;
                // Destroy existing instance if it exists
                // if ($.fn.DataTable.isDataTable('#survey-patients-table')) {
                //     $('#survey-patients-table').DataTable().destroy();
                // }

                this.destroyTable('survey-patients-table');

                this.tableInstance = $("#survey-patients-table").DataTable({
                    processing: true,
                    data: this.surveyPatientsData,
                    columnDefs: [
                        { targets: '_all', className: 'text-start' }
                    ],
                    columns: [
                        {
                            data: null,
                            width: '5%',
                            render: (_, __, ___, meta) => meta.row + 1
                        },
                        {
                            data: 'patient_id',
                            width: '15%',
                            defaultContent: '-'
                        },
                        {
                            data: null,
                            render: row =>
                                `${row.first_name ?? ''} ${row.last_name ?? ''}`.trim() || '-'
                        },
                        {
                            data: 'email',
                            defaultContent: '-'
                        },
                        {
                        data: 'pivot.status',
                            render: status => {
                            if (!status) return '-';

                            const map = {
                                completed: 'success',
                                pending: 'warning'
                            };

                            return `<span class="badge bg-${map[status] ?? 'secondary'}">
                                ${status}
                            </span>`;
                            }
                        },
                        {
                            data: null,
                            width: '30%',
                            orderable: false,
                            render: row => `
                                <button
                                    class="btn btn-outline-primary btn-sm view-report"
                                    data-user-id="${row.id}"
                                    data-survey-id="${row.pivot.survey_id}">
                                    View Report
                                </button>
                            `
                        }

                    ],


                    order: [[1, "asc"]],
                    pageLength: 10,
                    autoWidth: false,
                    layout: {
                        topStart: {
                            buttons: [
                                {
                                    text: '<i class="fa fa-arrow-left me-1"></i> Back to Survey',
                                    className: 'btn btn-primary btn-sm me-2',
                                    action: function () {
                                        alpine.backToSurveyList(); // ✅ ALWAYS works
                                    }
                                },
                                { extend: "copy", className: "btn btn-outline-primary btn-sm", attr: { title: "Copy to clipboard", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "csv", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as CSV", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "excel", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as Excel", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "pdf", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as PDF", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "print", className: "btn btn-outline-primary btn-sm", attr: { title: "Print", class: "btn btn-outline-primary btn-sm" } },
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

                $('#survey-patients-table tbody').off('click', '.view-report').on('click', '.view-report', (e) => {
                    const btn = e.currentTarget;

                    const userId = btn.dataset.userId;
                    const surveyId = btn.dataset.surveyId;

                    this.viewSurveyReport(userId, surveyId);
                });

            },

             renderSurveyReportTable() {
                if ($.fn.DataTable.isDataTable('#survey-report-table')) {
                    $('#survey-report-table').DataTable().destroy();
                }

                this.reportTableInstance = $("#survey-report-table").DataTable({
                    processing: true,
                    order: [[0, 'asc']],
                    data: this.surveyReportData.questions,
                    columns: [
                        {
                            data: null,
                            title: 'S/N',
                            width: '5%',
                            render: function(data, type, row, meta) {
                                return meta.row + 1 + meta.settings._iDisplayStart;
                            }
                        },
                        {
                            data: 'question',
                            title: 'Question',
                            width: '30%',
                            render: function(data) {
                                return data ? data.replace(/\n/g, '<br>') : '-';
                            }
                        },
                        {
                            data: 'type',
                            title: 'Type',
                            width: '12%',
                            render: function(data) {
                                const badges = {
                                    'single_option': '<span class="badge bg-primary">Single Option</span>',
                                    'multiple_choice': '<span class="badge bg-info">Multiple Choice</span>',
                                    'dropdown': '<span class="badge bg-secondary">Dropdown</span>',
                                    'rating': '<span class="badge bg-warning">Rating</span>',
                                    'yes_no_with_checkbox': '<span class="badge bg-success">Yes/No</span>',
                                    'yes_no_with_multi_checkbox': '<span class="badge bg-success">Yes/No Multi</span>',
                                    'text': '<span class="badge bg-dark">Text</span>',
                                };
                                return badges[data] || `<span class="badge bg-light text-dark">${data}</span>`;
                            }
                        },
                        {
                            data: 'label',
                            title: 'Label',
                            width: '10%',
                            render: function(data) {
                                return data && data.label 
                                    ? `<span class="badge" style="background-color: ${data.color}">${data.label}</span>`
                                    : '<span class="text-muted">-</span>';
                            }
                        },
                        {
                            data: 'report.total_responses',
                            title: 'Responses',
                            width: '8%',
                            className: 'text-center',
                            render: function(data) {
                                return `<strong>${data || 0}</strong>`;
                            }
                        },
                        {
                            data: null,
                            title: 'Results',
                            width: '35%',
                            render: function(data, type, row) {
                                const report = row.report;
                                
                                // For single option/dropdown questions with options
                                if (report.options && Array.isArray(report.options) && report.options.length > 0) {
                                    let html = '<div class="mb-1">';
                                    
                                    // Show average if available
                                    if (report.average !== null && report.average !== undefined) {
                                        html += `<div class="mb-2">
                                            <strong>Average:</strong> 
                                            <span class="badge bg-info">${report.average}</span>
                                        </div>`;
                                    }
                                    
                                    html += '<table class="table table-sm table-bordered mb-0" style="font-size: 0.85rem;">';
                                    html += '<thead class="table-light"><tr><th>Option</th><th width="80">Count</th><th width="120">%</th></tr></thead><tbody>';
                                    
                                    report.options.forEach(opt => {
                                        const progressColor = opt.percentage > 50 ? 'bg-success' : 
                                                            opt.percentage > 25 ? 'bg-info' : 'bg-secondary';
                                        
                                        html += `
                                            <tr>
                                                <td>${opt.text}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary">${opt.count}</span>
                                                </td>
                                                <td>
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar ${progressColor}" 
                                                            role="progressbar" 
                                                            style="width: ${opt.percentage}%"
                                                            aria-valuenow="${opt.percentage}" 
                                                            aria-valuemin="0" 
                                                            aria-valuemax="100">
                                                            ${opt.percentage}%
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        `;
                                    });
                                    
                                    html += '</tbody></table></div>';
                                    return html;
                                }
                                
                                // For rating/numeric questions
                                if (report.average !== undefined && report.min !== undefined && report.max !== undefined) {
                                    return `
                                        <div>
                                            <div class="mb-1">
                                                <strong>Average:</strong> 
                                                <span class="badge bg-info">${report.average || 'N/A'}</span>
                                            </div>
                                            <div>
                                                <strong>Range:</strong> 
                                                <span class="badge bg-secondary">${report.min || 0}</span>
                                                <i class="fa fa-arrow-right mx-1"></i>
                                                <span class="badge bg-secondary">${report.max || 0}</span>
                                            </div>
                                        </div>
                                    `;
                                }
                                
                                // For text questions
                                if (report.answers !== undefined) {
                                    if (!Array.isArray(report.answers) || report.answers.length === 0) {
                                        return '<span class="text-muted"><i class="fa fa-info-circle"></i> No responses yet</span>';
                                    }
                                    
                                    const displayLimit = 3;
                                    let html = '<ul class="mb-0 ps-3" style="font-size: 0.9rem;">';
                                    
                                    report.answers.slice(0, displayLimit).forEach(answer => {
                                        const truncated = answer.length > 60 
                                            ? answer.substring(0, 60) + '...' 
                                            : answer;
                                        html += `<li class="mb-1">${truncated}</li>`;
                                    });
                                    
                                    if (report.answers.length > displayLimit) {
                                        html += `<li class="text-muted">
                                            <em><i class="fa fa-plus-circle"></i> 
                                            ${report.answers.length - displayLimit} more response(s)</em>
                                        </li>`;
                                    }
                                    
                                    html += '</ul>';
                                    return html;
                                }
                                
                                return '<span class="text-muted">No data available</span>';
                            }
                        }
                    ],
                    order: [[0, "asc"]],
                    pageLength: 10,
                    autoWidth: false,
                    responsive: true,
                    layout: {
                        topStart: {
                            buttons: [
                                {
                                    text: '<i class="fa fa-arrow-left me-1"></i> Back to Patients',
                                    className: "btn btn-outline-primary btn-sm me-2",
                                    action: () => {
                                        this.showReport = false;
                                        this.surveyReportData = null;
                                        this.showSurveyPatientsList = true;
                                    }
                                },
                                { 
                                    extend: "copy", 
                                    className: "btn btn-outline-primary btn-sm",
                                    attr: { class: "btn btn-outline-primary btn-sm" },
                                    exportOptions: {
                                        columns: [0, 1, 2, 3, 4]  // Exclude Results column from export
                                    }
                                },
                                { 
                                    extend: "csv", 
                                    className: "btn btn-outline-primary btn-sm",
                                    attr: { class: "btn btn-outline-primary btn-sm" },
                                    exportOptions: {
                                        columns: [0, 1, 2, 3, 4]
                                    }
                                },
                                { 
                                    extend: "excel", 
                                    className: "btn btn-outline-primary btn-sm",
                                    title: `${this.surveyReportData?.survey_title} - Report`,
                                    attr: { class: "btn btn-outline-primary btn-sm" },
                                    exportOptions: {
                                        columns: [0, 1, 2, 3, 4]
                                    }
                                },
                                { 
                                    extend: "pdf", 
                                    className: "btn btn-outline-primary btn-sm",
                                    title: `${this.surveyReportData?.survey_title} - Report`,
                                    attr: { class: "btn btn-outline-primary btn-sm" },
                                    exportOptions: {
                                        columns: [0, 1, 2, 3, 4]
                                    }
                                },
                                { 
                                    extend: "print", 
                                    className: "btn btn-outline-primary btn-sm",
                                    title: `${this.surveyReportData?.survey_title} - Report`,
                                    attr: { class: "btn btn-outline-primary btn-sm" },
                                    exportOptions: {
                                        columns: [0, 1, 2, 3, 4]
                                    }
                                }
                            ]
                        },
                        topEnd: {
                            search: { placeholder: "Search questions..." }
                        }
                    }
                });
            },


            async viewSurveyReport(userId, surveyId) {
                console.log('Viewing report:', { userId, surveyId });

                this.selectedUserId = userId;
                this.selectedSurveyId = surveyId;

                this.showSurveyPatientsList = false;
                this.showSurveyResponses = true;

                await this.fetchSurveyAnswers(userId, surveyId);
            },


            backToSurveyList() {
                this.showSurveyResponses = false;
                this.showSurveyList = true;

                //if ($.fn.DataTable.isDataTable('#view-survey-response-table')) {
                    //$('#view-survey-response-table').DataTable().destroy();
                //}
                this.destroyTable('view-survey-response-table');
            },

       renderDataTable() {
    // Destroy existing instance if it exists
    // if ($.fn.DataTable.isDataTable('#general-questions-report-table')) {
    //     $('#general-questions-report-table').DataTable().destroy();
    // }

    this.destroyTable('general-questions-report-table');

    this.tableInstance = $("#general-questions-report-table").DataTable({
        processing: true,
        order: [[0, 'asc']],
        data: this.assessmentData,
        columns: [
            {
                data: null,
                title: 'S/N',
                width: '5%',
                render: function(data, type, row, meta) {
                    return meta.row + 1 + meta.settings._iDisplayStart;
                }
            },
            {
                data: 'question',
                title: 'Question',
                width: '30%',
                render: function(data) {
                    return data ? data.replace(/\n/g, '<br>') : '-';
                }
            },
            {
                data: 'type',
                title: 'Type',
                width: '12%',
                render: function(data) {
                    const badges = {
                        'single_choice': '<span class="badge bg-primary">Single Choice</span>',
                        'multiple_choice': '<span class="badge bg-info">Multiple Choice</span>',
                        'dropdown': '<span class="badge bg-secondary">Dropdown</span>',
                        'rating': '<span class="badge bg-warning">Rating</span>',
                        'yes_no_with_checkbox': '<span class="badge bg-success">Yes/No</span>',
                        'yes_no_with_multi_checkbox': '<span class="badge bg-success">Yes/No Multi</span>',
                        'text': '<span class="badge bg-dark">Text</span>',
                    };
                    return badges[data] || `<span class="badge bg-light text-dark">${data}</span>`;
                }
            },
            
            {
                data: 'label',
                width: '15%',
                render: data => data && data.label 
                    ? `<span class="badge" style="background-color: ${data.color}">${data.label}</span>`
                    : '<span class="text-muted">-</span>'
            },
            {
                data: 'report.total_responses',
                title: 'Responses',
                width: '10%',
                className: 'text-center',
                render: function(data) {
                    return `<strong>${data || 0}</strong>`;
                }
            },
            {
                data: null,
                title: 'Results',
                width: '43%',
                render: function(data, type, row) {
                    const report = row.report;
                    
                    // For single option/dropdown/yes_no questions with options
                    if (report.options && Array.isArray(report.options)) {
                        let html = '<div class="mb-1">';
                        
                        // Show average if available
                        if (report.average !== null && report.average !== undefined) {
                            html += `<div class="mb-2"><strong>Average:</strong> <span class="badge bg-info">${report.average}</span></div>`;
                        }
                        
                        html += '<table class="table table-sm table-bordered mb-0">';
                        html += '<thead><tr><th>Option</th><th>Count</th><th>%</th></tr></thead><tbody>';
                        
                        report.options.forEach(opt => {
                            const progressColor = opt.percentage > 50 ? 'bg-success' : 
                                                 opt.percentage > 25 ? 'bg-info' : 'bg-secondary';
                            
                            html += `
                                <tr>
                                    <td>${opt.text}</td>
                                    <td class="text-center"><span class="badge bg-primary">${opt.count}</span></td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar ${progressColor}" role="progressbar" 
                                                 style="width: ${opt.percentage}%">
                                                ${opt.percentage}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        });
                        
                        html += '</tbody></table></div>';
                        return html;
                    }
                    
                    // For rating/numeric questions
                    if (report.average !== undefined && report.min !== undefined) {
                        return `
                            <div>
                                <strong>Average:</strong> ${report.average}<br>
                                <strong>Min:</strong> ${report.min} | 
                                <strong>Max:</strong> ${report.max}
                            </div>
                        `;
                    }
                    
                    // For text questions
                    if (report.answers && Array.isArray(report.answers)) {
                        if (report.answers.length === 0) {
                            return '<span class="text-muted">No responses</span>';
                        }
                        
                        const displayLimit = 3;
                        let html = '<ul class="mb-0 ps-3">';
                        
                        report.answers.slice(0, displayLimit).forEach(answer => {
                            const truncated = answer.length > 50 ? answer.substring(0, 50) + '...' : answer;
                            html += `<li>${truncated}</li>`;
                        });
                        
                        if (report.answers.length > displayLimit) {
                            html += `<li class="text-muted"><em>+${report.answers.length - displayLimit} more...</em></li>`;
                        }
                        
                        html += '</ul>';
                        return html;
                    }
                    
                    return '<span class="text-muted">No data</span>';
                }
            }
        ],
        order: [[0, "asc"]],
        pageLength: 10,
        autoWidth: false,
        responsive: true,
        layout: {
            topStart: {
                buttons: [
                    { extend: "copy", className: "btn btn-outline-primary btn-sm", attr: { title: "Copy to clipboard", class: "btn btn-outline-primary" } },
                    { extend: "csv", className: "btn btn-outline-primary btn-sm", attr: { title: "Copy to clipboard", class: "btn btn-outline-primary" } },
                    { extend: "excel", className: "btn btn-outline-primary btn-sm", attr: { title: "Export to Excel", class: "btn btn-outline-primary" } },
                    { extend: "pdf", className: "btn btn-outline-primary btn-sm", attr: { title: "Export to PDF", class: "btn btn-outline-primary" } },
                    { extend: "print", className: "btn btn-outline-primary btn-sm", attr: { title: "Print table", class: "btn btn-outline-primary" } }
                ]
            },
            topEnd: {
                search: { placeholder: "Search questions..." }
            }
        }
    });
},

        populateQuestionLabelFilter(labels) {
            const select = document.getElementById('question-label-filter');

            select.innerHTML = `<option value="">All question labels</option>`;

            labels.sort().forEach(label => {
                const opt = document.createElement('option');
                opt.value = label;
                opt.textContent = label;
                select.appendChild(opt);
            });
        },

        populateFilter(filters){
            const select = document.getElementById('filter-item');
            select.innerHTML = `<option value="">All Frequency</option>`;
            filters.sort().forEach(item => {
                const opt = document.createElement('option');
                opt.value = item;
                opt.textContent = item;
                select.appendChild(opt);
            });
        },


        initQuestionLabelFilter() {
            document.getElementById('question-label-filter')
                .addEventListener('change', () => {

                    if (!this.tableInstance) return;

                    const value = document.getElementById('question-label-filter').value;

                    if (!value) {
                        this.tableInstance.column(3).search('').draw();
                    } else {
                        // match the label text inside the badge
                        this.tableInstance.column(3).search(value, false, false).draw();
                    }
                });
            },
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