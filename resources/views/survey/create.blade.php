@extends('layouts.simple.master')

@section('title', 'Add Survey')

@section('css')
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>{{ isset($survey) ? __("Edit Survey") : __("Create New Survey") }}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.survey.index') }}"> <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">{{__('Surveys')}}</li>
                        <li class="breadcrumb-item active">  {{ isset($survey) ? __("Edit Survey") : __("Create New Survey") }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div><!-- Container-fluid starts-->
    <div class="container-fluid" x-data="SurveyComponent()">
        <div class="edit-profile">
            <div class="row">
                <div class="col-xl-12">
                    <form class="" @submit.prevent="saveOrUpdateSurvey()" method="POST">
                        <div class="card">
                            {{-- <div class="card-header">
                                <h5 class="card-title">Edit Profile</h5>
                                <div class="card-options"><a class="card-options-collapse" href="#"
                                        data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a
                                        class="card-options-remove" href="#" data-bs-toggle="card-remove"><i
                                            class="fe fe-x"></i></a></div>
                            </div> --}}
                            <div class="card-body">
                                <div class="row custom-input">
                                    <div class="col-xxl-4 box-col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="Title">{{__('Title')}}</label>
                                            <input class="form-control" id="title" x-model="formData.title" type="text" placeholder="{{__('Title')}}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-xxl-4 box-col-12">
                                        <div class="mb-3"><label class="form-label"
                                                for="repetition">{{__('Repetition')}}</label><select class="form-control btn-square" x-model="formData.repetition" id="repetition">
                                                <option selected>{{__('Choose Repetition')}}</option>
                                                <option value="once">{{__('Once')}}</option>
                                                <option value="daily">{{__('Daily')}}</option>
                                                <option value="weekly">{{__('Weekly')}}</option>
                                                <option value="monthly">{{__('Monthly')}}</option>
                                                <option value="yearly">{{__('Yearly')}}</option>
                                                <option value="every_week">{{__('Every week (Mon-Fri)')}}</option>
                                                <option value="custom">{{__('Custom')}}</option>
                                            </select></div>
                                    </div>
                                    <div class="col-sm-4 col-xxl-4 box-col-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="send_to">{{__('Send Survey To')}}</label>
                                                <select class="form-control btn-square" x-model="formData.send_to" id="send_to">
                                                <option selected value="">{{__('Choose Recipient')}}</option>
                                                    
                                                <option value="department">{{__('Department')}}</option>
                                                <option value="individual_patient">{{__('Individual Patient')}}</option>
                                                 <option value="treatment_label">{{__('Treatments')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4" x-show="formData.send_to === 'department'">
                                        <label class="form-label">{{__("Department")}}</label>
                                        <select class="form-control"
                                                x-model="formData.department_id">
                                            <option value="">{{__("Select department")}}</option>
                                            @foreach(\App\Models\Department::all() as $department)
                                                <option value="{{ $department->id }}">
                                                    {{ ucfirst($department->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- <div class="col-sm-6 col-md-4" x-show="showPatientGroup()">
                                        <div class="mb-3">
                                            <label class="form-label" for="customFirstName">{{__('Patient')}}</label>
                                            <select class="form-control btn-square" x-model="formData.patientGroup" id="patient">
                                                <option value="patient_group">{{__('Patients Group')}}</option>
                                            </select>
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-sm-6 col-md-4" x-show="showIndividualPatient()">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Individual Patients') }}</label>
                                            <button
                                                type="button"
                                                class="btn btn-primary w-100"
                                                @click="addPatients = !addPatients">
                                                {{ __('Add Patients') }}
                                            </button>
                                        </div>
                                    </div> --}}

                                    
                                    <div class="col-sm-6 col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="add_question">{{__("Add Question")}}</label>
                                                <button class="btn btn-primary w-100" name="add_question" type="button" @click="addQuestion = !addQuestion">{{__("Add Question")}}</button>
                                            </div>
                                    </div>
                                    
                                    
                                    <div class="col-md-4">
                                        <div><label class="form-label" for="aboutMeDesc">{{__('Description')}}</label>
                                            <textarea class="form-control" cols="1" rows="1" id="description" x-model="formData.description" rows="4" placeholder="{{__('Enter your description')}}"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn btn-primary" type="submit" :disabled="isLoading">
                                <span
                                    x-show="isLoading"
                                    class="spinner-border spinner-border-sm me-2"
                                    role="status"
                                    aria-hidden="true">
                                </span>

                                <span x-text="isLoading ? 'Saving...' : (surveyId ? 'Update Survey' : 'Create Survey')">
                                    {{ isset($survey) ? '__("Update Survey")' : '__("Create Survey")' }}
                                </span>
                            </button>
                            </div>

                            
                        </div>

                        <div class="edit-profile">
                            <div class="row">
                                 <div class="col-xl-4" x-show="showCustomReoccurring()">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">{{__('Custom Recurring')}}</h5>
                                            <div class="card-options"><a class="card-options-collapse" href="#"
                                                    data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a
                                                    class="card-options-remove" href="#" data-bs-toggle="card-remove"><i
                                                        class="fe fe-x"></i></a></div>
                                        </div>
                                        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                            {{-- Repeat Every --}}
                                            <div class="row mb-4">
                                                <div class="col-md-6 col-6">
                                                    <label class="form-label">{{__("Repeat every")}}</label>
                                                    <input type="number" class="form-control" id="repeatEvery" name="repeat_interval" x-model="formData.repeat_interval" value="1" min="1">
                                                </div>
                                                <div class="col-md-6 col-6">
                                                    <label class="form-label d-none d-md-block invisible">{{__('Interval Unit')}}</label>
                                                    <select class="form-select" id="repeatUnit" x-model="formData.repeat_unit" name="repeat_unit">
                                                        <option value="day">{{__("day")}}</option>
                                                        <option value="week">{{{__("week")}}}</option>
                                                        <option value="month">{{__("month")}}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Repeat On --}}
                                       <div class="mb-4" x-show="ShowRepeatOn()">
                                            <label class="form-label mb-2">{{__("Repeat on")}}</label>

                                            <div class="d-flex flex-wrap gap-2">
                                                @php $days = ['S', 'M', 'T', 'W', 'T', 'F', 'S']; @endphp

                                                @foreach ($days as $index => $day)
                                                    <input
                                                        type="checkbox"
                                                        class="btn-check"
                                                        :id="'day-' + {{ $index }}"
                                                        :value="{{ $index }}"
                                                        x-model="formData.repeat_days"
                                                    >

                                                    <label
                                                        class="btn btn-outline-primary"
                                                        :for="'day-' + {{ $index }}"
                                                        :class="{ 'active': formData.repeat_days.includes({{ $index }}) }"
                                                        style="width: 36px;"
                                                    >
                                                        {{ $day }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>

                                            {{-- Ends --}}
                                            <div class="mb-3">
                                                 <label class="form-label mb-2">{{__("Ends")}}</label>
                                                    <div class="row g-2 align-items-center">
                                                        <!-- Never -->
                                                        <div class="col-md-3 col-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="ends" id="endsNever" value="never" x-model="formData.ends">
                                                                <label class="form-check-label" for="endsNever">{{__('Never')}}</label>
                                                            </div>
                                                        </div>

                                                        <!-- On -->
                                                        <div class="col-md-3 col-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="ends" id="endsOn" value="on" x-model="formData.ends">
                                                                <label class="form-check-label" for="endsOn">{{__('On')}}</label>
                                                            </div>
                                                        </div>

                                                        <!-- Date -->
                                                        <div class="col-md-6">
                                                            <input type="date" class="form-control" name="ends_on" x-model="formData.ends_on" :disabled="!isEndsOn()">
                                                        </div>

                                                        <!-- After -->
                                                        <div class="col-md-3 col-6 mt-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="ends" id="endsAfter" value="after" x-model="formData.ends">
                                                                <label class="form-check-label" for="endsAfter">{{__("After")}}</label>
                                                            </div>
                                                        </div>

                                                        <!-- Number -->
                                                        <div class="col-md-3 col-6 mt-2">
                                                            <input type="number" class="form-control" x-model="formData.ends_after" name="ends_after" min="1" :disabled="!isEndsAfter()">
                                                        </div>
                                                        <div class="col-md-6 mt-2">
                                                            <span>{{__("occurrences")}}</span>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4" x-show="showIndividualPatient()">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">{{__('Select Patient')}}</h5>
                                            <div class="card-options"><a class="card-options-collapse" href="#"
                                                    data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a
                                                    class="card-options-remove" href="#" data-bs-toggle="card-remove"><i
                                                        class="fe fe-x"></i></a></div>
                                        </div>
                                        <div class="card-body" x-init="fetchPatients()" style="max-height: 400px; overflow-y: auto;">
                                            <!-- Search -->
                                            <div class="input-group mb-3">
                                                <input type="text"
                                                    class="form-control"
                                                    placeholder="Search for patient..."
                                                    x-model="searchQuery">
                                            </div>


                                            <!-- Patient List -->
                                            <div class="list-group">
                                                <template x-for="patient in filteredPatients()" :key="patient.id">
                                                    <div
                                                        class="list-group-item d-flex align-items-center patient-item"
                                                        :class="{ 'active-patient': formData.selectedPatients.includes(patient.id) }"
                                                    >
                                                        <div class="form-check form-check-inline checkbox checkbox-dark mb-0 w-100">
                                                            <input
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                :id="'patient-' + patient.id"
                                                                :value="patient.id"
                                                                x-model="formData.selectedPatients"
                                                            >

                                                            <label
                                                                class="form-check-label ms-2"
                                                                :for="'patient-' + patient.id"
                                                                x-text="patientDisplayName(patient)"
                                                            ></label>
                                                        </div>
                                                    </div>
                                                </template>


                                                <!-- Loading -->
                                                <div class="text-muted text-center py-2" x-show="loading">
                                                    {{__("Loading patients...")}}
                                                </div>

                                                <!-- No result -->
                                                <div class="text-muted text-center py-2"
                                                    x-show="!loading && filteredPatients().length === 0">
                                                    {{__("No patients found")}}
                                                    <br>
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-sm btn-outline-secondary mt-2"
                                                        @click="fetchPatients()"
                                                        :disabled="loading">
                                                        <i class="fe fe-refresh-cw me-1"></i>
                                                        {{__("Reload")}}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-xl-4" x-show="showTreatmentLabel()">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">{{__('Select Label')}}</h5>
                                            <div class="card-options"><a class="card-options-collapse" href="#"
                                                    data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a
                                                    class="card-options-remove" href="#" data-bs-toggle="card-remove"><i
                                                        class="fe fe-x"></i></a></div>
                                        </div>
                                        <div class="card-body" x-init="fetchTreatments()" style="max-height: 400px; overflow-y: auto;">
                                            <!-- Search -->
                                            <div class="input-group mb-3">
                                                <input type="text"
                                                    class="form-control"
                                                    placeholder="Search for label..."
                                                    x-model="searchTreatment">
                                            </div>


                                            <!-- Patient List -->
                                            <div class="list-group">
                                                <template x-for="treatment in filteredTreatment()" :key="treatment.id">
                                                    <div
                                                        class="list-group-item d-flex align-items-center patient-item"
                                                        :class="{ 'active-treatment': formData.selectedTreatments.includes(treatment.id) }"
                                                    >
                                                        <div class="form-check form-check-inline checkbox checkbox-dark mb-0 w-100">
                                                            <input
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                :id="'treatment-' + treatment.id"
                                                                :value="treatment.id"
                                                                x-model="formData.selectedTreatments"
                                                            >

                                                            <label
                                                                class="form-check-label ms-2"
                                                                :for="'treatment-' + treatment.id"
                                                                x-text="treatmentDisplayName(treatment)"
                                                            ></label>
                                                        </div>
                                                    </div>
                                                </template>


                                                <!-- Loading -->
                                                <div class="text-muted text-center py-2" x-show="loading">
                                                    {{__("Loading treatments...")}}
                                                </div>
                                                <!-- No result -->
                                                    <div class="text-muted text-center py-2"
                                                        x-show="!loading && filteredTreatment().length === 0">
                                                        {{__("No Treatment found")}}
                                                        <br>
                                                        <button 
                                                            type="button" 
                                                            class="btn btn-sm btn-outline-secondary mt-2"
                                                            @click="fetchTreatments()"
                                                            :disabled="loading">
                                                            <i class="fe fe-refresh-cw me-1"></i>
                                                            {{__("Reload")}}
                                                        </button>
                                                    </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                <div class="col-xl-4" x-show="addQuestion" x-transition>
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">{{__('Select Question')}}</h5>
                                            <div class="card-options"><a class="card-options-collapse" href="#"
                                                    data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a
                                                    class="card-options-remove" href="#" data-bs-toggle="card-remove"><i
                                                        class="fe fe-x"></i></a></div>
                                        </div>
                                        <div class="card-body" x-init="fetchQuestions()" style="max-height: 400px; overflow-y: auto;">

                                            <!-- Search -->
                                            <div class="input-group mb-3">
                                                <input type="text"
                                                    class="form-control"
                                                    placeholder="{{__('Search for question')}}..."
                                                    x-model="searchQuestion">
                                            </div>

                                            <!-- Question List -->
                                            <div class="list-group">

                                            <template x-for="question in filteredQuestions()" :key="question.id">
                                                <div
                                                    class="list-group-item d-flex align-items-center patient-item"
                                                    :class="{ 'active-patient': formData.selectedQuestions.includes(question.id) }"
                                                >
                                                    <div class="form-check form-check-inline checkbox checkbox-dark mb-0 w-100">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            :id="'question-' + question.id"
                                                            :value="question.id"
                                                            x-model="formData.selectedQuestions"
                                                        >

                                                        <label
                                                            class="form-check-label ms-2"
                                                            :for="'question-' + question.id"
                                                            x-text="question.question"
                                                        ></label>
                                                    </div>
                                                </div>
                                            </template>


                                                <!-- Loading -->
                                                <div class="text-muted text-center py-2" x-show="questionsLoading">
                                                    {{__("Loading questions...")}}
                                                </div>
                                                
                                                <!-- No result -->
                                                <div class="text-muted text-center py-2"
                                                    x-show="!questionsLoading && filteredQuestions().length === 0">
                                                    {{__("No questions found")}}
                                                    <br>
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-sm btn-outline-secondary mt-2"
                                                        @click="fetchQuestions()"
                                                        :disabled="questionsLoading">
                                                        <i class="fe fe-refresh-cw me-1"></i>
                                                        {{__("Reload")}}
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div><!-- Container-fluid Ends-->
    @if(isset($survey))
<script>
    window.surveyId = {{ $survey->id }};
</script>
@endif
@endsection

@section('scripts')

<script>
     document.addEventListener('alpine:init', () => {
    Alpine.data('SurveyComponent', () => ({
        searchQuery: '',
        searchTreatment: '',
        searchQuestion: '',
        patients: [],
        treatments: [],
        questions: [],
        loading: false,
        questionsLoading: false,
        addQuestion: false,
        saving: false,
        errors: {},
        surveyId: null,
        isLoading: false,

        formData: {
            title: '',
            repetition: '',
            send_to: '',
            patient: '',
            description: '',
            ends: 'never',
            ends_on: '',
            ends_after: 1,
            repeat_interval: 1,
            repeat_unit: 'day',
            repeat_days: [],
            selectedPatients: [],
            selectedTreatments: [],
            selectedQuestions: [],
            department_id: null,
        },

        ShowRepeatOn() {
            this.formData.repeat_days = [];
            return this.formData.repetition === 'custom' && this.formData.repeat_unit === 'week';
        },

       async init() {
            if (!Array.isArray(this.formData.repeat_days)) {
                this.formData.repeat_days = [];
            }
            // Check if we're editing (surveyId might be passed from blade)
            this.surveyId = window.surveyId || null;

            // Load survey data if editing
            if (this.surveyId) {
                await this.loadSurvey();
            }
        },

        computedCustomRecurrence() {
            return {
                repeat: {
                    interval: this.formData.repeat_interval,
                    unit: this.formData.repeat_unit,
                },
                repeat_on: this.formData.repeat_unit === 'week'
                    ? this.formData.repeat_days
                    : null,
                ends: {
                    type: this.formData.ends,
                    on: this.formData.ends === 'on' ? this.formData.ends_on : null,
                    after: this.formData.ends === 'after' ? this.formData.ends_after : null,
                }
            };
        },

        async loadSurvey() {
            try {
                const response = await fetch(`/surveys/${this.surveyId}/get-edit-data`, {
                    headers: {
                        'Accept': 'application/json',
                    }
                });
                const result = await response.json();
                
                if (response.ok && result.success) {
                    const survey = result.data;

                    console.log('Loaded survey data:', survey);
                    
                    // Populate form data
                    this.formData.title = survey.title || '';
                    this.formData.description = survey.description || '';
                    this.formData.repetition = survey.frequency || '';
                    this.formData.send_to = survey.send_to || '';
                    console.log('Loaded survey send_to:', this.formData.send_to);
                    
                    // Load selected patients
                    if (survey.users && Array.isArray(survey.users)) {
                        this.formData.selectedPatients = survey.users.map(p => p.id);
                    }

                    //load labels
                    if(survey.labels && Array.isArray(survey.labels)) {
                        this.formData.selectedTreatments = survey.labels.map(l => l.id);
                    }
                    
                    // Load selected questions
                    if (survey.survey_questions && Array.isArray(survey.survey_questions)) {
                        this.formData.selectedQuestions = survey.survey_questions.map(q => q.question_id);
                    }
                    
                    // Load custom recurrence data if exists
                    if (survey.custom_reoccurrence) {
                        const custom = survey.custom_reoccurrence;
                        
                        this.formData.repeat_interval = custom.repeat?.interval || 1;
                        this.formData.repeat_unit = custom.repeat?.unit || 'day';
                        this.formData.repeat_days = custom.repeat_on || [];
                        
                        this.formData.ends = custom.ends?.type || 'never';
                        this.formData.ends_on = custom.ends?.on || '';
                        this.formData.ends_after = custom.ends?.after || 1;
                    }
                }
            } catch (error) {
                console.error('Error loading survey:', error);
                notify('error', 'Failed to load survey data');
            }
        },

        async saveOrUpdateSurvey() {
            
            this.isLoading = true,
            this.saving = true;
            this.errors = {};

            try {
                const payload = {
                    title: this.formData.title,
                    description: this.formData.description,
                    repetition: this.formData.repetition,
                    send_to: this.formData.send_to,
                    patient_ids: [],
                    question_ids: this.formData.selectedQuestions,
                    custom_reoccurrence: this.formData.repetition === 'custom' 
                        ? this.computedCustomRecurrence() 
                        : null,
                };

                console.log('Form data before processing:', this.formData);

                // Handle individual patients
                if (this.formData.send_to === 'individual_patient') {
                    payload.patient_ids = this.formData.selectedPatients || [];
                }
                // Department
                if (this.formData.send_to === 'department') {
                    payload.department_id = this.formData.department_id;
                }
                //Handles treatment
                if (this.formData.send_to === 'treatment_label') {
                    payload.treatment_ids = this.formData.selectedTreatments || [];
                }

                // Include survey ID for updates
                if (this.surveyId) {
                    payload.id = this.surveyId;
                }

                // console.log('Payload to be sent:', payload);
                // return;

                const url = '/surveys/save-or-update';
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    notify('success', result.message || (this.surveyId ? 'Survey updated successfully' : 'Survey created successfully'));

                    // Redirect or reset form
                    setTimeout(() => {
                        window.location.href = result.redirect || '/surveys';
                    }, 3000);
                } else {
                    if (result.errors) {
                        this.errors = result.errors;
                    }
                    throw new Error(result.message || 'Failed to save survey');
                }
            } catch (error) {
                console.error('Error saving survey:', error);
                notify('error', error.message || 'An error occurred while saving the survey');
            } finally {
                this.saving = false;
                this.isLoading = false;
            }
        },

        // patientDisplayName(patient) {
        //     const first = patient.first_name ? patient.first_name + ' ' : '';
        //     const last = patient.last_name?.trim();

        //     if (first || last) {
        //         return `${first ?? ''} ${last ?? ''}`.trim();
        //     }

        //     return patient.email;
        // },

        patientDisplayName(patient) {
            const first = patient.first_name ? patient.first_name + ' ' : '';
            const last = patient.last_name?.trim();

            let name = '';

            if (first || last) {
                name = `${first ?? ''}${last ?? ''}`.trim();
            } else {
                name = patient.email;
            }
            return `${name} - ${patient.patient_id}`;
        },

        treatmentDisplayName(treatment) {
            const name = treatment.label?.trim();

            if (name) {
                return `${name ?? ''}`.trim();
            }

            return treatment.label;
        },

        fetchPatients() {
            this.loading = true;
            fetch('/surveys/fetch-patients')
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        this.patients = res.data;
                    }
                })
                .catch(error => {
                    console.error('Error fetching patients:', error);
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        fetchTreatments() {
            this.loading = true;
            fetch('/surveys/fetch-treatments')
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        this.treatments = res.data;
                        console.log("Treatments",this.treatments)
                    }
                })
                .catch(error => {
                    console.error('Error fetching treatments:', error);
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        fetchQuestions() {
            this.questionsLoading = true;
            fetch('/surveys/fetch-questions')
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        this.questions = res.data;
                    }
                })
                .catch(error => {
                    console.error('Error fetching questions:', error);
                })
                .finally(() => this.questionsLoading = false);
        },

        filteredPatients() {
            const query = this.searchQuery?.toLowerCase() || '';
            
            const filtered = !query
                ? this.patients
                : this.patients.filter(patient =>
                    patient.email?.toLowerCase().includes(query) ||
                    patient.first_name?.toLowerCase().includes(query) ||
                    patient.last_name?.toLowerCase().includes(query) ||
                    `${patient.first_name} ${patient.last_name}`.toLowerCase().includes(query)
                );

            return filtered.sort((a, b) => {
                const aChecked = this.formData.selectedPatients.includes(a.id);
                const bChecked = this.formData.selectedPatients.includes(b.id);
                return bChecked - aChecked;
            });
        },

        filteredTreatment() {
            const filtered = !this.searchTreament
                ? this.treatments
                : this.treatments.filter(treatment =>
                    treatment.label?.toLowerCase().includes(this.searchTreament.toLowerCase())
                );

            return filtered.sort((a, b) => {
                const aChecked = this.formData.selectedTreatments.includes(a.id);
                const bChecked = this.formData.selectedTreatments.includes(b.id);
                return bChecked - aChecked;
            });
        },

        filteredQuestions() {
            const filtered = !this.searchQuestion
                ? this.questions
                : this.questions.filter(q =>
                    q.question.toLowerCase().includes(this.searchQuestion.toLowerCase())
                );

            return filtered.sort((a, b) => {
                const aChecked = this.formData.selectedQuestions.includes(a.id);
                const bChecked = this.formData.selectedQuestions.includes(b.id);
                return bChecked - aChecked;
            });
        },
        // showPatientGroup() {
        //     return this.formData.send_to === 'patient_group';
        // },

        showIndividualPatient() {
            return this.formData.send_to === 'individual_patient';
        },

        showTreatmentLabel() {
            return this.formData.send_to === 'treatment_label';
        },

        showCustomReoccurring() {
            return this.formData.repetition === 'custom';
        },

        isEndsOn() {
            return this.formData.ends === 'on';
        },

        isEndsAfter() {
            return this.formData.ends === 'after';
        }
    }))
})

    // document.addEventListener('alpine:init', () => {
    //     Alpine.data('SurveyComponent', () => ({
    //         searchQuery: '',
    //         searchQuestion: '',
    //         patients: [],
    //         questions: [],
    //         loading: false,
    //         questionsLoading: false,
    //         addQuestion: false,

    //             formData: {
    //             title: '',
    //             repetition: '',
    //             send_to: '',
    //             patient: '',
    //             description: '',
    //             ends: 'never',
    //             ends_on: '',
    //             ends_after: 1,
    //             repeat_interval: 1,
    //             repeat_unit: 'day',
    //             repeat_days: [],
    //             selectedPatients: [],
    //             selectedQuestions: [],
    //         },

    //         init() {
    //             if (!Array.isArray(this.formData.repeat_days)) {
    //                 this.formData.repeat_days = [];
    //             }
    //         },

    //         computedCustomRecurrence() {
    //             return {
    //                 repeat: {
    //                     interval: this.formData.repeat_interval,
    //                     unit: this.formData.repeat_unit,
    //                 },
    //                 repeat_on: this.formData.repeat_unit === 'week'
    //                     ? this.formData.repeat_days
    //                     : null,
    //                 ends: {
    //                     type: this.formData.ends,
    //                     on: this.formData.ends === 'on' ? this.formData.ends_on : null,
    //                     after: this.formData.ends === 'after' ? this.formData.ends_after : null,
    //                 }
    //             };
    //         },


          

    //         patientDisplayName(patient) {
    //             const first = patient.first_name?.trim();
    //             const last  = patient.last_name?.trim();

    //             if (first || last) {
    //                 return `${first ?? ''} ${last ?? ''}`.trim();
    //             }

    //             return patient.email;
    //         },

    //         fetchPatients() {
    //             this.loading = true;
    //             fetch('/surveys/fetch-patients')
    //                 .then(response => response.json())
    //                 .then(res => {
    //                     if (res.success) {
    //                         this.patients = res.data;
    //                     }
    //                 })
    //                 .catch(error => {
    //                     console.error('Error fetching patients:', error);
    //                 })
    //                 .finally(() => {
    //                     this.loading = false;
    //                 });
    //         },

    //         fetchQuestions() {
    //             this.questionsLoading = true;
    //             fetch('/surveys/fetch-questions')
    //                 .then(res => res.json())
    //                 .then(res => {
    //                     if (res.success) {
    //                         this.questions = res.data;
    //                     }
    //                 })
    //                 .catch(error => {
    //                     console.error('Error fetching questions:', error);
    //                 })
    //                 .finally(() => this.questionsLoading = false);
    //         },


    //     filteredPatients() {
    //         if (!this.searchQuery) {
    //             return this.patients;
    //         }

    //         return this.patients.filter(patient =>
    //             patient.email
    //                 ?.toLowerCase()
    //                 .includes(this.searchQuery.toLowerCase())
    //         );
    //     },
    //     filteredQuestions() {
    //         if (!this.searchQuestion) return this.questions;

    //         return this.questions.filter(q =>
    //             q.question.toLowerCase()
    //                 .includes(this.searchQuestion.toLowerCase())
    //         );
    //     },

    //         showPatientGroup() {
    //             return this.formData.send_to === 'patient_group';
    //         },
    //         showIndividualPatient() {
    //             return this.formData.send_to === 'individual_patient';
    //         },
    //         showCustomReoccurring() {
    //             return this.formData.repetition === 'custom';
    //         },

    //         isEndsOn() {
    //         return this.formData.ends === 'on';
    //         },

    //         isEndsAfter() {
    //             return this.formData.ends === 'after';
    //         }
    //     }))
    // })
</script>
@endsection
