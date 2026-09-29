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
                    <h5 class="modal-title" id="questionResultsModalLabel">{{__("Question Results")}}</h5>
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
                            <div class="row common-f-start g-sm-3 g-2">
                                {{-- <div class="col-auto"><label class="form-label">Select Dates</label></div> --}}
                                <div class="col-auto">
                                    <select id="question-label-filter" class="form-select w-auto">
                                        <option value="">{{__("All question labels")}}</option>
                                    </select>
                                    {{-- <div class="range-dropdown" id="reportrange"><span></span></div> --}}
                                </div>

                                <div class="col-auto">
                                    <select id="question-type-filter" class="form-select w-auto">
                                        <option value="">{{__("All question Type")}}</option>
                                    </select>
                                    {{-- <div class="range-dropdown" id="reportrange"><span></span></div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="product-report">
                            <div class="recent-table table-responsive custom-scrollbar">
                                <table class="table" id="general-questions-report-table">
                                    <thead>
                                        <tr>
                                            <th> <span class="c-o-light f-w-600">S/N</span></th>
                                            <th> <span class="c-o-light f-w-600">{{__("Question")}}</span></th>
                                            <th> <span class="c-o-light f-w-600">{{__("Type")}}</span></th>
                                            <th> <span class="c-o-light f-w-600">{{__("Label")}}</span></th>
                                            <th> <span class="c-o-light f-w-600">{{__("Responses")}}</span></th>
                                            <th> <span class="c-o-light f-w-600">{{__("Results")}}</span></th>
                                            <th> <span class="c-o-light f-w-600">{{__("Action")}}</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-if="isLoading">
                                            <tr>
                                                <td colspan="7" class="text-center py-4" style="text-align: center !important">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">{{__("Loading")}}</span>
                                                    </div>
                                                    <p class="mt-2">{{__("Loading questions...")}}</p>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
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
        isLoading: false,
        isLoadingAssessment: false,
        assessmentData: [], 
        tableInstance: null,
        ordering: false,
        selectedQuestion: null,

        init() {
            this.$nextTick(() => {
                this.initQuestionLabelFilter();
                this.initQuestionTypeFilter();
                this.fetchBellscaleReport('bellscale');
            });
        },

        async fetchBellscaleReport(module) {
            this.isLoadingAssessment = true;
            this.isLoading = true;
            
            try {
                const res = await fetch(`/reports/get-bellscale-report/${module}`);
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

                    const typeSet = new Set();
                    this.assessmentData.forEach(q => {
                        if (q.type) {
                            typeSet.add(q.type);
                        }
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

        // Helper function to format export data
        formatExportData(report, type) {
            if (!report) return '-';

            let result = '';

            // Add average if available
            if (report.average !== null && report.average !== undefined) {
                result += `Average: ${report.average}`;
            }

            // Add min/max if available
            if (report.min !== undefined && report.max !== undefined) {
                if (result) result += ' | ';
                result += `Min: ${report.min}, Max: ${report.max}`;
            }

            // Add options breakdown
            if (report.options && Array.isArray(report.options) && report.options.length > 0) {
                if (result) result += ' | ';
                const optionsText = report.options.map(opt => 
                    `${opt.text}: ${opt.count} (${opt.percentage}%)`
                ).join('; ');
                result += optionsText;
            }

            // Add text answers count
            if (report.answers && Array.isArray(report.answers) && report.answers.length > 0) {
                if (result) result += ' | ';
                result += `${report.answers.length} text responses`;
            }

            return result || '-';
        },

        renderDataTable() {
            // Destroy existing instance if it exists
            if ($.fn.DataTable.isDataTable('#general-questions-report-table')) {
                $('#general-questions-report-table').DataTable().destroy();
            }

            const self = this;

            this.tableInstance = $("#general-questions-report-table").DataTable({
                processing: true,
                order: [[0, 'asc']],
                data: this.assessmentData,
                columnDefs: [
                    { targets: '_all', className: 'text-start' }
                ],

                columns: [
                    {
                        data: null,
                        title: 'S/N',
                        width: '5%',
                        orderable: true,
                        render: function(data, type, row, meta) {
                            return meta.row + 1 + meta.settings._iDisplayStart;
                        }
                    },
                    {
                        data: 'question',
                        title: 'Question',
                        width: '35%',
                        render: function(data, type, row) {
                            if (type === 'export') {
                                // Clean text for export
                                return data ? data.replace(/<br>/g, ' ').replace(/\n/g, ' ') : '-';
                            }
                            return data ? data.replace(/\n/g, '<br>') : '-';
                        }
                    },
                    {
                        data: 'type',
                        title: 'Type',
                        width: '10%',
                        render: function(data, type, row) {
                            if (type === 'export') {
                                // Return plain text for export
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
                            
                            // HTML badges for display
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
                        title: 'Label',
                        width: '10%',
                        render: function(data, type, row) {
                            if (type === 'export') {
                                return data && data.label ? data.label : '-';
                            }
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
                        render: function(data, type, row) {
                            if (type === 'export') {
                                return data || 0;
                            }
                            return `<strong>${data || 0}</strong>`;
                        }
                    },
                    {
                        data: null,
                        title: 'Results Summary',
                        width: '30%',
                        render: function(data, type, row) {
                            const report = row.report;
                            
                            if (type === 'export') {
                                // Format comprehensive export data
                                return self.formatExportData(report, row.type);
                            }
                            
                            // For display, show minimal info since full details are in modal
                            if (!report) return '<span class="text-muted">No data</span>';
                            
                            let displayText = '';
                            
                            if (report.average !== null && report.average !== undefined) {
                                displayText += `Avg: ${report.average}`;
                            }
                            
                            if (report.options && report.options.length > 0) {
                                if (displayText) displayText += ' | ';
                                displayText += `${report.options.length} options`;
                            }
                            
                            if (report.answers && report.answers.length > 0) {
                                if (displayText) displayText += ' | ';
                                displayText += `${report.answers.length} text answers`;
                            }
                            
                            return displayText || '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: null,
                        title: 'Action',
                        width: '10%',
                        className: 'text-center',
                        orderable: false,
                        exportable: false, // Don't export this column
                        render: function(data, type, row) {
                            if (type === 'export') {
                                return ''; // Empty for export
                            }
                            return `<button class="btn btn-sm btn-primary view-results-btn" data-row-index="${row.id || ''}">
                                <i class="fa fa-eye"></i> 
                            </button>`;
                        }
                    }
                ],
                order: [[0, "asc"]],
                pageLength: 10,
                autoWidth: false,
                responsive: false, // Disable responsive to ensure export works properly
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

            // Add click event for View Results button
            $('#general-questions-report-table tbody').on('click', '.view-results-btn', function(e) {
                e.stopPropagation();
                const table = self.tableInstance;
                const tr = $(this).closest('tr');
                const row = table.row(tr);
                const rowData = row.data();
                
                self.showQuestionResults(rowData);
            });
        },

        showQuestionResults(questionData) {
            this.selectedQuestion = questionData;
            
            // Generate modal content
            const modalContent = this.generateResultsContent(questionData);
            
            // Update modal content
            document.getElementById('questionResultsContent').innerHTML = modalContent;
            document.getElementById('questionResultsModalLabel').textContent = 'Results: ' + (questionData.question || 'Question');
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('questionResultsModal'));
            modal.show();
        },

        generateResultsContent(questionData) {
            const report = questionData.report;
            
            if (!report) {
                return '<div class="alert alert-warning">No data available for this question.</div>';
            }

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

            // Add average if available
            if (report.average !== null && report.average !== undefined) {
                html += `
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h3 class="text-white">${report.average}</h3>
                                <p class="mb-0 text-white">Average</p>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Add min/max if available
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
                    </div>
                `;
            }

            html += '</div></div></div>';

            // For options-based questions
            if (report.options && Array.isArray(report.options) && report.options.length > 0) {
                html += `
                    <div class="row">
                        <div class="col-12">
                            <h6 class="mb-3">Response Breakdown</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Option</th>
                                            <th class="text-center">Count</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                `;

                report.options.forEach(opt => {
                    const progressColor = opt.percentage > 50 ? 'bg-success' : 
                                         opt.percentage > 25 ? 'bg-info' : 'bg-secondary';
                    
                    html += `
                        <tr>
                            <td><strong>${opt.text}</strong></td>
                            <td class="text-center">
                                <span class="badge bg-primary">${opt.count}</span>
                            </td>
                            <td style="width: 50%;">
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1" style="height: 25px;">
                                        <div class="progress-bar ${progressColor}" role="progressbar" 
                                             style="width: ${opt.percentage}%"
                                             aria-valuenow="${opt.percentage}" aria-valuemin="0" aria-valuemax="100">
                                            ${opt.percentage}%
                                        </div>
                                    </div>
                                    <span class="ms-2"><strong>${opt.percentage}%</strong></span>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                html += '</tbody></table></div></div></div>';
            }

            // For text-based questions
            if (report.answers && Array.isArray(report.answers) && report.answers.length > 0) {
                html += `
                    <div class="row">
                        <div class="col-12">
                            <h6 class="mb-3">Text Responses (${report.answers.length})</h6>
                            <div class="list-group" style="max-height: 400px; overflow-y: auto;">
                `;

                report.answers.forEach((answer, index) => {
                    html += `
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Response ${index + 1}</h6>
                            </div>
                            <p class="mb-1">${answer}</p>
                        </div>
                    `;
                });

                html += '</div></div></div>';
            }

            html += '</div>';

            return html;
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
            document.getElementById('question-label-filter')
                .addEventListener('change', () => {
                    if (!this.tableInstance) return;

                    const value = document.getElementById('question-label-filter').value;

                    if (!value) {
                        this.tableInstance.column(3).search('').draw();
                    } else {
                        this.tableInstance.column(3).search(value, false, false).draw();
                    }
                });
        },

         initQuestionTypeFilter() {
            document.getElementById('question-type-filter')
                .addEventListener('change', () => {
                    if (!this.tableInstance) return;

                    const value = document.getElementById('question-type-filter').value;

                    if (!value) {
                        this.tableInstance.column(2).search('').draw();
                    } else {
                        this.tableInstance.column(2).search(value, false, false).draw();
                    }
                });
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