<?php $__env->startSection('title', 'Settings'); ?>

<?php $__env->startSection('css'); ?>
    
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
        /* ─── Sidebar Nav Tabs ─────────────────────────────────────────── */
        .sidebar-left-icons.nav-pills {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
 
        .sidebar-left-icons .nav-item {
            width: 100%;
        }
 
        .sidebar-left-icons .nav-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            color: #6b7280;
            transition: background 0.2s ease, color 0.2s ease;
            white-space: nowrap;
            width: 100%;
        }
 
        .sidebar-left-icons .nav-link:hover {
            background-color: #f3f4f6;
            color: #111827;
        }
 
        .sidebar-left-icons .nav-link.active {
            background-color: #e8f0fe;
            color: #1a56db;
        }
 
        .sidebar-left-icons .nav-rounded {
            flex-shrink: 0;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.5rem;
            background-color: #f9fafb;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e5e7eb;
        }
 
        .sidebar-left-icons .nav-link.active .nav-rounded {
            background-color: #dbeafe;
            border-color: #93c5fd;
        }
 
        .sidebar-left-icons .product-icons i {
            font-size: 0.9rem;
        }
 
        .sidebar-left-icons .product-tab-content h6 {
            margin: 0;
            font-size: 0.875rem;
            font-weight: 500;
            line-height: 1;
        }
 
        /* ─── Tab Content Card ─────────────────────────────────────────── */
        .tab-content .card {
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
 
        .tab-content .card-body {
            padding: 1.5rem !important;
        }
 
        /* ─── Section Header ───────────────────────────────────────────── */
        .settings-section-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
 
        .settings-section-header h5 {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
            color: #111827;
        }
 
        .settings-section-header i {
            color: #6b7280;
            font-size: 0.95rem;
        }
 
        /* ─── Chart Settings Form ──────────────────────────────────────── */
        .chart-field-row {
            display: flex;
            align-items: flex-end;
            gap: 0.75rem;
            padding: 0.85rem 1rem;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            margin-bottom: 0.65rem;
            transition: border-color 0.2s;
        }
 
        .chart-field-row:hover {
            border-color: #93c5fd;
        }
 
        .chart-field-row .field-index {
            flex-shrink: 0;
            width: 1.6rem;
            height: 1.6rem;
            border-radius: 50%;
            background: #e5e7eb;
            color: #6b7280;
            font-size: 0.72rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.2rem; /* aligns with input bottom */
        }
 
        .chart-field-row .field-question {
            flex: 1 1 auto;
            min-width: 0;
        }
 
        .chart-field-row .field-chart-type {
            flex: 0 0 180px;
        }
 
        .chart-field-row .field-actions {
            flex-shrink: 0;
            display: flex;
            gap: 0.4rem;
            align-items: flex-end;
        }
 
        .chart-field-row .form-label {
            font-size: 0.78rem;
            font-weight: 500;
            color: #6b7280;
            margin-bottom: 0.3rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
 
        .chart-field-row .form-select {
            font-size: 0.875rem;
        }
 
        /* Empty state */
        .fields-empty-hint {
            text-align: center;
            padding: 2rem;
            color: #9ca3af;
            font-size: 0.875rem;
            border: 2px dashed #e5e7eb;
            border-radius: 0.5rem;
        }
 
        /* ─── Form Footer ──────────────────────────────────────────────── */
        .form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 1rem;
            margin-top: 0.5rem;
            border-top: 1px solid #e5e7eb;
        }
 
        .form-footer .btn-add-field {
            font-size: 0.825rem;
        }
 
        /* ─── Wearables Grid ───────────────────────────────────────────── */
        .wearable-card {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            overflow: hidden;
        }
 
        .wearable-card .card-body {
            padding: 1rem !important;
        }
 
        .wearable-card .card-title {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            margin-bottom: 0.75rem;
        }
 
        /* ─── Loader ───────────────────────────────────────────────────── */
        .loading-overlay {
            text-align: center;
            padding: 3rem 1rem;
        }
 
        .loading-overlay p {
            margin-top: 0.75rem;
            font-size: 0.875rem;
            color: #9ca3af;
        }
 
        /* ─── Dummy data notice ────────────────────────────────────────── */
        .dummy-notice {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.78rem;
            color: #dc2626;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 0.375rem;
            padding: 0.3rem 0.65rem;
            margin-bottom: 1rem;
        }
 
        /* ─── Responsive ───────────────────────────────────────────────── */
        @media (max-width: 768px) {
            .chart-field-row {
                flex-wrap: wrap;
            }
 
            .chart-field-row .field-chart-type {
                flex: 1 1 140px;
            }
 
            .chart-field-row .field-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }
    </style>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('main_content'); ?>
<section x-data="settingsComponent()">
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3><?php echo e(__("Settings")); ?></h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"> <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-home')); ?>"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">ECC</li>
                        <li class="breadcrumb-item active"><?php echo e(__("Settings")); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

       
    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog"
        aria-labelledby="general_scores" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="general_scores"><?php echo e(__('General scores')); ?></h4><button
                        class="btn-close py-0" type="button" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body dark-modal">
                    
                    
                </div>
            </div>
        </div>
    </div>
 
    
    <div class="container-fluid">
        <div class="user-profile">
            <div class="row">
                <div class="col-12">
                    <div class="row scope-bottom-wrapper user-profile-wrapper">
 
                        
                        <div class="col-xxl-3 user-xl-25 col-xl-4 col-lg-3 box-col-4">
                            <div class="card" style="position: sticky; top: 1.5rem;">
                                <div class="card-body p-3">
                                    <p class="text-muted mb-2" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.05em; font-weight:600;">
                                        Settings
                                    </p>
                                    <ul class="sidebar-left-icons nav nav-pills" id="add-product-pills-tab" role="tablist">
 
                                        <li class="nav-item">
                                            <a class="nav-link active"
                                               id="survey-response-tab"
                                               data-bs-toggle="pill"
                                               href="#survey-response"
                                               role="tab"
                                               @click="chartSettings()"
                                               aria-controls="survey-response"
                                               aria-selected="true">
                                                <div class="nav-rounded">
                                                    <div class="product-icons"><i class="fa-solid fa-gears"></i></div>
                                                </div>
                                                <div class="product-tab-content">
                                                    <h6><?php echo e(__('Charts Setup')); ?></h6>
                                                </div>
                                            </a>
                                        </li>
 
                                        <li class="nav-item ">
                                            <a class="nav-link"
                                               id="survey-settings-tab"
                                               data-bs-toggle="pill"
                                               href="#survey-settings"
                                               role="tab"
                                               @click="fetchSurveySettingsData()">
                                                <div class="nav-rounded">
                                                    <div class="product-icons"><i class="fa-solid fa-list-check"></i></div>
                                                </div>
                                                <div class="product-tab-content">
                                                    <h6><?php echo e(__('Survey Settings')); ?></h6>
                                                </div>
                                            </a>
                                        </li>
 
                                        <li class="nav-item d-none">
                                            <a class="nav-link"
                                               id="bell-scale-tab"
                                               data-bs-toggle="pill"
                                               href="#bell-scale"
                                               role="tab"
                                               @click="fetchBellscaleAssesment()"
                                               aria-controls="bell-scale"
                                               aria-selected="false">
                                                <div class="nav-rounded">
                                                    <div class="product-icons"><i class="fa-regular fa-bell"></i></div>
                                                </div>
                                                <div class="product-tab-content">
                                                    <h6><?php echo e(__('Bellscale')); ?></h6>
                                                </div>
                                            </a>
                                        </li>
 
                                        <li class="nav-item d-none">
                                            <a class="nav-link"
                                               id="wearables-tab"
                                               data-bs-toggle="pill"
                                               href="#wearables"
                                               role="tab"
                                               @click="initCharts()">
                                                <div class="nav-rounded">
                                                    <div class="product-icons"><i class="fa-solid fa-watch"></i></div>
                                                </div>
                                                <div class="product-tab-content">
                                                    <h6><?php echo e(__('Smart Wearables')); ?></h6>
                                                </div>
                                            </a>
                                        </li>
 
                                    </ul>
                                </div>
                            </div>
                        </div><!-- /sidebar -->
 
                        
                        <div class="col-xxl-9 user-xl-75 col-xl-8 col-lg-9 box-col-8e">
                            <div class="tab-content" id="add-product-pills-tabContent">
 
                                
                                <div class="tab-pane fade show active"
                                     id="survey-response"
                                     role="tabpanel"
                                     aria-labelledby="survey-response-tab">
                                    <div class="card">
 
                                        <div class="settings-section-header">
                                            <i class="fa-solid fa-gears"></i>
                                            <h5>Charts Setup</h5>
                                        </div>
 
                                        <div class="card-body">
                                            <form novalidate @submit.prevent="submitQuestionCharts">
 
                                                
                                                <div class="mb-3">
                                                    <template x-for="(field, index) in questionCharts" :key="index">
                                                        <div class="chart-field-row">
 
                                                            
                                                            <div class="field-index" x-text="index + 1"></div>
 
                                                            
                                                            <div class="field-question">
                                                                <label class="form-label">Question</label>
                                                                <select class="form-select" x-model="field.question_id">
                                                                    <option value="">— Select a question —</option>
                                                                    <template x-for="(label, id) in questions" :key="id">
                                                                        <option
                                                                            :value="String(id)"
                                                                            :selected="String(field.question_id) === String(id)"
                                                                            :disabled="isQuestionDisabled(id, index)"
                                                                            x-text="label">
                                                                        </option>
                                                                    </template>
                                                                </select>
                                                            </div>
 
                                                            
                                                            <div class="field-chart-type">
                                                                <label class="form-label">Chart Type</label>
                                                                <select class="form-select" x-model="field.chart_type">
                                                                    <option value="">— Select type —</option>
                                                                    <template x-for="(label, key) in charts" :key="key">
                                                                        <option
                                                                            :value="String(key)"
                                                                            :selected="String(field.chart_type) === String(key)"
                                                                            x-text="label">
                                                                        </option>
                                                                    </template>
                                                                </select>
                                                            </div>
 
                                                            
                                                            <div class="field-actions">
                                                                <button type="button"
                                                                        class="btn btn-outline-danger btn-sm"
                                                                        title="Remove row"
                                                                        @click="removeField(index)"
                                                                        x-show="questionCharts.length > 1">
                                                                    <i class="fa-solid fa-trash-can"></i>
                                                                </button>
                                                            </div>
 
                                                        </div>
                                                    </template>
                                                </div>
 
                                                
                                                <div class="form-footer">
                                                    <button type="button"
                                                            class="btn btn-outline-secondary btn-sm btn-add-field"
                                                            @click="addNewField">
                                                        <i class="fa-solid fa-plus me-1"></i>
                                                        Add Field
                                                    </button>
 
                                                    <button class="btn btn-primary px-4"
                                                            type="submit"
                                                            :disabled="isLoadingQuestionChart">
                                                        <span x-show="isLoadingQuestionChart"
                                                              class="spinner-border spinner-border-sm me-2"
                                                              role="status"
                                                              aria-hidden="true"></span>
                                                        <span x-text="isLoadingQuestionChart ? 'Saving…' : 'Save Settings'"></span>
                                                    </button>
                                                </div>
 
                                            </form>
                                        </div>
                                    </div>
                                </div><!-- /charts setup tab -->
 
                                
                                <div class="tab-pane fade"
                                     id="survey-settings"
                                     role="tabpanel"
                                     aria-labelledby="survey-settings-tab">
                                    <div class="card">
                                        <div class="settings-section-header">
                                            <i class="fa-solid fa-list-check"></i>
                                            <h5>Survey Settings</h5>
                                        </div>
                                        <div class="card-body">
                                            <form novalidate @submit.prevent="submitSurveyCharts">
 
                                                
                                                <div class="mb-3">
                                                    <template x-for="(field, index) in surveyCharts" :key="index">
                                                        <div class="chart-field-row">
 
                                                            
                                                            <div class="field-index" x-text="index + 1"></div>
                                                            
                                                           <div class="field-question">
                                                                <label class="form-label">Survey</label>
                                                                <select class="form-select"
                                                                        x-model="field.survey_id"
                                                                        @change="onSurveyChange(index)">
                                                                    <option value="">— Select a survey —</option>
                                                                    <template x-for="(label, id) in surveys" :key="id">
                                                                        <option
                                                                            :value="String(id)"
                                                                            :selected="String(field.survey_id) === String(id)"
                                                                            
                                                                            x-text="label">
                                                                        </option>
                                                                    </template>
                                                                </select>
                                                            </div>

                                                            
                                                            <div class="field-question">
                                                                <label class="form-label">Question</label>
                                                                <select class="form-select"
                                                                        x-model="field.question_id"
                                                                        :disabled="!field.survey_id || loadingQuestions[index]">
                                                                    <option value=""
                                                                            x-text="loadingQuestions[index] ? 'Loading…' : '— Select a question —'">
                                                                    </option>
                                                                    <template x-for="(label, id) in questionsForRow(index)" :key="id">
                                                                        <option
                                                                            :value="String(id)"
                                                                            :selected="String(field.question_id) === String(id)"
                                                                            :disabled="isSurveyQuestionDisabled(id, index)"
                                                                            x-text="label">
                                                                        </option>
                                                                    </template>
                                                                </select>
                                                            </div>
 
                                                            
                                                            <div class="field-chart-type">
                                                                <label class="form-label">Chart Type</label>
                                                                <select class="form-select" x-model="field.chart_type">
                                                                    <option value="">— Select type —</option>
                                                                    <template x-for="(label, key) in charts" :key="key">
                                                                        <option
                                                                            :value="String(key)"
                                                                            :selected="String(field.chart_type) === String(key)"
                                                                            x-text="label">
                                                                        </option>
                                                                    </template>
                                                                </select>
                                                            </div>
 
                                                            
                                                            <div class="field-actions">
                                                                <button type="button"
                                                                        class="btn btn-outline-danger btn-sm"
                                                                        title="Remove row"
                                                                        @click="removeSurveyField(index)"
                                                                        x-show="surveyCharts.length > 1">
                                                                    <i class="fa-solid fa-trash-can"></i>
                                                                </button>
                                                            </div>
 
                                                        </div>
                                                    </template>
                                                </div>
 
                                                
                                                <div class="form-footer">
                                                    <button type="button"
                                                            class="btn btn-outline-secondary btn-sm btn-add-field"
                                                            @click="addNewSurveyField">
                                                        <i class="fa-solid fa-plus me-1"></i>
                                                        Add Field
                                                    </button>
 
                                                    <button class="btn btn-primary px-4"
                                                            type="submit"
                                                            :disabled="isLoadingQuestionChart">
                                                        <span x-show="isLoadingQuestionChart"
                                                              class="spinner-border spinner-border-sm me-2"
                                                              role="status"
                                                              aria-hidden="true"></span>
                                                        <span x-text="isLoadingQuestionChart ? 'Saving…' : 'Save Settings'"></span>
                                                    </button>
                                                </div>
 
                                            </form>
                                            
                                        </div>
                                    </div>
                                </div><!-- /general tab -->
 
                                
                                <div class="tab-pane fade"
                                     id="bell-scale"
                                     role="tabpanel"
                                     aria-labelledby="bell-scale-tab">
                                    <div class="card">
                                        <div class="settings-section-header">
                                            <i class="fa-regular fa-bell"></i>
                                            <h5>Bellscale Assessments</h5>
                                        </div>
                                        <div class="card-body">
                                            
                                            <p class="text-muted mb-0" style="font-size:0.875rem;">
                                                No bellscale data available.
                                            </p>
                                        </div>
                                    </div>
                                </div><!-- /bellscale tab -->
 
                                
                                <div class="tab-pane fade"
                                     id="wearables"
                                     role="tabpanel"
                                     aria-labelledby="wearables-tab">
                                    <div class="card">
 
                                        <div class="settings-section-header" style="justify-content: space-between;">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fa-solid fa-watch"></i>
                                                <h5 class="mb-0">Smart Wearables</h5>
                                            </div>
                                            <div x-show="isLoading"
                                                 class="spinner-border spinner-border-sm text-primary"
                                                 role="status"
                                                 aria-label="Loading"></div>
                                        </div>
 
                                        <div class="card-body">
 
                                            
                                            <div x-show="isLoading" class="loading-overlay">
                                                <div class="loader-box">
                                                    <div class="loader-3"></div>
                                                </div>
                                                <p>Fetching latest wearable data…</p>
                                            </div>
 
                                            
                                            <div x-show="!isLoading" x-transition>
 
                                                <div class="dummy-notice">
                                                    <i class="fa-solid fa-flask"></i>
                                                    Dummy data — for testing only
                                                </div>
 
                                                <div class="row g-3">
 
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="wearable-card card shadow-none">
                                                            <div class="card-body">
                                                                <p class="card-title">Heart Rate</p>
                                                                <div id="hrChart" style="min-height:150px;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
 
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="wearable-card card shadow-none">
                                                            <div class="card-body">
                                                                <p class="card-title">SpO₂</p>
                                                                <div id="spo2Chart" style="min-height:150px;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
 
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="wearable-card card shadow-none">
                                                            <div class="card-body">
                                                                <p class="card-title">Sleep Stages</p>
                                                                <div id="sleepChart" style="min-height:150px;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
 
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="wearable-card card shadow-none">
                                                            <div class="card-body">
                                                                <p class="card-title">HRV (RMSSD)</p>
                                                                <div id="hrvChart" style="min-height:150px;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
 
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="wearable-card card shadow-none">
                                                            <div class="card-body">
                                                                <p class="card-title">Temperature</p>
                                                                <div id="tempChart" style="min-height:150px;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
 
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="wearable-card card shadow-none">
                                                            <div class="card-body">
                                                                <p class="card-title">Steps</p>
                                                                <div id="stepsChart" style="min-height:150px;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
 
                                                </div>
                                            </div><!-- /content -->
 
                                        </div>
                                    </div>
                                </div><!-- /wearables tab -->
 
                            </div><!-- /tab-content -->
                        </div><!-- /col -->
 
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /container-fluid -->
 
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('assets/js/chart/apex-chart/apex-chart.js')); ?>"></script>

<script>
    document.addEventListener('alpine:init', () => {
    
    Alpine.data('settingsComponent', () => ({

        questions: <?php echo json_encode($questions, 15, 512) ?>,
        surveys: <?php echo json_encode($surveys, 15, 512) ?>,
        charts: <?php echo json_encode($charts, 15, 512) ?>,
        questionCharts: <?php echo json_encode($settings, 15, 512) ?>,
        surveyCharts: <?php echo json_encode($surveySettings, 15, 512) ?>,


        isLoadingQuestionChart: false,
        isLoading: false,
        questionCache: {},
        loadingQuestions: {},

        init() {

            if (this.questionCharts.length === 0) {
                this.addNewField();
            }
           // fetchSurveySettingsData()
        },

        chartSettings (){
            if (this.questionCharts.length === 0) {
                this.addNewField();
            }
        },
       

        addNewSurveyField() {
            this.surveyCharts.push({
                id: null,
                survey_id: '',
                question_id: '',
                chart_type: ''
            });
        },

        removeSurveyField(index) {
            this.surveyCharts.splice(index, 1);
        },

        addNewField() {
            this.questionCharts.push({
                id: null,
                question_id: '',
                chart_type: ''
            });
        },

        removeField(index) {
            this.questionCharts.splice(index, 1);
        },

        isQuestionDisabled(questionId, currentIndex) {
            return this.questionCharts.some((field, index) => {
                return index !== currentIndex &&
                       field.question_id == questionId;
            });
        },

     

// Call this once in init() for pre-saved rows
fetchSurveySettingsData() {
    if (this.surveyCharts.length === 0) {
        this.addNewSurveyField();
    } else {
        this.surveyCharts.forEach((field, index) => {
            if (field.survey_id) {
                this.fetchQuestionsForSurvey(field.survey_id, index);
            }
        });
    }
},

async fetchQuestionsForSurvey(surveyId, index) {
    if (!surveyId) return;
    if (this.questionCache[surveyId]) return;

    this.loadingQuestions[index] = true;

    try {
        const res = await fetch(`/settings/${surveyId}/questions`, {
            headers: { 'Accept': 'application/json' }
        });
        const data = await res.json();
        this.questionCache[surveyId] = data.questions; // { id: label }
    } catch (e) {
        console.error('Failed fetching questions for survey', surveyId, e);
    } finally {
        this.loadingQuestions[index] = false;
    }
},

onSurveyChange(index) {
    const surveyId = this.surveyCharts[index].survey_id;
    this.surveyCharts[index].question_id = '';
    this.fetchQuestionsForSurvey(surveyId, index);
},

questionsForRow(index) {
    const surveyId = this.surveyCharts[index].survey_id;
    return surveyId ? (this.questionCache[surveyId] || {}) : {};
},

// isSurveyQuestionDisabled(questionId, currentIndex) {
//     const surveyId = this.surveyCharts[currentIndex].survey_id;
//     return this.surveyCharts.some((field, index) => {
//         return index !== currentIndex
//             && field.survey_id == surveyId
//             && field.question_id == questionId;
//     });
// },

    async submitSurveyCharts() {
        this.isLoadingQuestionChart = true;

        try {
            const response = await fetch('/settings/survey-charts/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ charts: this.surveyCharts })
            });

            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Error saving');

            if (data.charts) {
                this.surveyCharts = data.charts;
            }

            notify('success', data.message || 'Settings updated successfully');
        } catch (error) {
            alert(error.message);
        } finally {
            this.isLoadingQuestionChart = false;
        }
    },



      async submitQuestionCharts() {
            this.isLoadingQuestionChart = true;

            try {
                const response = await fetch('/settings/question-charts/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ charts: this.questionCharts })
                });

                const data = await response.json();

                if (!response.ok) throw new Error(data.message || 'Error saving');

                // CRITICAL: Update local state with fresh data from DB
                // This converts those 'null' IDs into real Database IDs
                if (data.charts) {
                    this.questionCharts = data.charts;
                }

                notify('success', data.message || 'Settings updated successfully');
            } catch (error) {
                alert(error.message);
            } finally {
                this.isLoadingQuestionChart = false;
            }
        },

        

    }))
});

    // THis method has it fucking way of adding an unseen unstyled and a fucking space under the header. it took me days to catch the criminal
// function settingsComponent() {

//     return {

//         questions: <?php echo json_encode($questions, 15, 512) ?>,
//         charts: <?php echo json_encode($charts, 15, 512) ?>,
//         questionCharts: <?php echo json_encode($settings, 15, 512) ?>,


//         isLoadingQuestionChart: false,
//         isLoading: false,

//         init() {

//             if (this.questionCharts.length === 0) {
//                 this.addNewField();
//             }
//         },

//         addNewField() {
//             this.questionCharts.push({
//                 id: null,
//                 question_id: '',
//                 chart_type: ''
//             });
//         },

//         removeField(index) {
//             this.questionCharts.splice(index, 1);
//         },

//         isQuestionDisabled(questionId, currentIndex) {
//             return this.questionCharts.some((field, index) => {
//                 return index !== currentIndex &&
//                        field.question_id == questionId;
//             });
//         },

//       async submitQuestionCharts() {
//             this.isLoadingQuestionChart = true;

//             try {
//                 const response = await fetch('/settings/question-charts/store', {
//                     method: 'POST',
//                     headers: {
//                         'Content-Type': 'application/json',
//                         'Accept': 'application/json',
//                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//                     },
//                     body: JSON.stringify({ charts: this.questionCharts })
//                 });

//                 const data = await response.json();

//                 if (!response.ok) throw new Error(data.message || 'Error saving');

//                 // CRITICAL: Update local state with fresh data from DB
//                 // This converts those 'null' IDs into real Database IDs
//                 if (data.charts) {
//                     this.questionCharts = data.charts;
//                 }

//                 notify('success', data.message || 'Settings updated successfully');
//             } catch (error) {
//                 alert(error.message);
//             } finally {
//                 this.isLoadingQuestionChart = false;
//             }
//         },
//     }
// }
</script>

<script src="<?php echo e(asset('assets/js/counter/custom-counter1.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/tooltip-init.js')); ?>"></script>



    <script src="<?php echo e(asset('assets/js/flat-pickr/flatpickr.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/flat-pickr/custom-flatpickr.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/flat-pickr/moment.js')); ?>"></script>
    
    <script src="<?php echo e(asset('assets/js/modalpage/validation-modal.js')); ?>"></script>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ecc-pain-new\resources\views/settings/index.blade.php ENDPATH**/ ?>