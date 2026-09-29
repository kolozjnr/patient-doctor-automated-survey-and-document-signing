@extends('layouts.simple.master')

@section('title', 'Single Document Details')

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
                    <h3>{{__('Document Details')}}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"> <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">{{__('Documents')}}</li>
                        <li class="breadcrumb-item active">{{__('Document Details')}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div><!-- Container-fluid starts-->
    <div class="container-fluid">
       <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <!-- Simple Type and Status Row -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="" style="width: 18%">
                                    <span class="fw-semibold me-2">{{ __('Type') }}:</span>
                                    <span class="text-muted">{{ ucfirst($document->document_type) }}</span>
                                </div>
                                <div class="text-center">
                                    <span class="fw-semibold me-2">{{ __('Status') }}:</span>
                                    @php
                                        $statusColor = match($document->status) {
                                            'active' => 'success',
                                            'pending' => 'warning',
                                            'completed' => 'info',
                                            'cancelled' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }} px-3 py-1">{{ $document->status ?? 'N/A' }}</span>
                                </div>
                                <div style="width: 80px;"></div>
                            </div>

                            <!-- Shared With and Shared On Row -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div style="width: 25%">
                                    <span class="fw-semibold me-2">{{ __('No of Patients') }}:</span>
                                    <span class="text-muted">{{ $document->assignments_count ?? '0' }}</span>
                                </div>
                                <div class="text-center">
                                    <span class="me-2 fw-semibold">{{ __('Create At') }}:</span>
                                    <span class="text-muted">{{ $document->created_at ? \Carbon\Carbon::parse($document->shared_at)->format('d-m-Y H:i') : '06-03-2026 12:45' }}</span>
                                </div>
                                <div style="width: 80px;"></div>
                            </div>

                            <!-- Download Button -->
                            {{-- <div>
                                <a href="{{ $document->download_url ?? '#' }}" class="text-decoration-none d-inline-flex align-items-center text-primary">
                                    <i class="fa-solid fa-download me-2"></i>
                                    <span>{{ __('Document Downloaden') }}</span>
                                </a>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid product-report-wrapper" x-data="DocumentAssignmentsTable({{$document->id}})">
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
                                <div class="col-auto" style="visibility: hidden">
                                    <select id="treatment-filter" class="form-select w-auto hidden" style="display: non;">
                                        {{-- <option value="">All Treatments</option> --}}
                                    </select>

                                    {{-- <div class="range-dropdown" id="reportrange"><span></span></div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="product-report">
                            <div class="recent-table table-responsive custom-scrollbar">
                                <table class="table" id="view-patient-assignment-table">
                                    <thead>
                                        <tr>
                                            <th><span class="c-o-light f-w-600">S/N</span></th>
                                            <th><span class="c-o-light f-w-600">Name</span></th>
                                            <th><span class="c-o-light f-w-600">Email</span></th>
                                            <th><span class="c-o-light f-w-600">Status</span></th>
                                            <th><span class="c-o-light f-w-600">Action</span></th>
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

     function DocumentAssignmentsTable(documentId) {
        return {
            documentId: documentId,
            isLoading: false,
            isLoadingAssessment: false,
            charts: {},
            assessmentData: [], 
            tableInstance: null,

         init() {
            this.fetchDocumentAssignmentData();
        },

           

            async fetchDocumentAssignmentData() {
    this.isLoadingAssessment = true;
    try {
        const res = await fetch(`/documents/fetch-single-document/${this.documentId}`);
        const json = await res.json();

        if (json.success) {
            this.responseData = json.data; // ✅ store the full data object

            this.$nextTick(() => {
                this.renderDocumentAssignmentDataTable();
            });
        }
    } catch (e) {
        console.error(e);
    } finally {
        this.isLoadingAssessment = false;
    }
},

renderDocumentAssignmentDataTable() {
    if ($.fn.DataTable.isDataTable('#view-patient-assignment-table')) {
        $('#view-patient-assignment-table').DataTable().destroy();
    }

    const users = this.responseData.users ?? [];
    const assignments = this.responseData.assignments ?? [];

    const tableData = users.map(user => ({
        ...user,
        assignment: assignments.find(a => a.user_id === user.id) || {}
    }));

    this.tableInstance = $("#view-patient-assignment-table").DataTable({
        processing: true,
        data: tableData,
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
                data: null,
                title: 'Status',
                render: d => {
                    const status = d.pivot?.status ?? d.assignment?.status ?? '-';
                    const badgeClass = status === 'pending' ? 'warning' : status === 'signed' ? 'success' : 'secondary';
                    return `<span class="badge bg-${badgeClass} text-capitalize">${status}</span>`;
                }
            },
           {
            data: null,
            title: 'Action',
            orderable: false,
            searchable: false,
            render: d => {
                const assignment = d.assignment || {};
                const downloadUrl = assignment.consent_signed_url ?? '#';
                const status = d.pivot?.status ?? assignment.status;

                const actionBtn = status === 'completed'
                    ? `<a href="${downloadUrl}" class="btn btn-sm btn-outline-success" title="Download signed document" target="_blank">
                            <i class="fa fa-download"></i>
                    </a>`
                    : `<span class="text-muted">-</span>`;

                return `
                    <div class="d-flex gap-1 align-items-center">
                        ${actionBtn}
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
                    { extend: "copy", className: "btn btn-outline-primary btn-sm", attr: { class: "btn btn-outline-primary" } },
                    { extend: "csv", className: "btn btn-outline-primary btn-sm", attr: { class: "btn btn-outline-primary" } },
                    { extend: "excel", className: "btn btn-outline-primary btn-sm", attr: { class: "btn btn-outline-primary" } },
                    { extend: "pdf", className: "btn btn-outline-primary btn-sm", attr: { class: "btn btn-outline-primary" } },
                    { extend: "print", className: "btn btn-outline-primary btn-sm", attr: { class: "btn btn-outline-primary" } },
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
