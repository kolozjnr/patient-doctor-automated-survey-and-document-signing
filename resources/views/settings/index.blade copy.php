@extends('layouts.simple.master')

@section('title', 'Patient information')

@section('css')
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select.bootstrap5.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/autoFill.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/keyTable.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/fixedHeader.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/rowReorder.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/flatpickr/flatpickr.min.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select/bootstrap-select.min.css') }}"> --}}

@endsection

@section('main_content')
                <section x-data="settingsComponent()">
 <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3>{{__('Settings')}}</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"> <svg class="stroke-icon">
                                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                            </svg></a></li>
                                    <li class="breadcrumb-item">{{__('Patients')}}</li>
                                    <li class="breadcrumb-item active">{{__('Question cahrt')}}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
      
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <div class="user-profile">
                        <div class="row"><!-- user profile first-style start-->
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
                                                            href="#survey-response" role="tab" @click="chartSettings()"
                                                            aria-controls="survey-response" aria-selected="false">
                                                            <div class="nav-rounded">
                                                                <div class="product-icons"><i
                                                                        class="fa-solid fa-gears"></i></div>
                                                            </div>
                                                            <div class="product-tab-content">
                                                                <h6>{{__('Charts setup')}}</h6>
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
                                                                     <form class="row g-3 needs-validation" novalidate @submit.prevent="submitQuestionCharts">
                                                                <!-- Parent Row -->
                                                                <div class="row g-2">
                                                                   <template x-for="(field, index) in questionCharts" :key="index">
                                                                    {{-- <pre x-text="JSON.stringify(questionCharts, null, 2)"></pre> --}}
                                                                        <div class="col-md-10">
                                                                            <div class="row align-items-end g-2 col-12">

                                                                                <!-- Question -->
<div class="col-6">
    <label class="form-label">Question</label>
    <select class="form-select" x-model="field.question_id">
        <option value="">Select Question</option>
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

<!-- Chart Type -->
<div class="col-4">
    <label class="form-label">Chart Type</label>
    <select class="form-select" x-model="field.chart_type">
        <option value="">Select Type</option>
        <template x-for="(label, key) in charts" :key="key">
            <option 
                :value="String(key)"
                :selected="String(field.chart_type) === String(key)"
                x-text="label">
            </option>
        </template>
    </select>
</div>

                                                                                <!-- Actions -->
                                                                                <div class="col-2">
                                                                                    <label class="form-label d-block">&nbsp;</label>
                                                                                    <div class="d-flex gap-1">

                                                                                        <button type="button"
                                                                                                class="btn btn-outline-primary btn-sm"
                                                                                                @click="addNewField">
                                                                                            <i class="fa-solid fa-plus"></i>
                                                                                        </button>

                                                                                        <button type="button"
                                                                                                class="btn btn-outline-danger btn-sm"
                                                                                                @click="removeField(index)"
                                                                                                x-show="questionCharts.length > 1">
                                                                                            <i class="fa-solid fa-trash"></i>
                                                                                        </button>

                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                </div>

                                                                <!-- Add New Field Button -->
                                                                <div class="col-md-12">
                                                                    <button type="button" 
                                                                            class="btn btn-outline-secondary btn-sm mb-3"
                                                                            @click="addNewField">
                                                                        <i class="fa-solid fa-plus me-2"></i>Add Another Field
                                                                    </button>
                                                                </div>
                                                                
                                                                <!-- Submit Button -->
                                                                <div class="col-md-12">
                                                                    <button class="btn btn-primary" type="submit" :disabled="isLoadingQuestionChart">
                                                                        <span x-show="isLoadingQuestionChart" class="spinner-border spinner-border-sm me-2" 
                                                                            role="status" aria-hidden="true">
                                                                        </span>
                                                                        <span x-text="isLoadingQuestionChart ? 'Saving...' : 'Save Settings'"></span>
                                                                    </button>
                                                                </div>
                                                            </form>
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
                                                                        <h5>Bellscale Assesments</h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
function settingsComponent() {

    return {

        questions: @json($questions),
        charts: @json($charts),
        questionCharts: @json($settings),

// questionCharts: @json(
//     $settings->map(function($s){
//         return [
//             'id' => $s->id,
//             'question_id' => (string) $s->question_id,
//             'chart_type' => (string) $s->chart_type
//         ];
//     })->values()
// ),


        isLoadingQuestionChart: false,
        isLoading: false,

        init() {

            if (this.questionCharts.length === 0) {
                this.addNewField();
            }
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
    }
}
</script>

<script src="{{ asset('assets/js/counter/custom-counter1.js') }}"></script>
<script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
{{-- <script src="{{ asset('assets/js/common-avatar-change.js') }}"></script> --}}


    <script src="{{ asset('assets/js/flat-pickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/js/flat-pickr/custom-flatpickr.js') }}"></script>
    <script src="{{ asset('assets/js/flat-pickr/moment.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/flat-pickr/custom-range-btn.js') }}"></script> --}}
    <script src="{{ asset('assets/js/modalpage/validation-modal.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/select/bootstrap-select.min.js') }}"></script> --}}
@endsection