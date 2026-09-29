

<?php $__env->startSection('title', 'Add Document'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main_content'); ?>
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                   <h3>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($document)): ?>
                            <?php echo e(__("Edit document")); ?>

                        <?php else: ?>
                            <?php echo e(__("Add New Document")); ?>

                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.documents.index')); ?>"> <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-home')); ?>"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item"><?php echo e(__('Documents')); ?></li>
                       <li class="breadcrumb-item active">
                            <?php echo e(isset($document) ? __('Edit document') : __('Add New Document')); ?>

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
                                            <label class="form-label" for="Title"><?php echo e(__("Title")); ?></label>
                                            <input class="form-control" id="title" x-model="formData.title" type="text" placeholder="<?php echo e(__('Title')); ?>">
                                        </div>
                                    </div>

                                       <div class="col-xxl-4 box-col-12">
                                        <div class="mb-3"><label class="form-label"
                                                for="repetition"><?php echo e(__('Document Type')); ?></label><select class="form-control btn-square" x-model="formData.document_type" id="repetition">
                                                <option selected><?php echo e(__('Choose Type')); ?></option>
                                                <option value="simple"><?php echo e(__('Simple Document')); ?></option>
                                                <option value="sign"><?php echo e(__('Sign Document')); ?></option>
                                            </select></div>
                                    </div>

                                    <div class="col-xxl-12 box-col-12" x-show="formData.document_type === 'sign'">
                                        <div class="mb-3">
                                             <div class="mt-2">
                                                <p class="fw-bold mb-2">Ondertekenings Document Placeholders:</p>

                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item py-1">
                                                        <code>{{Signature;type=signature}}</code> - Handtekening veld
                                                    </li>
                                                    <li class="list-group-item py-1">
                                                        <code>{{Name}}</code> - Tekst veld
                                                    </li>
                                                    <li class="list-group-item py-1">
                                                        <code>{{Date;type=date}}</code> - Datum veld
                                                    </li>
                                                    <li class="list-group-item py-1">
                                                        <code>{{Email}}</code> - Email veld
                                                    </li>
                                                    <li class="list-group-item py-1">
                                                        <code>{{Phone;type=phone}}</code> - Telefoon veld
                                                    </li>
                                                    <li class="list-group-item py-1">
                                                        <code>{{Checkbox;type=checkbox}}</code> - Checkbox
                                                    </li>
                                                    <li class="list-group-item py-1">
                                                        <code>{{Select;type=select;options=Optie1,Optie2}}</code> - Dropdown
                                                    </li>
                                                </ul>

                                                <div class="mt-2 py-2 mb-0">
                                                    Als er geen placeholders worden gebruikt, wordt automatisch een handtekening veld toegevoegd.
                                                </div>
                                            </div>
                                            
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
                                            <label class="form-label" for="send_to"><?php echo e(__('Send Document To')); ?></label>
                                                <select class="form-control btn-square" x-model="formData.send_to" id="send_to">
                                                <option selected value=""><?php echo e(__('Choose Recipient')); ?></option>
                                                    
                                                <option value="department"><?php echo e(__('Department')); ?></option>
                                                <option value="individual_patient"><?php echo e(__('Individual Patient')); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4" x-show="formData.send_to === 'department'">
                                        <label class="form-label">Department</label>
                                        <select class="form-control"
                                                x-model="formData.department_id">
                                            <option value=""><?php echo e(__("Select department")); ?></option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\Department::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($department->id); ?>">
                                                    <?php echo e(ucfirst($department->name)); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <div><label class="form-label" for="aboutMeDesc"><?php echo e(__('Description')); ?></label>
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

                                <span x-text="isLoading ? '<?php echo e(__("Saving")); ?>...' : (surveyId ? '<?php echo e(__("Update Document")); ?>' : 'Create Document')">
                                    <?php echo e(isset($survey) ? '__("Update Document")' : '__("Create Document")'); ?>

                                </span>
                            </button>
                            </div>

                            
                        </div>

                        <div class="edit-profile">
                            <div class="row">

                                <div class="col-xl-4" x-show="showIndividualPatient()">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title"><?php echo e(__('Select Patient')); ?></h5>
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
                                                    <?php echo e(__("Loading patients...")); ?>

                                                </div>
                                                
                                                <div class="text-muted text-center py-2"
                                                    x-show="!loading && filteredPatients().length === 0">
                                                    <?php echo e(__("No patients found")); ?>

                                                    <br>
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-sm btn-outline-secondary mt-2"
                                                        @click="fetchPatients()"
                                                        :disabled="loading">
                                                        <i class="fe fe-refresh-cw me-1"></i>
                                                        <?php echo e(__("Reload")); ?>

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
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($survey)): ?>
<script>
    window.surveyId = <?php echo e($survey->id); ?>;
</script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ecc-pain-new\resources\views/documents/create.blade.php ENDPATH**/ ?>