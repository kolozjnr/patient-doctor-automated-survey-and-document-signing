<?php $__env->startSection('title', 'Single Surve Details'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/owlcarousel.css')); ?>">

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
<?php $__env->stopSection(); ?>

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

<?php $__env->startSection('main_content'); ?>
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3><?php echo e(__('Survey Details')); ?></h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"> <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-home')); ?>"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item"><?php echo e(__('Surveys')); ?></li>
                        <li class="breadcrumb-item active"><?php echo e(__('Survey Details')); ?></li>
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
                                <?php echo e(__('Survey Details')); ?>

                            </h6>
                            <span class="badge bg-white text-info mx-2">
                                <?php echo e(__('1 Survey')); ?>

                            </span>
                        </div>
                        <div class="card-body p-4">
                            <div class="survey-info">
                                <div class="info-item mb-3 pb-3 border-bottom">
                                    <label class="text-muted small mb-1 d-block"><?php echo e(__('Title')); ?></label>
                                    <h5 class="mb-0"><?php echo e($survey->title); ?></h5>
                                </div>
                                
                                <div class="info-item mb-3 pb-3 border-bottom">
                                    <label class="text-muted small mb-1 d-block"><?php echo e(__('Frequency')); ?></label>
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($survey->frequency === 'custom' && $survey->custom_reoccurrence): ?>
                                            <?php
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
                                            ?>
                                        <?php else: ?>
                                            <?php echo e(ucfirst($survey->frequency)); ?>

                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </span>

                                </div>
                                
                                <div class="info-item mb-3 pb-3 border-bottom">
                                    <label class="text-muted small mb-1 d-block"><?php echo e(__('Description')); ?></label>
                                    <p class="mb-0 fw-semibold"><?php echo e($survey->description ?? 'No description'); ?></p>
                                </div>
                                
                                <div class="info-item mb-3 pb-3 border-bottom">
                                    <label class="text-muted small mb-1 d-block"><?php echo e(__('Created At')); ?></label>
                                    <p class="mb-0"><?php echo e($survey->created_at->diffForHumans()); ?></p>
                                </div>

                                <div class="info-item mb-3 pb-3 border-bottom">
                                    <label class="text-muted small mb-1 d-block"><?php echo e(__('Recievers')); ?></label>
                                    <p class="mb-0 fw-semibold"><?php echo e($survey->users->count() ?? 0); ?> <?php echo e(__('Patients')); ?></p>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Survey Patients Card -->
                

                <!-- Questions Card -->
                <div class="col-xxl-7 col-lg-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-white px-2">
                                <i class="fas fa-question-circle me-2 text-white"></i>
                                <?php echo e(__('Questions')); ?>

                            </h6>
                            <span class="badge bg-white text-info mx-2">
                                <?php echo e($survey->surveyQuestions->count() ?? 0); ?> <?php echo e(__('Questions')); ?>

                            </span>
                        </div>
                        <div class="card-body p-4">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($survey->surveyQuestions && $survey->surveyQuestions->count() > 0): ?>
                                <div class="questions-list">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $survey->surveyQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $surveyQuestion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="question-item mb-3 p-1 bg-light rounded question-row">
                                            <div class="d-flex align-items-center w-100 question-content">
                                                
                                                <span class="badge bg-warning text-dark me-2 flex-shrink-0">
                                                    Q<?php echo e($index + 1); ?>

                                                </span>

                                                <!-- Question text -->
                                                <p class="mb-0 fw-semibold question-text"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="<?php echo e($surveyQuestion->question->question ?? 'No question text'); ?>">
                                                    <?php echo e($surveyQuestion->question->question ?? 'No question text'); ?>

                                                </p>

                                                <!-- Question type -->
                                                <small class="text-muted ms-3 flex-shrink-0 question-type">
                                                    <?php echo e(ucfirst($surveyQuestion->question->type ?? $surveyQuestion->type ?? 'text')); ?>

                                                </small>

                                                <!-- Icon -->
                                                

                                            </div>
                                        </div>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-question fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0"><?php echo e(__('No questions added yet.')); ?></p>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="container-fluid product-report-wrapper" x-data="SurveyPatientsTable(<?php echo e($survey->id); ?>)">
            <div class="col-12">
                <div class="card">
                    <div class="card-body px-0 pt-0">
                        
                        <div class="top-body">
                            <div class="row common-f-start g-sm-3 g-2">
                                <div class="col-auto">
                                    <label class="form-label"></label>
                                </div>
                                <div class="col-auto">
                                    <select id="treatment-filter" class="form-select w-auto hidden" style="display: non;">
                                        
                                    </select>

                                    
                                </div>
                            </div>
                        </div>
                        <div class="product-report">
                            <div class="recent-table table-responsive custom-scrollbar">
                                <table class="table" id="view-survey-patient-table">
                                    <thead>
                                        <tr>
                                            <th> <span class="c-o-light f-w-600">S/N</span></th>
                                            <th> <span class="c-o-light f-w-600"><?php echo e(__("Name")); ?></span></th>
                                            <th> <span class="c-o-light f-w-600"><?php echo e(__("Email")); ?></span>
                                            </th>
                                            <th> <span class="c-o-light f-w-600"><?php echo e(__("Action")); ?></span></th>
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
        
    </div><!-- Container-fluid Ends-->
<?php $__env->stopSection(); ?>

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
                                { extend: "copy", text: "<?php echo e(__('Copy')); ?>", className: "btn btn-outline-primary btn-sm", attr: { title: "Copy to clipboard", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "csv", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as CSV", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "excel", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as Excel", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "pdf", className: "btn btn-outline-primary btn-sm", attr: { title: "Export as PDF", class: "btn btn-outline-primary btn-sm" } },
                                { extend: "print", text: "<?php echo e(__('Print')); ?>", className: "btn btn-outline-primary btn-sm", attr: { title: "Print", class: "btn btn-outline-primary btn-sm" } },
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

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('assets/js/touchspin_2/custom_touchspin.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/owlcarousel/owl.carousel.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/ecommerce.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/form-validation-custom.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/custom_zoom_magnifier.js')); ?>"></script>

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

<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ecc-pain-new\resources\views/survey/show.blade.php ENDPATH**/ ?>