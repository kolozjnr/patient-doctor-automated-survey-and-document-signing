@extends('layouts.simple.master')

@section('title', 'Single Surve Details')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/owlcarousel.css') }}">

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

<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    .card {
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .card-header {
        border-bottom: none;
        /* padding: 1.25rem 1.5rem; */
    }
    .card .card-header{
        padding: 8px !important;
    }
    
    .info-item label {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
    }
    
    .question-item {
        transition: all 0.2s;
    }
    
    .question-item:hover {
        background-color: #fff !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .question-row {
    height: 35px;                 /* fixed height */
    overflow: hidden;
}

.question-content {
    display: flex;
    align-items: center;
    white-space: nowrap;          /* force single line */
    overflow-x: auto;             /* horizontal scroll */
    overflow-y: hidden;
    gap: 8px;
}

/* Hide ugly scrollbar (optional) */
.question-content::-webkit-scrollbar {
    height: 6px;
}
.question-content::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}

/* Question text expands */
.question-text {
    flex: 1 1 auto;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    min-width: 200px;
}

/* Fixed-size items */
.question-type,
.question-icon {
    white-space: nowrap;
}

</style>

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>{{__('Survey Details')}}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"> <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">{{__('Surveys')}}</li>
                        <li class="breadcrumb-item active">{{__('Survey Details')}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div><!-- Container-fluid starts-->
    <div class="container-fluid">
        <div>
         <div class="container-fluid py-4">
            <div class="row g-4">
                <!-- Survey Details Card -->
                <div class="col-xxl-5 col-lg-5 col-md-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                            <h6 class="mb-0 text-white px-1">
                                <i class="fas fa-clipboard-list me-2 text-white"></i>
                                {{ __('Survey Details') }}
                            </h6>
                            <span class="badge bg-white text-info mx-2">
                                {{ __('1 Survey') }}
                            </span>
                        </div>
                        <div class="card-body p-4">
                            <div class="survey-info">
                                <div class="info-item mb-3 pb-3 border-bottom">
                                    <label class="text-muted small mb-1 d-block">{{ __('Title') }}</label>
                                    <h5 class="mb-0">{{ $survey->title }}</h5>
                                </div>
                                
                                <div class="info-item mb-3 pb-3 border-bottom">
                                    <label class="text-muted small mb-1 d-block">{{ __('Frequency') }}</label>
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i>
                                        @if($survey->frequency === 'custom' && $survey->custom_reoccurrence)
                                            @php
                                                $custom = json_decode($survey->custom_reoccurrence, true);

                                                $textParts = [];

                                                // Repeat interval
                                                if (!empty($custom['repeat']['interval']) && !empty($custom['repeat']['unit'])) {
                                                    $interval = $custom['repeat']['interval'];
                                                    $unit = $custom['repeat']['unit'];

                                                    // Pluralize unit if needed
                                                    $unitText = $interval > 1 ? __($unit . 's') : __($unit);

                                                    $textParts[] = __('Every') . " {$interval} {$unitText}";
                                                }

                                                // Repeat on (for weekly/day selection)
                                                if (!empty($custom['repeat_on']) && is_array($custom['repeat_on'])) {
                                                    $days = implode(', ', $custom['repeat_on']);
                                                    $textParts[] = __('On: ') . $days;
                                                }

                                                // End condition
                                                if (!empty($custom['ends']['type'])) {
                                                    switch($custom['ends']['type']) {
                                                        case 'never':
                                                            $textParts[] = __('Ends: Never');
                                                            break;
                                                        case 'on':
                                                            $endDate = !empty($custom['ends']['on']) ? \Carbon\Carbon::parse($custom['ends']['on'])->format('M d, Y') : '';
                                                            $textParts[] = __('Ends on: ') . $endDate;
                                                            break;
                                                        case 'after':
                                                            $occurrences = $custom['ends']['after'] ?? 1;
                                                            $textParts[] = __('Ends after: ') . $occurrences . ' ' . __('occurrence') . ($occurrences > 1 ? 's' : '');
                                                            break;
                                                    }
                                                }

                                                echo implode(' | ', $textParts);
                                            @endphp
                                        @else
                                            {{ ucfirst($survey->frequency) }}
                                        @endif
                                    </span>

                                </div>
                                
                                <div class="info-item mb-3 pb-3 border-bottom">
                                    <label class="text-muted small mb-1 d-block">{{ __('Description') }}</label>
                                    <p class="mb-0 fw-semibold">{{ $survey->description ?? 'No description' }}</p>
                                </div>
                                
                                <div class="info-item mb-3 pb-3 border-bottom">
                                    <label class="text-muted small mb-1 d-block">{{ __('Created At') }}</label>
                                    <p class="mb-0">{{ $survey->created_at->diffForHumans() }}</p>
                                </div>

                                <div class="info-item mb-3 pb-3 border-bottom">
                                    <label class="text-muted small mb-1 d-block">{{ __('Recievers') }}</label>
                                    <p class="mb-0 fw-semibold">{{ $survey->users->count() ?? 0 }} {{ __('Patients') }}</p>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Survey Patients Card -->
                {{-- <div class="col-xxl-4 col-lg-6 col-md-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-white">
                                <i class="fas fa-users me-2 text-white"></i>
                                {{ __('Patients') }}
                            </h6>
                            <span class="badge bg-white text-info">
                                {{ $survey->users->count() ?? 0 }} {{ __('Patients') }}
                            </span>
                        </div>
                        <div class="card-body p-4">
                            <div class="patients-list">
                                    @if($survey->users->isNotEmpty())
                            <div class="list-group list-group-flush">
                                @foreach($survey->users as $user)
                                    <div class="list-group-item px-0 py-3 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white me-3">
                                                {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $user->full_name ?? $user->first_name }}</h6>
                                                <small class="text-muted">{{ $user->email ?? '' }}</small>
                                            </div>
                                            <span class="badge bg-light text-dark">
                                                {{ $user->pivot->status ?? __('Pending') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">{{ __('No patients assigned to this survey yet.') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div> --}}

                <!-- Questions Card -->
                <div class="col-xxl-7 col-lg-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-white px-2">
                                <i class="fas fa-question-circle me-2 text-white"></i>
                                {{ __('Questions') }}
                            </h6>
                            <span class="badge bg-white text-info mx-2">
                                {{ $survey->surveyQuestions->count() ?? 0 }} {{ __('Questions') }}
                            </span>
                        </div>
                        <div class="card-body p-4">
                            @if($survey->surveyQuestions && $survey->surveyQuestions->count() > 0)
                                <div class="questions-list">
                                    @foreach($survey->surveyQuestions as $index => $surveyQuestion)
                                        <div class="question-item mb-3 p-1 bg-light rounded question-row">
                                            <div class="d-flex align-items-center w-100 question-content">
                                                
                                                <span class="badge bg-warning text-dark me-2 flex-shrink-0">
                                                    Q{{ $index + 1 }}
                                                </span>

                                                <!-- Question text -->
                                                <p class="mb-0 fw-semibold question-text"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="{{ $surveyQuestion->question->question ?? 'No question text' }}">
                                                    {{ $surveyQuestion->question->question ?? 'No question text' }}
                                                </p>

                                                <!-- Question type -->
                                                <small class="text-muted ms-3 flex-shrink-0 question-type">
                                                    {{ ucfirst($surveyQuestion->question->type ?? $surveyQuestion->type ?? 'text') }}
                                                </small>

                                                <!-- Icon -->
                                                {{-- <a href="#" class="ms-3 flex-shrink-0 question-icon">
                                                    <i class="fa-regular fa-file-zipper"></i>
                                                </a> --}}

                                            </div>
                                        </div>

                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-question fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">{{ __('No questions added yet.') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="container-fluid product-report-wrapper" x-data="SurveyPatientsTable({{$survey->id}})">
            <div class="col-12">
                <div class="card">
                    <div class="card-body px-0 pt-0">
                        {{-- <div class="card-header">
                            {{__('Patients')}}
                        </div> --}}
                        <div class="top-body">
                            <div class="row common-f-start g-sm-3 g-2">
                                <div class="col-auto">
                                    <label class="form-label"></label>
                                </div>
                                <div class="col-auto">
                                    <select id="treatment-filter" class="form-select w-auto hidden" style="display: non;">
                                        {{-- <option value="">All Treatments</option> --}}
                                    </select>

                                    {{-- <div class="range-dropdown" id="reportrange"><span></span></div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="product-report">
                            <div class="recent-table table-responsive custom-scrollbar">
                                <table class="table" id="view-survey-patient-table">
                                    <thead>
                                        <tr>
                                            <th> <span class="c-o-light f-w-600">S/N</span></th>
                                            <th> <span class="c-o-light f-w-600">{{__("Name")}}</span></th>
                                            <th> <span class="c-o-light f-w-600">{{__("Email")}}</span>
                                            </th>
                                            <th> <span class="c-o-light f-w-600">{{__("Action")}}</span></th>
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
        {{-- <div class="card">
            <div class="row product-page-main">
                <div class="col-sm-12">
                    <ul class="nav nav-tabs border-tab nav-primary mb-0" id="top-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-bs-toggle="tab"
                                href="#top-home" role="tab" aria-controls="top-home"
                                aria-selected="false">Description</a>
                            <div class="material-border"></div>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="contact-top-tab" data-bs-toggle="tab"
                                href="#top-contact" role="tab" aria-controls="top-contact"
                                aria-selected="true">Additional Info</a>
                            <div class="material-border"></div>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="brand-top-tab" data-bs-toggle="tab"
                                href="#top-brand" role="tab" aria-controls="top-brand" aria-selected="true">Write
                                Review</a>
                            <div class="material-border"></div>
                        </li>
                    </ul>
                    <div class="tab-content" id="top-tabContent">
                        <div class="tab-pane fade active show" id="top-home" role="tabpanel"
                            aria-labelledby="top-home-tab">
                        </div>
                        <div class="tab-pane fade" id="top-contact" role="tabpanel" aria-labelledby="contact-top-tab">
                            
                        </div>
                        <div class="tab-pane fade" id="top-brand" role="tabpanel" aria-labelledby="brand-top-tab">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div><!-- Container-fluid Ends-->
@endsection

<script>
    // SurveyPatientsTable

     function SurveyPatientsTable(surveyId) {
        return {
            surveyId: surveyId,
            isLoading: false,
            isLoadingAssessment: false,
            charts: {},
            assessmentData: [], 
            tableInstance: null,

         init() {
            this.fetchSurveyResponseData();
        },

           

            async fetchSurveyResponseData() {
                this.isLoadingAssessment = true;
                try {
                    const res = await fetch(`/surveys/get-survey-patients/${this.surveyId}`);
                    //console.log(res);
                    const json = await res.json();

                    if (json.success) {
                        this.responseData = json.data.users;

                        this.$nextTick(() => {
                            this.renderSurveyResponseDataTable();
                        });
                    }
                } catch (e) {
                    console.error(e);
                }
                finally{
                    this.isLoadingAssessment = false;
                }
            },

            
            renderSurveyResponseDataTable() {
                // Destroy existing instance if it exists
                if ($.fn.DataTable.isDataTable('#view-survey-patient-table')) {
                    $('#view-survey-patient-table').DataTable().destroy();
                }

                this.tableInstance = $("#view-survey-patient-table").DataTable({
                    processing: true,
                    data: this.responseData,
                    columnDefs: [
                        { targets: '_all', className: 'text-start' }
                    ],
                    columns: [
                        {
                            data: null,
                            title: 'S/N',
                            render: (data, type, row, meta) => meta.row + 1
                        },
                        {
                            data: null,
                            title: 'Name',
                            render: d => `${d.first_name ?? ''} ${d.last_name ?? ''}`.trim()
                        },
                        {
                            data: 'email',
                            title: 'Email',
                            defaultContent: '-'
                        },
                        {
                            data: 'id',
                            title: 'Action',
                            orderable: false,
                            searchable: false,
                            render: id => `
                                <a href="/patient/${id}?survey_id=${this.surveyId}"
                                class="btn btn-sm btn-outline-primary"
                                title="View patient survey">
                                    <i class="fa fa-eye"></i>
                                </a>
                            `

                        }
                    ],

                    order: [[1, "asc"]],
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



            backToSurveyList() {
                this.showSurveyResponses = false;
                this.showSurveyList = true;

                //if ($.fn.DataTable.isDataTable('#view-survey-response-table')) {
                    $('#view-survey-patient-table').DataTable().destroy();
                //}
            },

    

           
        };
    }

</script>

@section('scripts')
    <script src="{{ asset('assets/js/touchspin_2/custom_touchspin.js') }}"></script>
    <script src="{{ asset('assets/js/owlcarousel/owl.carousel.js') }}"></script>
    <script src="{{ asset('assets/js/ecommerce.js') }}"></script>
    <script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
    <script src="{{ asset('assets/js/custom_zoom_magnifier.js') }}"></script>

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
