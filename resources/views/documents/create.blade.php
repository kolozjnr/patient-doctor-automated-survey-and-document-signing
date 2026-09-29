@extends('layouts.simple.master')

@section('title', 'Add Document')

@section('css')
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                   <h3>
                        @if(isset($document))
                            {{ __("Edit document") }}
                        @else
                            {{ __("Add New Document") }}
                        @endif
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}"> <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">{{__('Documents')}}</li>
                       <li class="breadcrumb-item active">
                            {{ isset($document) ? __('Edit document') : __('Add New Document') }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div><!-- Container-fluid starts-->
    <div class="container-fluid" x-data="DocumentComponent()">
        <div class="edit-profile">
            <div class="row">
                <div class="col-xl-12">
                    <form class="" @submit.prevent="saveOrUpdateDocument()" method="POST" enctype="multipart/form-data">
                        <div class="card">
                            
                            <div class="card-body">
                                <div class="row custom-input">
                                    <div class="col-xxl-4 box-col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="Title">{{__("Title")}}</label>
                                            <input class="form-control" id="title" x-model="formData.title" type="text" placeholder="{{__('Title')}}">
                                        </div>
                                    </div>

                                       <div class="col-xxl-4 box-col-12">
                                        <div class="mb-3"><label class="form-label"
                                                for="repetition">{{__('Document Type')}}</label><select class="form-control btn-square" x-model="formData.document_type" id="repetition">
                                                <option selected>{{__('Choose Type')}}</option>
                                                <option value="simple">{{__('Simple Document')}}</option>
                                                <option value="sign">{{__('Sign Document')}}</option>
                                            </select></div>
                                    </div>

                                    <div class="col-xxl-12 box-col-12" x-show="formData.document_type === 'sign'">
                                        <div class="mb-3">
                                             <div class="mt-2">
                                                <p class="fw-bold mb-2">Ondertekenings Document Placeholders:</p>

                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item py-1">
                                                        <code>@{{Signature;type=signature}}</code> - Handtekening veld
                                                    </li>
                                                    <li class="list-group-item py-1">
                                                        <code>@{{Name}}</code> - Tekst veld
                                                    </li>
                                                    <li class="list-group-item py-1">
                                                        <code>@{{Date;type=date}}</code> - Datum veld
                                                    </li>
                                                    <li class="list-group-item py-1">
                                                        <code>@{{Email}}</code> - Email veld
                                                    </li>
                                                    <li class="list-group-item py-1">
                                                        <code>@{{Phone;type=phone}}</code> - Telefoon veld
                                                    </li>
                                                    <li class="list-group-item py-1">
                                                        <code>@{{Checkbox;type=checkbox}}</code> - Checkbox
                                                    </li>
                                                    <li class="list-group-item py-1">
                                                        <code>@{{Select;type=select;options=Optie1,Optie2}}</code> - Dropdown
                                                    </li>
                                                </ul>

                                                <div class="mt-2 py-2 mb-0">
                                                    Als er geen placeholders worden gebruikt, wordt automatisch een handtekening veld toegevoegd.
                                                </div>
                                            </div>
                                            {{-- <div class="card-header py-2 d-flex align-items-center justify-content-between">
                                                <h6 class="mb-0">Custom Placeholders</h6>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" 
                                                        x-model="formData.useCustomPlaceholders"
                                                        id="useCustomPlaceholders">
                                                    <label class="form-check-label" for="useCustomPlaceholders">
                                                        Add custom fields to document
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="card-body" x-show="formData.useCustomPlaceholders">
                                                <!-- Available placeholder chips -->
                                                <p class="text-muted small mb-2">Click to add fields:</p>
                                                <div class="d-flex flex-wrap gap-2 mb-3">
                                                    <template x-for="ph in availablePlaceholders" :key="ph.key">
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-secondary"
                                                                @click="addPlaceholder(ph)">
                                                            + <span x-text="ph.label"></span>
                                                        </button>
                                                    </template>
                                                </div>

                                                <div x-show="formData.placeholders.length > 0">
                                                    <p class="text-muted small mb-2">Fields to be added (drag to reorder):</p>
                                                    <div class="list-group">
                                                        <template x-for="(ph, index) in formData.placeholders" :key="index">
                                                            <div class="list-group-item d-flex align-items-center justify-content-between py-2">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <span class="badge bg-primary" x-text="ph.type"></span>
                                                                    <span x-text="ph.label"></span>
                                                                    <!-- Select options input -->
                                                                    <input x-show="ph.type === 'select'"
                                                                        type="text"
                                                                        class="form-control form-control-sm ms-2"
                                                                        style="width: 200px"
                                                                        placeholder="Options: Yes,No,Maybe"
                                                                        x-model="ph.options">
                                                                    <!-- Required toggle -->
                                                                    <div class="form-check mb-0 ms-2">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            x-model="ph.required"
                                                                            :id="'req-' + index">
                                                                        <label class="form-check-label small" :for="'req-' + index">Required</label>
                                                                    </div>
                                                                </div>
                                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                                        @click="removePlaceholder(index)">
                                                                    &times;
                                                                </button>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>

                                                <p x-show="formData.placeholders.length === 0" class="text-muted small mt-2">
                                                    No custom fields added — default Signature + Date will be used.
                                                </p>
                                            </div> --}}
                                        </div>
                                    </div>

                                     <div class="col-xxl-4 box-col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="file">File</label>
                                            <input class="form-control" id="file" type="file" 
                                                @change="formData.file = $event.target.files[0]" placeholder="File">
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xxl-4 box-col-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="send_to">{{__('Send Document To')}}</label>
                                                <select class="form-control btn-square" x-model="formData.send_to" id="send_to">
                                                <option selected value="">{{__('Choose Recipient')}}</option>
                                                    
                                                <option value="department">{{__('Department')}}</option>
                                                <option value="individual_patient">{{__('Individual Patient')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4" x-show="formData.send_to === 'department'">
                                        <label class="form-label">Department</label>
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
                                    <div class="col-md-4">
                                        <div><label class="form-label" for="aboutMeDesc">{{__('Description')}}</label>
                                            <textarea class="form-control" cols="1" rows="1" id="description" x-model="formData.description" rows="4" placeholder="Enter your description"></textarea>
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

                                <span x-text="isLoading ? '{{__("Saving")}}...' : (surveyId ? '{{__("Update Document")}}' : 'Create Document')">
                                    {{ isset($survey) ? '__("Update Document")' : '__("Create Document")' }}
                                </span>
                            </button>
                            </div>

                            
                        </div>

                        <div class="edit-profile">
                            <div class="row">

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
                                                        class="list-group-item d-flex py-1 align-items-center patient-item"
                                                        :class="{ 'active-patient': formData.selectedPatients.includes(patient.id) }"
                                                    >
                                                        <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                                            <input
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                :id="'patient-' + patient.id"
                                                                :value="patient.id"
                                                                x-model="formData.selectedPatients"
                                                            >

                                                            <label
                                                                class="form-check-label ms-1" 
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
    Alpine.data('DocumentComponent', () => ({
        searchQuery: '',
        searchQuestion: '',
        patients: [],
        questions: [],
        loading: false,
        questionsLoading: false,
        addQuestion: false,
        saving: false,
        errors: {},
        surveyId: null,
        isLoading: false,

        availablePlaceholders: [
            { key: 'Signature', label: 'Signature', type: 'signature', required: true,  options: '' },
            { key: 'Name',      label: 'Name',      type: 'text',      required: false, options: '' },
            { key: 'Date',      label: 'Date',      type: 'date',      required: true,  options: '' },
            { key: 'Email',     label: 'Email',     type: 'text',      required: false, options: '' }, // email → text
            { key: 'Phone',     label: 'Phone',     type: 'phone',     required: false, options: '' },
            { key: 'Checkbox',  label: 'Checkbox',  type: 'checkbox',  required: false, options: '' },
            { key: 'Select',    label: 'Dropdown',  type: 'select',    required: false, options: '' },
        ],

        formData: {
            title: '',
            send_to: '',
            description: '',
            document_type: '',
            file: null,
            selectedPatients: [],
            department_id: null,
            useCustomPlaceholders: false,
            placeholders: []

        },
        addPlaceholder(ph) {
            // Prevent duplicate signature/date fields
            const unique = ['signature', 'date'];
            if (unique.includes(ph.type) && this.formData.placeholders.find(p => p.type === ph.type)) {
                notify('error', `A ${ph.label} field already exists.`);
                return;
            }
            this.formData.placeholders.push({ ...ph }); // push copy
        },

        removePlaceholder(index) {
            this.formData.placeholders.splice(index, 1);
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

      

        async loadDocument() {
            try {
                const response = await fetch(`/documents/${this.surveyId}/get-edit-data`);
                const result = await response.json();
                
                if (result.success) {
                    const doc = result.data;
                    // Map DB values to the Alpine formData object
                    this.formData.id = doc.id;
                    this.formData.title = doc.title;
                    this.formData.description = doc.description;
                    this.formData.send_to = doc.send_to;
                    this.formData.department_id = doc.department_id;
                    
                    if (doc.users) {
                        this.formData.selectedPatients = doc.users.map(u => u.id);
                    }
                }
            } catch (error) {
                console.error('Error loading document:', error);
            }
        },

        async saveOrUpdateDocument() {
            this.isLoading = true;

            try {
                const formPayload = new FormData();
                formPayload.append('title',         this.formData.title);
                formPayload.append('description',   this.formData.description);
                formPayload.append('document_type', this.formData.document_type);
                formPayload.append('send_to',       this.formData.send_to);

                if (this.formData.file) formPayload.append('file', this.formData.file);
                if (this.formData.department_id) formPayload.append('department_id', this.formData.department_id);

                this.formData.selectedPatients.forEach(id => formPayload.append('patient_ids[]', id));
                if (this.formData.document_type === 'sign' && this.formData.useCustomPlaceholders && this.formData.placeholders.length > 0) {
                    formPayload.append('placeholders', JSON.stringify(this.formData.placeholders));
                }

                const response = await fetch('/documents/save-or-update', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept':       'application/json',
                    },
                    body: formPayload,
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    notify('success', result.message);
                    setTimeout(() => window.location.href = '/documents', 2000);
                } else {
                    notify('error', result.message || 'Failed to save');
                }
            } catch (e) {
                notify('error', e.message);
            } finally {
                this.isLoading = false;
            }
        },
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

        fetchPatients() {
            this.loading = true;
            fetch('/documents/fetch-patients')
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

        filteredQuestions() {
            if (!this.searchQuestion) return this.questions;

            return this.questions.filter(q =>
                q.question.toLowerCase()
                    .includes(this.searchQuestion.toLowerCase())
            );
        },

        showIndividualPatient() {
            return this.formData.send_to === 'individual_patient';
        },

      

    }))
})

   
</script>
@endsection
