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
    <style>
        /* Style for clickable rows */
        #general-questions-report-table tbody tr {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        #general-questions-report-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .view-results-btn {
            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
        }

        .view-results-btn:hover {
            color: #0056b3;
        }

        /* Hide the Results column from screen display but keep for export */
        /* #general-questions-report-table th:nth-child(6),
        #general-questions-report-table td:nth-child(6) {
            display: none;
        } */
 /* Hide the Results column from screen display but keep for export */
        /* #general-questions-report-table th:nth-child(3),
        #general-questions-report-table td:nth-child(3) {
            display: none;
        } */

        /* Force left alignment for entire table */
        #general-questions-report-table th,
        #general-questions-report-table td {
            text-align: left !important;
        }

    </style>
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
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"> 
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                            </svg></a>
                        </li>
                        <li class="breadcrumb-item">{{__('ECC')}}</li>
                        <li class="breadcrumb-item active">{{__('Report')}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Question Results Modal --}}
    <div class="modal fade" id="questionResultsModal" tabindex="-1" role="dialog" aria-labelledby="questionResultsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="questionResultsModalLabel">Question Results</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="questionResultsContent">
                    <!-- Content will be dynamically loaded here -->
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">{{__("Loading...")}}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__("Close")}}</button>
                </div>
            </div>
        </div>
    </div>

        <div class="container-fluid product-report-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body px-0 pt-0">
                        <div class="top-body">
                            <div class="row common-f-start g-sm-3 g-2 mb-2">
                                    {{-- <div class="col-auto"><label class="form-label">Select Dates</label></div> --}}
                                    <div class="col-auto">
                                        <select id="question-label-filter" class="form-select w-auto">
                                            <option value="">{{__("All question labels")}}</option>
                                        </select>
                                        {{-- <div class="range-dropdown" id="reportrange"><span></span></div> --}}
                                    </div>

                                    <div class="col-auto"  >
                                        <select id="question-type-filter" class="form-select w-auto">
                                            <option value="">{{__("All question Type")}}</option>
                                        </select>
                                        {{-- <div class="range-dropdown" id="reportrange"><span></span></div> --}}
                                    </div>
                                    <div class="col-auto"  x-show="currentView === 'generalReport'">
                                        <input type="text" id="dateRange" autocomplete="off" placeholder="{{__('From and To')}}" class="form-control w-auto"/>
                                    </div>
                                    
                                    
                            </div>
                        </div>
                        <div class="product-report">
                            <div class="recent-table table-responsive custom-scrollbar">
                                <div x-show="currentView === 'generalReport'" x-cloak>
                                    <div x-show="isLoading" class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">{{__("Loading...")}}</span>
                                        </div>
                                        <p class="mt-2">{{__("Loading questions...")}}</p>
                                    </div>
                                
                                    <div x-show="!isLoading">
                                        <table class="table" id="general-questions-report-table">
                                            <thead>
                                                <tr>
                                                    <th> <span class="c-o-light f-w-600">{{__("S/N")}}</span></th>
                                                    <th> <span class="c-o-light f-w-600">{{__("Question")}}</span></th>
                                                    <th> <span class="c-o-light f-w-600">{{__("Type")}}</span></th>
                                                    <th> <span class="c-o-light f-w-600">{{__("Label")}}</span></th>
                                                    <th> <span class="c-o-light f-w-600">{{__("Responses")}}</span></th>
                                                    <th> <span class="c-o-light f-w-600">{{__("Results")}}</span></th>
                                                    <th> <span class="c-o-light f-w-600">{{__("Action")}}</span></th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div x-show="currentView === 'responseReport'" x-cloak>
                                    <div x-show="isLoading" class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">{{__("Loading...")}}</span>
                                        </div>
                                        <p class="mt-2">{{__("Loading response...")}}</p>
                                    </div>
                                
                                    <div x-show="!isLoading">
                                        <table class="table" id="general-response-report-table">
                                            <thead>
                                                <tr>
                                                    <th> <span class="c-o-light f-w-600">{{__("S/N")}}</span></th>
                                                    <th> <span class="c-o-light f-w-600">{{__("Patiend ID")}}</span></th>
                                                    <th> <span class="c-o-light f-w-600">{{__("Fullname")}}</span></th>
                                                    <th> <span class="c-o-light f-w-600">{{__("Responses")}}</span></th>
                                                    <th> <span class="c-o-light f-w-600">{{__("Results")}}</span></th>
                                                    {{-- <th> <span class="c-o-light f-w-600">{{__("Action")}}</span></th> --}}
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
    </div><!-- Container-fluid Ends-->


</section>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>

<script>
function ReportComponent() {
    return {
        currentView: "generalReport",
        isLoading: false,
        isLoadingAssessment: false,
        assessmentData: [],
        PatientResponsesData: [],
        tableInstance: null,
        selectedQuestion: null,
        selectedModule: 'general',

        init() {
            this.$nextTick(() => {
                this.initQuestionLabelFilter();
                this.initQuestionTypeFilter();
                this.initDateRangeFilter();
                this.fetchGeneralQuestionReport('general');
            });
        },

        destroyTable(tableId) {
            if ($.fn.DataTable.isDataTable(`#${tableId}`)) {
                $(`#${tableId}`).DataTable().destroy();
            }
        },

        
        async fetchGeneralQuestionReport(module, from = null, to = null) {
            this.isLoadingAssessment = true;
            this.isLoading = true;

            try {
                const url = new URL(`/reports/questions-report/${module}`, window.location.origin);
                if (from) url.searchParams.set('from', from);
                if (to)   url.searchParams.set('to', to);

                //console.log('from', from)

                const res  = await fetch(url.toString());
                const json = await res.json();

                if (json.success) {
                    this.assessmentData = json.data;

                    //console.log('Fetched assessment data:', this.assessmentData);

                    this.$nextTick(() => {
                        setTimeout(() => {
                            this.renderDataTable();
                            window.dispatchEvent(new Event('resize'));
                        }, 100);
                    });

                    const labelSet = new Set();
                    this.assessmentData.forEach(q => {
                        if (q.label?.label) labelSet.add(q.label.label);
                    });
                    this.populateQuestionLabelFilter([...labelSet]);

                    const typeSet = new Set();
                    this.assessmentData.forEach(q => {
                        if (q.type) typeSet.add(q.type);
                    });
                    this.populateQuestionTypeFilter([...typeSet]);
                }
            } catch (error) {
                console.error("Error fetching assessment:", error);
            } finally {
                this.isLoadingAssessment = false;
                this.isLoading = false;
            }
        },

        async fetchQuestionResponse(module, questionId) {
            try {
                const res = await fetch(`/reports/single-question-response/${questionId}/${module}`);
                const json = await res.json();

                if (json.success) {
                    this.PatientResponsesData = json.data;

                    this.$nextTick(() => {
                        setTimeout(() => {
                            this.renderQuestionResponse();
                        }, 50);
                    });
                }
            } catch (e) {
                console.error(e);
            } finally {
                this.isLoading = false; // ✅ fix wrong variable
            }
        },

        formatExportData(report, type) {
            if (!report) return '-';
            let result = '';

            if (report.average !== null && report.average !== undefined) {
                result += `Average: ${report.average}`;
            }
            if (report.min !== undefined && report.max !== undefined) {
                if (result) result += ' | ';
                result += `Min: ${report.min}, Max: ${report.max}`;
            }
            if (report.options?.length > 0) {
                if (result) result += ' | ';
                result += report.options.map(opt =>
                    `${opt.text}: ${opt.count} (${opt.percentage}%)`
                ).join('; ');
            }
            if (report.answers?.length > 0) {
                if (result) result += ' | ';
                result += `${report.answers.length} text responses`;
            }

            return result || '-';
        },

        renderDataTable() {

            const self = this;
            this.destroyTable('general-questions-report-table');

            // FIX: Register a custom search that operates on raw data values,
            // not rendered HTML, so badge markup doesn't break filtering.
            $.fn.dataTable.ext.search.push(function(settings, searchData, index, rowData) {
                if (settings.nTable.id !== 'general-questions-report-table') return true;
                return true; // Actual column filtering handled by column().search() on orthogonal data
            });

            this.tableInstance = $("#general-questions-report-table").DataTable({
                processing: true,
                data: this.assessmentData,
                order: [[0, 'asc']],
                autoWidth: false,
                responsive: false,
                pageLength: 10,
                columnDefs: [
                    { targets: '_all', className: 'text-start' },

                    // FIX: Provide orthogonal data so column search uses plain
                    // text ("Rating") instead of the badge HTML.
                    {
                        targets: 2, // Type column
                        render: function(data, type, row) {
                            if (type === 'filter' || type === 'export') {
                                const typeMap = {
                                    'single_choice': 'Single Choice',
                                    'multiple_choice': 'Multiple Choice',
                                    'dropdown': 'Dropdown',
                                    'rating': 'Rating',
                                    'yes_no_with_checkbox': 'Yes/No',
                                    'yes_no_with_multi_checkbox': 'Yes/No Multi',
                                    'text': 'Text',
                                };
                                return typeMap[data] || data;
                            }
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
                        targets: 3, // Label column
                        render: function(data, type, row) {
                            if (type === 'filter' || type === 'export') {
                                return data?.label ?? '-';
                            }
                            return data?.label
                                ? `<span class="badge" style="background-color: ${data.color}">${data.label}</span>`
                                : '<span class="text-muted">-</span>';
                        }
                    },
                ],

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
                        width: '35%',
                        render: function(data, type, row) {
                            if (type === 'export') return data ? data.replace(/<br>/g, ' ').replace(/\n/g, ' ') : '-';
                            return data ? data.replace(/\n/g, '<br>') : '-';
                        }
                    },
                    { data: 'type',  title: 'Type',  width: '10%' },
                    { data: 'label', title: 'Label', width: '10%' },
                    {
                        data: 'report.total_responses',
                        title: 'Responses',
                        width: '8%',
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (type === 'export') return data || 0;
                            return `<strong>${data || 0}</strong>`;
                        }
                    },
                    {
                        data: null,
                        title: 'Results Summary',
                        width: '30%',
                        render: function(data, type, row) {
                            const report = row.report;
                            if (type === 'export') return self.formatExportData(report, row.type);
                            if (!report) return '<span class="text-muted">No data</span>';

                            let parts = [];
                            if (report.average !== null && report.average !== undefined) {
                                parts.push(`Avg: ${report.average}`);
                            }
                            if (report.options?.length > 0) parts.push(`${report.options.length} options`);
                            if (report.answers?.length > 0) parts.push(`${report.answers.length} text answers`);

                            return parts.length ? parts.join(' | ') : '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: null,
                        title: 'Action',
                        width: '10%',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'export') return '';
                            return `<div class="btn-group btn-group-sm gap-2 justify-content-center" role="group"> 
                                <button class="btn btn-sm btn-primary view-results-btn">
                                <i class="fa fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-primary view-responses-btn" 
                                    data-question-id="${row.question_id}">
                                <i class="fa-solid fa-users-viewfinder"></i>
                            </button>
                            </div>
                            `;
                        }
                    }
                ],

                layout: {
                    topStart: {
                        buttons: [
                            {
                                extend: "copy",
                                className: "btn btn-outline-primary btn-sm",
                                attr: { title: "Copy to clipboard", class: "btn btn-outline-primary btn-sm" },
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5], // Include Results Summary, exclude Action
                                    format: {
                                        body: function(data, row, column, node) {
                                            // Strip HTML tags for clean export
                                            return data.replace(/<[^>]*>/g, '');
                                        }
                                    }
                                }
                            },
                            {
                                extend: "csv",
                                className: "btn btn-outline-primary btn-sm",
                                attr: { class: "btn btn-outline-primary btn-sm" },
                                title: 'Questions_Report_' + new Date().toISOString().split('T')[0],
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5]
                                }
                            },
                            {
                                extend: "excel",
                                className: "btn btn-outline-primary btn-sm",
                                title: 'Questions_Report_' + new Date().toISOString().split('T')[0],
                                attr: { class: "btn btn-outline-primary btn-sm" },
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5]
                                },
                                customize: function(xlsx) {
                                    // Customize Excel export
                                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                    
                                    // Auto-width columns
                                    $('row c[r^="B"]', sheet).attr('s', '55'); // Wrap text for Question column
                                    $('row c[r^="F"]', sheet).attr('s', '55'); // Wrap text for Results Summary column
                                }
                            },
                            {
                                extend: "pdf",
                                className: "btn btn-outline-primary btn-sm",
                                attr: { title: "Export as PDF", class: "btn btn-outline-primary btn-sm" },
                                orientation: 'landscape',
                                pageSize: 'A4',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5]
                                },
                                customize: function(doc) {
                                    // Customize PDF
                                    doc.content[1].table.widths = ['5%', '25%', '10%', '10%', '10%', '40%'];
                                    
                                    // Add header
                                    doc.content.splice(0, 1, {
                                        text: 'Questions Report - ' + new Date().toLocaleDateString(),
                                        style: 'header',
                                        alignment: 'center',
                                        margin: [0, 0, 0, 10]
                                    });
                                    
                                    // Style the document
                                    doc.styles.header = {
                                        fontSize: 18,
                                        bold: true
                                    };
                                    
                                    doc.defaultStyle = {
                                        fontSize: 8
                                    };
                                }
                            },
                            {
                                extend: "print",
                                className: "btn btn-outline-primary btn-sm",
                                attr: { title: "Print", class: "btn btn-outline-primary btn-sm" },
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5]
                                },
                                customize: function(win) {
                                    // Add custom styles for print
                                    $(win.document.body)
                                        .css('font-size', '10pt')
                                        .prepend(
                                            '<h1 style="text-align:center;">Questions Report</h1>' +
                                            '<p style="text-align:center;">Generated on: ' + new Date().toLocaleString() + '</p>'
                                        );
                                    
                                    $(win.document.body).find('table')
                                        .addClass('compact')
                                        .css('font-size', 'inherit');
                                }
                            }
                        ]
                    },
                    topEnd: {
                        search: { placeholder: "Search questions..." }
                    }
                }
            });

            // Row click handler for View Results button
            $('#general-questions-report-table tbody').on('click', '.view-results-btn', function(e) {
                e.stopPropagation();
                const rowData = self.tableInstance.row($(this).closest('tr')).data();
                self.showQuestionResults(rowData);
            });

            $('#general-questions-report-table tbody').off('click', '.view-responses-btn').on('click', '.view-responses-btn', function (e) {
                e.stopPropagation();

                const questionId = $(this).data('question-id');

                self.viewQuestionResponse(questionId);
            });
        },

    

             renderQuestionResponse() {
                const alpine = this;
                this.destroyTable('general-response-report-table');
                // if ($.fn.DataTable.isDataTable('#survey-report-table')) {
                //     $('#survey-report-table').DataTable().destroy();
                // }
                
                this.tableInstance = $("#general-response-report-table").DataTable({
                    processing: true,
                    order: [[0, 'asc']],
                    data: this.PatientResponsesData.respondents,
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
                        data: 'user.patient_id',
                        title: 'Patient ID',
                        defaultContent: '-'
                    },
                    {
                        data: null,
                        title: 'Fullname',
                        render: row => row.user?.name || '-'
                    },
                    {
                        data: 'answer',
                        title: 'Response',
                        render: function(data) {
                            if (!data) return '<span class="text-muted">No Answer</span>';

                            // Handle array (multiple choice)
                            if (Array.isArray(data)) {
                                return data.join(', ');
                            }

                            return data;
                        }
                    },
                    {
                        data: 'submitted_at',
                        title: 'Submitted At',
                        render: function(data) {
                            if (!data) return '-';
                            return new Date(data).toLocaleString();
                        }
                    },
                    // {
                    //     data: null,
                    //     title: 'Action',
                    //     orderable: false,
                    //     render: row => `
                    //         <button class="btn btn-outline-primary btn-sm view-user-response"
                    //             data-user-id="${row.user?.id}"
                    //             data-answer-id="${row.answer_id}">
                    //             View
                    //         </button>
                    //     `
                    // }
                ],
                    order: [[0, "asc"]],
                    pageLength: 10,
                    autoWidth: false,
                    responsive: false,
                    layout: {
                        topStart: {
                            buttons: [
                                {
                                    text: '<i class="fa fa-arrow-left me-1"></i> Back to Reports',
                                    className: "btn btn-outline-primary btn-sm me-2",
                                    attr: { class: "btn btn-outline-primary btn-sm" },
                                    action: () => {
                                        alpine.backToReports()
                                    }
                                },
                                { 
                                    extend: "copy", 
                                    className: "btn btn-outline-primary btn-sm",
                                    attr: { class: "btn btn-outline-primary btn-sm" },
                                    exportOptions: {
                                        columns: [0, 1, 2, 3], // Include Results Summary
                                        format: {
                                            body: function(data, row, column, node) {
                                                return data.replace(/<[^>]*>/g, '');
                                            }
                                        }
                                    }
                                },
                                { 
                                    extend: "csv", 
                                    className: "btn btn-outline-primary btn-sm",
                                    title: `${this.surveyReportData?.survey_title || 'Question'}_Report_${new Date().toISOString().split('T')[0]}`,
                                    attr: { class: "btn btn-outline-primary btn-sm" },
                                    exportOptions: {
                                        columns: [0, 1, 2, 3]
                                    }
                                },
                                { 
                                    extend: "excel", 
                                    className: "btn btn-outline-primary btn-sm",
                                    title: `${this.surveyReportData?.survey_title || 'Question'} - Report`,
                                    attr: { class: "btn btn-outline-primary btn-sm" },
                                    exportOptions: {
                                        columns: [0, 1, 2, 3]
                                    },
                                    customize: function(xlsx) {
                                        const questionText = alpine.PatientResponsesData?.question?.text || '';
                                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                        // Wrap text for Question and Results Summary columns
                                        $('row c[r^="B"]', sheet).attr('s', '55');
                                        $('row c[r^="F"]', sheet).attr('s', '55');
                                    }
                                },
                                { 
                                    extend: "pdf", 
                                    className: "btn btn-outline-primary btn-sm",
                                    title: `${questionText || 'Question'} - Report`,
                                    orientation: 'landscape',
                                    pageSize: 'A4',
                                    attr: { class: "btn btn-outline-primary btn-sm" },
                                    exportOptions: {
                                        columns: [0, 1, 2, 3]
                                    },
                                    customize: function(doc) {
                                        const questionText = alpine.PatientResponsesData?.question?.text || '';

                                        doc.content[1].table.widths = ['5%', '20%', '10%', '10%', '8%', '47%'];
                                        
                                        doc.content.splice(0, 1, {
                                            text: `${questionText || 'Question'} - Report - ${new Date().toLocaleDateString()}`,
                                            style: 'header',
                                            alignment: 'center',
                                            margin: [0, 0, 0, 10]
                                        });
                                        
                                        doc.styles.header = {
                                            fontSize: 16,
                                            bold: true
                                        };
                                        
                                        doc.defaultStyle = {
                                            fontSize: 7
                                        };
                                    }
                                },
                                { 
                                    extend: "print", 
                                    className: "btn btn-outline-primary btn-sm",
                                    title: `${this.surveyReportData?.survey_title || 'Question'} - Report`,
                                    attr: { class: "btn btn-outline-primary btn-sm" },
                                    exportOptions: {
                                        columns: [0, 1, 2, 3]
                                    },
                                    customize: function(win) {
                                         const questionText = alpine.PatientResponsesData?.question?.text || '';

                                        $(win.document.body)
                                            .css('font-size', '9pt')
                                            .prepend(
                                                `<h1 style="text-align:center;">${alpine.surveyReportData?.survey_title || 'Question'} - Report</h1>` + 
                                                `   <p style="text-align:center; font-weight:600; margin:5px 0;">
                                                        ${questionText}
                                                    </p>` +
                                                
                                                '<p style="text-align:center;">Generated on: ' + new Date().toLocaleString() + '</p>'
                                            );
                                        
                                        $(win.document.body).find('table')
                                            .addClass('compact')
                                            .css('font-size', 'inherit');
                                    }
                                }
                            ]
                        },
                        topEnd: {
                            search: { placeholder: "Search questions..." }
                        }
                    }
                });

                // Add click event for View Results button
                $('#general-response-report-table tbody')
                .off('click', '.view-user-response')
                .on('click', '.view-user-response', (e) => {
                    const btn = e.currentTarget;

                    const userId = btn.dataset.userId;
                    const answerId = btn.dataset.answerId;

                    console.log(userId, answerId);
                });
            },

        async viewQuestionResponse(questionId) {

            this.selectedQuestionId = questionId;

            this.currentView = 'responseReport';

            this.assessmentData = null;

            this.isLoading = true;

            await this.$nextTick();

            await this.fetchQuestionResponse(this.selectedModule, questionId);
        },

        backToReports()
        {
            this.destroyTable('general-response-report-table tbody');
            this.currentView = 'generalReport';
        },



        initDateRangeFilter() {
            const input = document.getElementById('dateRange');
            if (!input) return;

            flatpickr(input, {
                mode: 'range',
                dateFormat: 'Y-m-d',
                allowInput: true,
                onClose: (selectedDates) => {
                    if (selectedDates.length === 2) {
                        const from = selectedDates[0].toISOString().split('T')[0];
                        const to   = selectedDates[1].toISOString().split('T')[0];
                        //console.log('flat', from)
                        this.fetchGeneralQuestionReport('general', from, to);
                    }
                },
                onReady: (selectedDates, dateStr, instance) => {
                    const clearBtn = document.createElement('button');
                    clearBtn.textContent = 'Clear';
                    clearBtn.className = 'btn btn-sm btn-outline-secondary mt-2 w-100';
                    clearBtn.addEventListener('click', () => {
                        instance.clear();
                        this.fetchGeneralQuestionReport('general'); // fetch without dates
                    });
                    instance.calendarContainer.appendChild(clearBtn);
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

        populateQuestionTypeFilter(types) {
            const select = document.getElementById('question-type-filter');
            select.innerHTML = `<option value="">All question Types</option>`;
            types.sort().forEach(type => {
                const opt = document.createElement('option');
                opt.value = type;
                opt.textContent = type;
                select.appendChild(opt);
            });
        },

        initQuestionLabelFilter() {
            document.getElementById('question-label-filter').addEventListener('change', () => {
                if (!this.tableInstance) return;
                const value = document.getElementById('question-label-filter').value;
                // FIX: search on column 3 which now returns plain text for 'filter' type
                this.tableInstance.column(3).search(value, false, false).draw();
            });
        },

        initQuestionTypeFilter() {
            document.getElementById('question-type-filter').addEventListener('change', () => {
                if (!this.tableInstance) return;
                const value = document.getElementById('question-type-filter').value;
                // FIX: search on column 2 which now returns plain text for 'filter' type
                this.tableInstance.column(2).search(value, false, false).draw();
            });
        },

        showQuestionResults(questionData) {
            this.selectedQuestion = questionData;
            document.getElementById('questionResultsContent').innerHTML = this.generateResultsContent(questionData);
            document.getElementById('questionResultsModalLabel').textContent = 'Results: ' + (questionData.question || 'Question');
            new bootstrap.Modal(document.getElementById('questionResultsModal')).show();
        },

        generateResultsContent(questionData) {
            const report = questionData.report;
            if (!report) return '<div class="alert alert-warning">No data available for this question.</div>';

            let html = `
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">${questionData.question}</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h3 class="text-white">${report.total_responses || 0}</h3>
                                            <p class="mb-0 text-white">Total Responses</p>
                                        </div>
                                    </div>
                                </div>
            `;

            if (report.average !== null && report.average !== undefined) {
                html += `
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h3 class="text-white">${report.average}</h3>
                                <p class="mb-0 text-white">Average</p>
                            </div>
                        </div>
                    </div>`;
            }

            if (report.min !== undefined && report.max !== undefined) {
                html += `
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h3>${report.min}</h3>
                                <p class="mb-0">Minimum</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h3>${report.max}</h3>
                                <p class="mb-0">Maximum</p>
                            </div>
                        </div>
                    </div>`;
            }

            html += '</div></div></div>';

            if (report.options?.length > 0) {
                html += `
                    <div class="row">
                        <div class="col-12">
                            <h6 class="mb-3">Response Breakdown</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr><th>Option</th><th class="text-center">Count</th><th>Percentage</th></tr>
                                    </thead>
                                    <tbody>`;

                report.options.forEach(opt => {
                    const progressColor = opt.percentage > 50 ? 'bg-success' : opt.percentage > 25 ? 'bg-info' : 'bg-secondary';
                    html += `
                        <tr>
                            <td><strong>${opt.text}</strong></td>
                            <td class="text-center"><span class="badge bg-primary">${opt.count}</span></td>
                            <td style="width:50%">
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1" style="height:25px">
                                        <div class="progress-bar ${progressColor}" role="progressbar"
                                             style="width:${opt.percentage}%"
                                             aria-valuenow="${opt.percentage}" aria-valuemin="0" aria-valuemax="100">
                                            ${opt.percentage}%
                                        </div>
                                    </div>
                                    <span class="ms-2"><strong>${opt.percentage}%</strong></span>
                                </div>
                            </td>
                        </tr>`;
                });

                html += '</tbody></table></div></div></div>';
            }

            if (report.answers?.length > 0) {
                html += `
                    <div class="row">
                        <div class="col-12">
                            <h6 class="mb-3">Text Responses (${report.answers.length})</h6>
                            <div class="list-group" style="max-height:400px;overflow-y:auto">`;

                report.answers.forEach((answer, i) => {
                    html += `
                        <div class="list-group-item">
                            <h6 class="mb-1">Response ${i + 1}</h6>
                            <p class="mb-1">${answer}</p>
                        </div>`;
                });

                html += '</div></div></div>';
            }

            return html + '</div>';
        }
    };
}
</script>

<script src="{{ asset('assets/js/counter/custom-counter1.js') }}"></script>
<script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
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
<script src="{{ asset('assets/js/modalpage/validation-modal.js') }}"></script>
<script src="{{ asset('assets/js/select/bootstrap-select.min.js') }}"></script>
@endsection