<?php $__env->startSection('title', 'Reports'); ?>

<?php $__env->startSection('css'); ?>
 <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/jquery.dataTables.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select.bootstrap5.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/sweetalert2.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/autoFill.bootstrap5.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/keyTable.bootstrap5.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/buttons.bootstrap5.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/fixedHeader.bootstrap5.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/responsive.bootstrap5.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/rowReorder.bootstrap5.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/flatpickr/flatpickr.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select/bootstrap-select.min.css')); ?>">

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
        #survey-report-table th:nth-child(6),
    #survey-report-table td:nth-child(6) {
        display: none;
    }/

        /* Force left alignment for entire table */
     table.dataTable th,
    table.dataTable td {
        text-align: left !important;
    }


    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main_content'); ?>
<section x-data="ReportComponent()">
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3><?php echo e(__('Report')); ?></h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"> 
                            <svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-home')); ?>"></use>
                            </svg></a>
                        </li>
                        <li class="breadcrumb-item"><?php echo e(__('ECC')); ?></li>
                        <li class="breadcrumb-item active"><?php echo e(__('Report')); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="surveyResultsModal" tabindex="-1" role="dialog" aria-labelledby="surveyResultsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="surveyResultsModalLabel"><?php echo e(__("Question Results")); ?></h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="surveyResultsContent">
                    <!-- Content will be dynamically loaded here -->
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden"><?php echo e(__("Loading...")); ?></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                                
                                <div class="col-auto">
                                    <select id="question-label-filter" class="form-select w-auto">
                                        <option value="">All question labels</option>
                                    </select>
                                    
                                </div>

                                <div class="col-auto">
                                    <select id="question-type-filter" class="form-select w-auto">
                                        <option value=""><?php echo e(__("All question Type")); ?></option>
                                    </select>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="product-report">
                            <div class="recent-table table-responsive custom-scrollbar">
                                <div x-show="currentView === 'survey'" x-cloak>
                                    <!-- Loader - outside the table -->
                                    <div x-show="isLoadingSurvey" class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden"><?php echo e(__("Loading...")); ?></span>
                                        </div>
                                        <p class="mt-2"><?php echo e(__("Loading surveys...")); ?></p>
                                    </div>
                                    
                                    <!-- Table - only show when NOT loading -->
                                    <div x-show="!isLoadingSurvey">
                                        <table class="table" id="survey-table">
                                            <thead>
                                                <tr>
                                                    <th> <span class="c-o-light f-w-600">S/N</span></th>
                                                    <th> <span class="c-o-light f-w-600"><?php echo e(__("Title")); ?></span></th>
                                                    <th> <span class="c-o-light f-w-600"><?php echo e(__("Frequency")); ?></span></th>
                                                    <th> <span class="c-o-light f-w-600"><?php echo e(__("Patients")); ?></span></th>
                                                    <th> <span class="c-o-light f-w-600"><?php echo e(__("Questions")); ?></span></th>
                                                    <th> <span class="c-o-light f-w-600"><?php echo e(__("Action")); ?></span></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- DataTables will populate this -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                               <div x-show="currentView === 'patients'" x-cloak>
                                <!-- Loader overlay - outside the table -->
                                <div x-show="isLoadingPatients" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden"><?php echo e(__("Loading...")); ?></span>
                                    </div>
                                    <p class="mt-2"><?php echo e(__("Loading patients...")); ?></p>
                                </div>
    
                                <!-- Table - only show when NOT loading -->
                                <div x-show="!isLoadingPatients">
                                    <table class="table" id="survey-patients-table">
                                        <thead>
                                            <tr>
                                                <th><span class="c-o-light f-w-600">S/N</span></th>
                                                <th><span class="c-o-light f-w-600"><?php echo e(__("Patient ID")); ?></span></th>
                                                <th><span class="c-o-light f-w-600"><?php echo e(__("Full Name")); ?></span></th>
                                                <th><span class="c-o-light f-w-600"><?php echo e(__("Email")); ?></span></th>
                                                <th><span class="c-o-light f-w-600"><?php echo e(__("Status")); ?></span></th>
                                                <th><span class="c-o-light f-w-600"><?php echo e(__("Action")); ?></span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                                
                               <div x-show="currentView === 'report'" x-cloak>
                                <!-- Loading overlay -->
                                <div x-show="isLoadingReport" class="text-center py-5">
                                    <div class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden"><?php echo e(__("Loading...")); ?></span>
                                        </div>
                                        <p class="mt-2"><?php echo e(__("Loading report...")); ?></p>
                                    </div>
                                </div>
                                
                            <div x-show="!isLoadingReport">
                                  <!-- Table -->
                                <table class="table" id="survey-report-table">
                                    <thead>
                                        <tr>
                                            <th><span class="c-o-light f-w-600">S/N</span></th>
                                            <th><span class="c-o-light f-w-600"><?php echo e(__("Question")); ?></span></th>
                                            <th><span class="c-o-light f-w-600"><?php echo e(__("Responses")); ?></span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- DataTables will populate this -->
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
    </div><!-- Container-fluid Ends-->


</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('assets/js/chart/apex-chart/apex-chart.js')); ?>"></script>

<script>
function ReportComponent() {
    return {
        currentView: 'survey', // 'survey' | 'patients' | 'report'

        isLoadingSurvey: false,
        isLoadingPatients: false,
        isLoadingReport: false,
        isLoading: false,

        surveyData: [],
        surveyPatientsData: [],
        surveyReportData: [],

        selectedSurveyId: null,
        selectedUserId: null,

        init() {
            this.$nextTick(() => {
                this.initQuestionLabelFilter();
                this.initQuestionTypeFilter();
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
                this.isLoadingSurvey = true;
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

                        const frequencies = [...new Set(
                        this.surveyData
                            .map(q => q.frequency)
                            .filter(Boolean)
                        )];

                        this.populateFilter(frequencies);

                    }
                } catch (error) {
                    console.error("Error fetching assessment:", error);
                } finally {
                    this.isLoadingSurvey = false;
                }
            },

            async fetchSurveyUsers(surveyId) {
                //his.currentView = 'patients';
                this.selectedSurveyId = surveyId;
                this.isLoadingPatients = true;
                this.surveyPatientsData = [];

                try {
                    const res = await fetch(`/reports/get-survey-users/${surveyId}`);
                    const json = await res.json();

                    if (json.success) {
                        this.surveyPatientsData = json.data;
                        this.$nextTick(() => this.renderSurveyUsers());
                    }
                } catch (e) {
                    console.error(e);
                } finally {
                    this.isLoadingPatients = false;
                }
            },


            async fetchSurveyAnswers(userId, surveyId) {
                //this.currentView = 'report';
                this.selectedUserId = userId;
                this.isLoadingReport = true;
                this.surveyReportData = [];

                try {
                    const res = await fetch(`/reports/get-survey-answers/${surveyId}/user/${userId}`);
                    const json = await res.json();

                    if (json.success) {
                        this.surveyReportData = json.data;
                        this.$nextTick(() => this.renderSurveyReportTable());
                    }
                } catch (e) {
                    console.error(e);
                } finally {
                    this.isLoadingReport = false;
                }
            },

            showSurveyQuestionResults(questionData) {
            // Generate modal content
            const modalContent = this.generateSurveyResultsContent(questionData);
            
            // Update modal content
            document.getElementById('surveyResultsContent').innerHTML = modalContent;
            document.getElementById('surveyResultsModalLabel').textContent = 'Results: ' + (questionData.question || 'Question');
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('surveyResultsModal'));
            modal.show();
        },

        generateSurveyResultsContent(questionData) {
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
                                <h3 class="text-white">${report.min}</h3>
                                <p class="mb-0 text-white">Minimum</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h3 class="text-white">${report.max}</h3>
                                <p class="mb-0 text-white">Maximum</p>
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
                                            <th class="text-end">Percentage</th>
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

            formatSurveyExportData(report, type) {
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

                // Add text answers
                if (report.answers && Array.isArray(report.answers) && report.answers.length > 0) {
                    if (result) result += ' | ';
                    result += `Text Responses: ${report.answers.join('; ')}`;
                }

                return result || '-';
            },
            
       renderSurveyDataTable() {

            this.destroyTable('survey-table');
                // Destroy existing instance if it exists
                // if ($.fn.DataTable.isDataTable('#survey-table')) {
                //     $('#survey-table').DataTable().destroy();
                // }

                this.tableInstance = $("#survey-table").DataTable({
                    processing: true,
                    //serverSide: false,
                    data: this.surveyData,
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
                            data: 'title',
                            width: '45%',
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
                            width: '10%',
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
                            width: '10%',
                            orderable: false,
                            render: function(data, type, row) {
                                return `<span class="badge badge-light-dark">${data} patient${data !== 1 ? 's' : ''}</span>`;
                            }
                        },
                        {
                            data: 'questions_count',
                            width: '10%',
                            orderable: false,
                            render: function(data, type, row) {
                                return `${data} question${data !== 1 ? 's' : ''}`;
                            }
                        },
                        {
                            data: 'id',
                            width: '25%',
                            orderable: false,
                            render: function(data, type, row) {
                                return `
                                    <div class="btn-group btn-group-sm" role="group">
                                         <button 
                                        class="btn btn-outline-primary btn-sm view-survey"
                                        data-id="${data}">
                                        <i class="fa fa-eye"> View</i>
                                    </button>
                                    </div>
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
                                { 
                                    extend: "copy", 
                                    text: "<?php echo e(__('Copy')); ?>",
                                    className: "btn btn-outline-primary btn-sm", 
                                    attr: { title: "<?php echo e(__('Copy to clipboard')); ?>", class: "btn btn-outline-primary btn-sm" } 
                                },
                                { 
                                    extend: "csv", 
                                    text: "<?php echo e(__('CSV')); ?>",
                                    className: "btn btn-outline-primary btn-sm", 
                                    attr: { title: "<?php echo e(__('Export as CSV')); ?>", class: "btn btn-outline-primary btn-sm" } 
                                },
                                { 
                                    extend: "excel", 
                                    text: "<?php echo e(__('Excel')); ?>",
                                    className: "btn btn-outline-primary btn-sm", 
                                    attr: { title: "<?php echo e(__('Export as Excel')); ?>", class: "btn btn-outline-primary btn-sm" } 
                                },
                                { 
                                    extend: "pdf", 
                                    text: "<?php echo e(__('PDF')); ?>",
                                    className: "btn btn-outline-primary btn-sm", 
                                    attr: { title: "<?php echo e(__('Export as PDF')); ?>", class: "btn btn-outline-primary btn-sm" } 
                                },
                                { 
                                    extend: "print", 
                                    text: "<?php echo e(__('Print')); ?>",
                                    className: "btn btn-outline-primary btn-sm", 
                                    attr: { title: "<?php echo e(__('Print')); ?>", class: "btn btn-outline-primary btn-sm" } 
                                },
                                // {
                                //     text: '<i class="fa fa-plus"></i> Add Survey',
                                //     className: 'btn btn-primary btn-sm ms-2',
                                //     action: () => { window.location.href = '/surveys/create'; }
                                // }
                            ],
                        },
                        topEnd: {
                            search: { placeholder: "<?php echo e(__('Search here')); ?>..." }
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
            this.selectedSurveyId = surveyId;

            // if ($.fn.DataTable.isDataTable('#survey-patients-table')) {
            //     $('#survey-patients-table').DataTable().destroy();
            // }

            // Switch view FIRST
            this.currentView = 'patients';

            // Clear old data immediately
            this.surveyPatientsData = [];

            // Show loader immediately
            this.isLoadingPatients = true;

            // Force Alpine DOM update
            await this.$nextTick();

            // Now fetch
            await this.fetchSurveyUsers(surveyId);
        },


         renderSurveyUsers() {
                const alpine = this;
                this.destroyTable('survey-patients-table');
                // Destroy existing instance if it exists
                // if ($.fn.DataTable.isDataTable('#survey-patients-table')) {
                //     $('#survey-patients-table').DataTable().destroy();
                // }

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
                            width: '25%',
                            render: row =>
                                `${row.first_name ?? ''} ${row.last_name ?? ''}`.trim() || '-'
                        },
                        {
                            data: 'email',
                            defaultContent: '-'
                        },
                        {
                        data: 'pivot.status',
                            width: '20%',
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
                                    attr: { class: "btn btn-outline-primary btn-sm" },
                                    action: function () {
                                        alpine.backToSurvey(); // ✅ ALWAYS works
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
                const alpine = this;
                this.destroyTable('survey-report-table');
                // if ($.fn.DataTable.isDataTable('#survey-report-table')) {
                //     $('#survey-report-table').DataTable().destroy();
                // }

                this.reportTableInstance = $("#survey-report-table").DataTable({
                    processing: true,
                    order: [[0, 'asc']],
                    data: this.surveyReportData.questions,
                    columnDefs: [
                        { targets: '_all', className: 'text-start' }
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
                            width: '25%',
                            render: function(data, type, row) {
                                if (type === 'export') {
                                    return data ? data.replace(/<br>/g, ' ').replace(/\n/g, ' ') : '-';
                                }
                                return data ? data.replace(/\n/g, '<br>') : '-';
                            }
                        },
                        {
                            data: 'report', // Point this to the report object
                            title: 'Answer',
                            width: '45%',
                            render: (report) => {
                                if (!report) return '<span class="text-muted">No Data</span>';

                                // 1. Check for the specific 'selected_option' field (Single Choice/Dropdown)
                                if (report.selected_options) {
                                    return `<div class="">${report.selected_options}</div>`;
                                }

                                // 2. Check for text answers array (Open Text)
                                if (report.answers && report.answers.length > 0) {
                                    return report.answers[0]; 
                                }

                                // 5. Fallback
                                return '<span class="text-muted">No Answer Provided</span>';
                            }
                        },
                        // {
                        //     data: null,
                        //     title: 'Action',
                        //     width: '12%',
                        //     className: 'text-center',
                        //     orderable: false,
                        //     exportable: false,
                        //     render: function(data, type, row) {
                        //         if (type === 'export') {
                        //             return '';
                        //         }
                        //         return `<button class="btn btn-sm btn-primary view-survey-results-btn" data-row-index="${row.id || ''}">
                        //             <i class="fa fa-eye"></i> View
                        //         </button>`;
                        //     }
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
                                    text: '<i class="fa fa-arrow-left me-1"></i> Back to Patients',
                                    className: "btn btn-outline-primary btn-sm me-2",
                                    attr: { class: "btn btn-outline-primary btn-sm" },
                                    action: () => {
                                        alpine.backToPatients()
                                    }
                                },
                                { 
                                    extend: "copy", 
                                    className: "btn btn-outline-primary btn-sm",
                                    attr: { class: "btn btn-outline-primary btn-sm" },
                                    exportOptions: {
                                        columns: [0, 1, 2], // Include Results Summary
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
                                    title: `${this.surveyReportData?.survey_title || 'Survey'}_Report_${new Date().toISOString().split('T')[0]}`,
                                    attr: { class: "btn btn-outline-primary btn-sm" },
                                    exportOptions: {
                                        columns: [0, 1, 2]
                                    }
                                },
                                { 
                                    extend: "excel", 
                                    className: "btn btn-outline-primary btn-sm",
                                    title: `${this.surveyReportData?.survey_title || 'Survey'} - Report`,
                                    attr: { class: "btn btn-outline-primary btn-sm" },
                                    exportOptions: {
                                        columns: [0, 1, 2]
                                    },
                                    customize: function(xlsx) {
                                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                        // Wrap text for Question and Results Summary columns
                                        $('row c[r^="B"]', sheet).attr('s', '55');
                                        $('row c[r^="F"]', sheet).attr('s', '55');
                                    }
                                },
                                { 
                                    extend: "pdf", 
                                    className: "btn btn-outline-primary btn-sm",
                                    title: `${this.surveyReportData?.survey_title || 'Survey'} - Report`,
                                    orientation: 'landscape',
                                    pageSize: 'A4',
                                    attr: { class: "btn btn-outline-primary btn-sm" },
                                    exportOptions: {
                                        columns: [0, 1, 2]
                                    },
                                    customize: function(doc) {
                                        doc.content[1].table.widths = ['5%', '20%', '10%', '10%', '8%', '47%'];
                                        
                                        doc.content.splice(0, 1, {
                                            text: `${alpine.surveyReportData?.survey_title || 'Survey'} - Report - ${new Date().toLocaleDateString()}`,
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
                                    title: `${this.surveyReportData?.survey_title || 'Survey'} - Report`,
                                    attr: { class: "btn btn-outline-primary btn-sm" },
                                    exportOptions: {
                                        columns: [0, 1, 2]
                                    },
                                    customize: function(win) {
                                        $(win.document.body)
                                            .css('font-size', '9pt')
                                            .prepend(
                                                `<h1 style="text-align:center;">${alpine.surveyReportData?.survey_title || 'Survey'} - Report</h1>` +
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
                $('#survey-report-table tbody').on('click', '.view-survey-results-btn', function(e) {
                    e.stopPropagation();
                    const table = alpine.reportTableInstance;
                    const tr = $(this).closest('tr');
                    const row = table.row(tr);
                    const rowData = row.data();
                    
                    alpine.showSurveyQuestionResults(rowData);
                });
            },


            async viewSurveyReport(userId, surveyId) {
                this.selectedUserId = userId;
                this.selectedSurveyId = surveyId;

                // Switch view FIRST
                this.currentView = 'report';

                // Clear old data
                this.surveyReportData = null;

                // Show loader immediately
                this.isLoadingReport = true;

                await this.$nextTick();

                await this.fetchSurveyAnswers(userId, surveyId);
            },



            backToSurvey() {
                // if ($.fn.DataTable.isDataTable('#survey-patients-table')) {
                //     $('#survey-patients-table').DataTable().destroy();
                // }
                this.destroyTable('survey-patients-table');
                this.currentView = 'survey';
            },

            backToPatients() {
                this.destroyTable('survey-report-table');
                // if ($.fn.DataTable.isDataTable('#survey-report-table')) {
                //     $('#survey-report-table').DataTable().destroy();
                // }
                this.isLoadingPatients = true;
                this.currentView = 'patients';
                if (this.selectedSurveyId) {
                    this.fetchSurveyUsers(this.selectedSurveyId);
                }
            },



        showQuestionResults(questionData) {
            this.selectedQuestion = questionData;
            
            // Generate modal content
            const modalContent = this.generateResultsContent(questionData);
            
            // Update modal content
            document.getElementById('questionResultsContent').innerHTML = modalContent;
            document.getElementById('surveyResultsModalLabel').textContent = 'Results: ' + (questionData.question || 'Question');
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('surveyResultsModal'));
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

        populateFilter(frequencies) {
            const select = document.getElementById('question-label-filter');
            select.innerHTML = `<option value=""><?php echo e(__('All frequencies')); ?></option>`;

            frequencies.sort().forEach(item => {
                const opt = document.createElement('option');
                opt.value = item;
                opt.textContent = item;
                select.appendChild(opt);
            });
        },
        
        populateQuestionTypeFilter(types) {
            const select = document.getElementById('question-type-filter');
            select.innerHTML = `<option value=""><?php echo e(__('All question Types')); ?></option>`;

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

<script src="<?php echo e(asset('assets/js/counter/custom-counter1.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/tooltip-init.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/dataTables.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/dataTables.select.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/select.bootstrap5.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.autoFill.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/autoFill.bootstrap5.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.keyTable.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/keyTable.bootstrap5.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.buttons.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.bootstrap5.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.colVis.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.fixedHeader.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/fixedHeader.bootstrap5.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/vfs_fonts.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.print.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.responsive.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/responsive.bootstrap5.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.rowReorder.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/rowReorder.bootstrap5.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/flat-pickr/flatpickr.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/flat-pickr/custom-flatpickr.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/flat-pickr/moment.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/modalpage/validation-modal.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/select/bootstrap-select.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ecc-pain-new\resources\views/reports/surveys-report.blade.php ENDPATH**/ ?>