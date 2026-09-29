@extends('layouts.simple.master')

@section('title', 'Patients List')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/autoFill.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/keyTable.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/fixedHeader.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/rowReorder.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select/bootstrap-select.min.css') }}">
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Patients</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"> <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">Patients</li>
                        <li class="breadcrumb-item active">Patients List</li>
                    </ol>
                </div>
            </div>
        </div>
        
        <div class="card-header card-no-border text-end">
            <div class="card-header-right-icon">
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#labelMoadl"
                    data-whatever="@getbootstrap">Label
                </button>
            </div>
        </div>
    </div><!-- Container-fluid starts-->
    <section  x-data="PatientsComponent()"
    @click="
        const editBtn = $event.target.closest('.edit-patient-btn');
        const deleteBtn = $event.target.closest('.delete-patient-btn');

        if (editBtn) editPatient(editBtn.dataset.id);
        if (deleteBtn) deletePatientById(deleteBtn.dataset.id);
    ">
        <div class="modal fade" id="labelMoadl" tabindex="-1" role="dialog"
            aria-labelledby="labelMoadl" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-toggle-wrapper social-profile text-start dark-sign-up">
                        <div class="modal-body">
                            <!-- Treatment Labels Form -->
                            <div class="mb-5">
                                <h5 class="modal-header justify-content-left border-0 mb-3">{{__('Treatment Label')}}</h5>
                                <form class="row g-3 needs-validation" novalidate @submit.prevent="submitTreatmentLabels">
                                    <!-- Parent Row -->
                                    <div class="row g-2">
                                        <template x-for="(field, index) in treatmentLabels" :key="index">
                                            <!-- Each label takes half width -->
                                            <div class="col-md-6" x-transition:enter="transition ease-out duration-500"
                                                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                                x-transition:leave="transition ease-in duration-400"
                                                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                                x-transition:leave-end="opacity-0 translate-y-4 scale-95">
                                                <div class="row align-items-end g-2">
                                                    <!-- Label -->
                                                    <div class="col-6">
                                                        <label class="form-label">Label</label>
                                                        <input class="form-control" type="text" :placeholder="'Label ' + (index + 1)"
                                                            x-model="field.label" :style="'background-color:' + field.color + '; color:white;'"
                                                            required>
                                                    </div>

                                                    <!-- Color -->
                                                    <div class="col-1">
                                                        <label class="form-label">Color</label>
                                                        <input class="form-control p-0"
                                                            type="color"
                                                            x-model="field.color"
                                                            style="height:38px;">
                                                    </div>

                                                    <!-- Actions -->
                                                    <div class="col-3">
                                                        <label class="form-label d-block">&nbsp;</label>
                                                        <div class="d-flex gap-1">
                                                            <button type="button"
                                                                    class="btn btn-outline-primary btn-sm"
                                                                    @click="duplicateTreatmentField(index)">
                                                                <i class="fa-solid fa-plus"></i>
                                                            </button>

                                                            <button type="button"
                                                                    class="btn btn-outline-danger btn-sm"
                                                                    @click="removeTreatmentField(index)"
                                                                    x-show="index > 0">
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
                                                @click="addNewTreatmentField">
                                            <i class="fa-solid fa-plus me-2"></i>Add Another Label
                                        </button>
                                    </div>
                                    
                                    <!-- Submit Button -->
                                    <div class="col-md-12">
                                        <button class="btn btn-primary" type="submit" :disabled="isLoadingTreatment">
                                            <span x-show="isLoadingTreatment" class="spinner-border spinner-border-sm me-2" 
                                                role="status" aria-hidden="true">
                                            </span>
                                            <span x-text="isLoadingTreatment ? 'Saving...' : 'Save Treatment Labels'"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Question Labels Form -->
                            <div>
                                <h5 class="modal-header justify-content-left border-0 mb-3">{{__('Question Category')}}</h5>
                                <form class="row g-3 needs-validation" novalidate @submit.prevent="submitQuestionLabels">
                                    <!-- Parent Row -->
                                    <div class="row g-2">
                                        <template x-for="(field, index) in questionLabels" :key="index">
                                            <!-- Each label takes half width -->
                                            <div class="col-md-6" x-transition:enter="transition ease-out duration-500"
                                                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                                x-transition:leave="transition ease-in duration-400"
                                                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                                x-transition:leave-end="opacity-0 translate-y-4 scale-95">
                                                <div class="row align-items-end g-2">
                                                    <!-- Label -->
                                                    <div class="col-6">
                                                        <label class="form-label">Label</label>
                                                        <input class="form-control" type="text" :placeholder="'Label ' + (index + 1)"
                                                            x-model="field.label" :style="'background-color:' + field.color + '; color:white;'"
                                                            required>
                                                    </div>

                                                    <!-- Color -->
                                                    <div class="col-1">
                                                        <label class="form-label">Color</label>
                                                        <input class="form-control p-0"
                                                            type="color"
                                                            x-model="field.color"
                                                            style="height:38px;">
                                                    </div>

                                                    <!-- Actions -->
                                                    <div class="col-3">
                                                        <label class="form-label d-block">&nbsp;</label>
                                                        <div class="d-flex gap-1">
                                                            <button type="button"
                                                                    class="btn btn-outline-primary btn-sm"
                                                                    @click="duplicateQuestionField(index)">
                                                                <i class="fa-solid fa-plus"></i>
                                                            </button>

                                                            <button type="button"
                                                                    class="btn btn-outline-danger btn-sm"
                                                                    @click="removeQuestionField(index)"
                                                                    x-show="index > 0">
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
                                                @click="addNewQuestionField">
                                            <i class="fa-solid fa-plus me-2"></i>Add Another Label
                                        </button>
                                    </div>
                                    
                                    <!-- Submit Button -->
                                    <div class="col-md-12">
                                        <button class="btn btn-primary" type="submit" :disabled="isLoadingQuestion">
                                            <span x-show="isLoadingQuestion" class="spinner-border spinner-border-sm me-2" 
                                                role="status" aria-hidden="true">
                                            </span>
                                            <span x-text="isLoadingQuestion ? 'Saving...' : 'Save Question Labels'"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Label Modal --}}
        
        {{-- New Patient modal --}}
        <div class="col-md-6">
        <div class="modal fade" id="patient-modal" tabindex="-1" role="dialog"
            aria-labelledby="patient-modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document" :class="{ 'content-loading': isLoadingEdit }">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" x-text="isEdit ? 'Edit Patient' : 'Add New Patient'"></h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body custom-scrollbar">
                        <form method="POST" @submit.prevent="addOrEditPatient" :class="{ 'opacity-50 pointer-events-none': isLoadingEdit }">
                            @csrf
                            <input type="hidden" x-model="patientId">
                            <input type="hidden" x-model="formData.user_type" value="patient">
                            
                            <!-- Patient ID -->
                            <div class="form-group mb-3">
                                <label for="patient_id">Patient ID</label>
                                <input type="number" class="form-control" id="patient_id" 
                                    x-model="formData.patient_id" required>
                            </div>

                            <!-- Email -->
                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" 
                                    x-model="formData.email" required>
                            </div>

                            <!-- DOB -->
                            <div class="form-group mb-3">
                                <label for="date_of_birth">Date of Birth</label>
                                <input type="date" class="form-control" id="date_of_birth" 
                                    x-model="formData.date_of_birth" required>
                            </div>

                            <!-- MULTIPLE TREATMENT LABELS -->
                            <div class="form-group mb-3">
                                <label>Treatment Labels</label>
                                <template x-for="(labelId, index) in formData.labels" :key="index">
                                    <div class="d-flex gap-2 mb-2">
                                        <select class="form-control" x-model="formData.labels[index]" required>
                                            <option value="">Select Label</option>
                                            @foreach(App\Models\Label::where('category', 'treatment')->get() as $label)
                                                <option value="{{ $label->id }}">{{ $label->label }}</option>
                                            @endforeach
                                        </select>

                                        <!-- Remove Button -->
                                        <button type="button" class="btn btn-danger" 
                                            x-show="formData.labels.length > 1" 
                                            @click="removeLabel(index)">
                                            ×
                                        </button>
                                    </div>
                                </template>

                                <!-- Add more -->
                                <button type="button" class="btn btn-sm btn-outline-primary" @click="addLabel">
                                    + Add Label
                                </button>
                            </div>


                            <!-- MULTIPLE Departments -->
                            <div class="form-group mb-3">
                                <label>Departments</label>
                                <template x-for="(departmentId, index) in formData.departments" :key="index">
                                    <div class="d-flex gap-2 mb-2">
                                        <select class="form-control" x-model="formData.departments[index]" required>
                                            <option value="">Select Department</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>

                                        <!-- Remove Button -->
                                        <button type="button" class="btn btn-danger" 
                                            x-show="formData.departments.length > 1" 
                                            @click="removeDepartment(index)">
                                            ×
                                        </button>
                                    </div>
                                </template>

                                <!-- Add more -->
                                <button type="button" class="btn btn-sm btn-outline-primary" @click="addDepartment">
                                    + Add Department
                                </button>
                            </div>

                            <!-- Opt for daily Radio Button -->
                            <div class="form-group mb-3">
                                <label class="d-block">Opt for daily?</label>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="opt_daily_yes"
                                        name="opt_for_daily" value="1" x-model="formData.opt_for_daily" required>
                                    <label class="form-check-label" for="opt_daily_yes">Ja</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="opt_daily_no"
                                        name="opt_for_daily" value="0" x-model="formData.opt_for_daily" required>
                                    <label class="form-check-label" for="opt_daily_no">Nein</label>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">
                                    Close
                                </button>
                                <button class="btn btn-primary" type="submit" :disabled="isSaving">
                                    <span x-show="isSaving" class="spinner-border spinner-border-sm me-2" 
                                        role="status" aria-hidden="true"></span>
                                    <span x-text="isSaving ? 'Saving...' : 'Save'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Patient Modal ends --}}
    <div class="container-fluid product-report-wrapper">
            <div class="col-12">
                <div class="card">
                    <div class="card-body px-0 pt-0">
                        <div class="top-body">
                            <div class="row common-f-start g-sm-3 g-2">
                                {{-- <div class="col-auto">
                                    <label class="form-label">Filter</label>
                                </div> --}}
                                <div class="col-auto">
                                    <select id="treatment-filter" class="form-select w-auto">
                                        <option value="">All Treatments</option>
                                    </select>

                                    {{-- <div class="range-dropdown" id="reportrange"><span></span></div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="product-report">
                            <div class="recent-table table-responsive custom-scrollbar">
                                <table class="table" id="patient-table">
                                    <thead>
                                        <tr>
                                            <th> <span class="c-o-light f-w-600">Patient ID</span></th>
                                            <th> <span class="c-o-light f-w-600">Email</span></th>
                                            <th> <span class="c-o-light f-w-600">Treatment</span>
                                            </th>
                                            <th> <span class="c-o-light f-w-600">Department</span></th>
                                            <th> <span class="c-o-light f-w-600">Created At</span></th>
                                            <th> <span class="c-o-light f-w-600">Consent</span></th>
                                            <th> <span class="c-o-light f-w-600">Status</span></th>
                                            <th> <span class="c-o-light f-w-600">Action</span></th>
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
    </section>
@endsection


<script>
    document.addEventListener('alpine:init', () => {
        window.existingLabels = @json($labels ?? []);
        
        Alpine.data('PatientsComponent', () => ({

            treatmentLabels: window.existingLabels && window.existingLabels.length > 0 
            ? window.existingLabels
                .filter(l => l.category === 'treatment')
                .map(l => ({
                    id: l.id,
                    label: l.label,
                    color: l.color,
                    category: 'treatment'
                }))
            : [{ id: null, label: '', color: '#7366FF', category: 'treatment' }],

        questionLabels: window.existingLabels && window.existingLabels.length > 0
            ? window.existingLabels
                .filter(l => l.category === 'question')
                .map(l => ({
                    id: l.id,
                    label: l.label,
                    color: l.color,
                    category: 'question'
                }))
            : [{ id: null, label: '', color: '#28C76F', category: 'question' }],

        isLoadingTreatment: false,
        isLoadingQuestion: false,

        isLoading: false,
        isSavingLabels: false,
        allPatients: [],
        dataTable: null,
        table:null,
            
            shouldUseDarkText(hexColor) {
                hexColor = hexColor.replace('#', '');
                const r = parseInt(hexColor.substr(0, 2), 16);
                const g = parseInt(hexColor.substr(2, 2), 16);
                const b = parseInt(hexColor.substr(4, 2), 16);
                const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
                return luminance > 0.5;
            },

            // Patient Form State
            isEdit: false,
            patientId: null,
            isSaving: false,
            isLoadingEdit: false,
            formData: {
                patient_id: '',
                email: '',
                date_of_birth: '',
                user_type: 'patient',
                labels: [''],
                departments: [''],
                opt_for_daily: '0'
            },
            
            modalInstance: null,
           


            init() {
                const modalEl = document.getElementById('patient-modal');
                if (modalEl) {
                    modalEl.addEventListener('hidden.bs.modal', () => {
                        this.resetPatientForm();
                    });
                }

                // Handle edit button clicks
                $(document).on('click', '.edit-patient-btn', (e) => {
                    e.preventDefault();
                    const patientId = $(e.currentTarget).data('id');
                    this.editPatient(patientId);
                });
                
            this.initDataTable();
            this.initTreatmentFilter();
            this.fetchPatients();
            },

            initDataTable() {
            const self = this;
            
            // Define a custom stripHTML function
            const stripHTML = function(data) {
                if (!data) return '';
                if (typeof data !== 'string') return data;
                
                // Create a temporary div to parse HTML
                const div = document.createElement('div');
                div.innerHTML = data;
                
                // Get text content
                const text = div.textContent || div.innerText || '';
                
                // Clean up and trim
                return text.replace(/\s+/g, ' ').trim();
            };

            // Define render functions that handle both display and export
            const renderLabelsForExport = (labels = []) => {
                if (!labels.length) return '-';
                return labels.map(l => l.name).join(', ');
            };

            const renderConsentForExport = (consent) => {
                if (!consent || consent.trim() === '') return '-';
                return 'Consent Available';
            };

            const renderStatusForExport = (status) => {
                return status ?? '-';
            };

            const renderDepartmentForExport = (departments = []) => {
                if (!departments.length) return '-';
                return departments.map(d => d.name).join(', ');
            };

            this.table = $('#patient-table').DataTable({
                order: [[0, 'asc']],
                pageLength: 10,
                responsive: false,
                autoWidth: false,
                searching: true,
                paging: true,
                info: true,
                
                // Define column widths
                columns: [
                    { width: "15%" }, // Patient ID
                    { width: "15%" }, // Email
                    { width: "20%" }, // Treatment
                    { width: "10%" }, // Department
                    { width: "10%" }, // Created At
                    { width: "5%" },  // Consent
                    { width: "10%" }, // Status
                    { width: "10%" }  // Action
                ],
                
                // Left align all cells
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td').css({
                        'text-align': 'left',
                        'vertical-align': 'middle'
                    });
                },
                
                // Left align header cells
                headerCallback: function(thead, data, start, end, display) {
                    $(thead).find('th').css('text-align', 'left');
                },
                
                layout: {
                    topStart: {
                        buttons: [
                            {
                                extend: 'copy',
                                className: 'btn btn-outline-primary',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6], // Exclude action column (7)
                                    format: {
                                        body: function(data, row, column, node) {
                                            // Apply stripHTML for export
                                            return stripHTML(data);
                                        }
                                    }
                                }
                            },
                            {
                                extend: 'csv',
                                className: 'btn btn-outline-primary',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6], // Exclude action column (7)
                                    format: {
                                        body: function(data, row, column, node) {
                                            return stripHTML(data);
                                        }
                                    }
                                }
                            },
                            {
                                extend: 'excel',
                                className: 'btn btn-outline-primary',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6], // Exclude action column (7)
                                    format: {
                                        body: function(data, row, column, node) {
                                            return stripHTML(data);
                                        }
                                    }
                                }
                            },
                            {
                                extend: 'pdf',
                                className: 'btn btn-outline-primary',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6], // Exclude action column (7)
                                    format: {
                                        body: function(data, row, column, node) {
                                            return stripHTML(data);
                                        }
                                    }
                                }
                            },
                            {
                                extend: 'print',
                                className: 'btn btn-outline-primary',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6], // Exclude action column (7)
                                    format: {
                                        body: function(data, row, column, node) {
                                            return stripHTML(data);
                                        }
                                    }
                                },
                                customize: function(win) {
                                    // Remove action column from print
                                    $(win.document.body).find('th:last-child, td:last-child').remove();
                                    $(win.document.body).find('table').css('width', '100%');
                                }
                            },
                            {
                                text: '<i class="fa fa-plus"></i> Add Patient',
                                className: 'btn btn-primary ms-2',
                                action: function () {
                                    const modal = new bootstrap.Modal(
                                        document.getElementById('patient-modal')
                                    );
                                    modal.show();
                                }
                            }
                        ],
                    },
                    topEnd: {
                        search: { placeholder: 'Search here...' },
                    },
                },
                language: {
                    search: "",
                    searchPlaceholder: "Search here...",
                    paginate: {
                        previous: '<i class="fa fa-chevron-left"></i>',
                        next: '<i class="fa fa-chevron-right"></i>'
                    }
                }
            });
            
            // Force left alignment after table is drawn
            this.table.on('draw', function() {
                $('#patient-table th, #patient-table td').css('text-align', 'left');
            });
        },


                async fetchPatients() {
                    try {
                        const res = await fetch('/patients_get');
                        const json = await res.json();

                        if (!json.success) return;
                        const treatmentSet = new Set();

                        // Store raw data in an array for DataTable
                        const tableData = json.data.map(patient => {
                            // Store treatment names for filter
                            (patient.treatment_labels || []).forEach(l => {
                                treatmentSet.add(l.name)
                            });
                            
                            // Return an object with both display and export data
                            return {
                                patient_id: patient.patient_id ?? '-',
                                email: patient.email ?? '-',
                                treatments: patient.treatment_labels || [],
                                departments: patient.departments || [],
                                created_at: patient.created_at,
                                consent: patient.consent,
                                consent_signed_url: patient.consent_signed_url,
                                status: patient.status,
                                id: patient.id
                            };
                        });

                        // Clear and add data to DataTable
                        this.table.clear();
                        
                        // Add each row with proper rendering
                        tableData.forEach(patient => {
                            const rowNode = this.table.row.add([
                                patient.patient_id,
                                patient.email,
                                this.renderLabels(patient.treatments),
                                this.renderDepartment(patient.departments),
                                this.formatDate(patient.created_at),
                                this.renderConsent(patient.consent, patient.consent_signed_url), 
                                this.renderStatus(patient.status),
                                this.renderActions(patient.id)
                            ]).draw(false).node();
                            
                            // Store raw data for export
                            $(rowNode).data('patient', patient);
                        });

                        this.populateTreatmentFilter([...treatmentSet]);

                    } catch (e) {
                        console.error('Failed to load patients', e);
                    }
                },

                renderLabels(labels = []) {
                    if (!labels.length) return '-';

                    return labels.map(l => `
                        <span class="badge me-1"
                            style="background:${l.color}">
                            ${l.name}
                        </span>
                    `).join('');
                },

            renderDepartment(departments = []) {
            if (!departments.length) return '-';

            return departments.map(l => `
                <span class="department-badge" style="background-color: #f0f0f0; padding: 2px 8px; border-radius: 4px; margin-right: 4px;">
                    ${l.name}
                </span>
            `).join('');
        },

        renderConsent(consent, consentSignedUrl) {
            // Use consent_signed_url if available, otherwise use original consent
            const url = consentSignedUrl || consent;
            
            if (!url || url.trim() === '') {
                return '<span class="">-</span>';
            }

            return `
                <a href="${url}"
                target="_blank"
                rel="noopener"
                title="View / Download consent"
                class="text-primary pointer">
                    <i class="fa fa-download"></i>
                </a>
            `;
        },

            // renderConsent(consent) {
            //     if (!consent || consent.trim() === '') {
            //         return '<span class="">-</span>';
            //     }

            //     return `
            //         <a href="${consent}"
            //         target="_blank"
            //         rel="noopener"
            //         title="View / Download consent"
            //         class="text-primary pointer">
            //             <i class="fa fa-download"></i>
            //         </a>
            //     `;
            // },

                renderStatus(status) {
                    const map = {
                        Nieuw: 'badge bg-info',
                        Active: 'badge bg-success',
                        Inactive: 'badge bg-secondary',
                    };

                    return `<span class="${map[status] ?? 'badge bg-light'}">${status ?? '-'}</span>`;
                },

                renderActions(id) {
                    return `
                        <div class="d-flex gap-2 justify-content-center">
                            <!-- View -->
                            <a href="/patient/${id}"
                            class="btn btn-sm btn-light"
                            title="View">
                                <i class="fa fa-eye text-primary"></i>
                            </a>

                            <!-- Edit -->
                            <button class="btn btn-outline-primary btn-sm edit-patient-btn" data-id="${id}" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <!-- Delete -->
                            <button type="button"
                                class="btn btn-sm btn-light"
                                title="Delete"
                                @click="deletePatient(${id})">
                                <i class="fa fa-trash text-danger"></i>
                            </button>
                        </div>
                    `;
                },


                formatDate(date) {
                    if (!date) return '-';
                    return new Date(date).toLocaleDateString();
                },
                populateTreatmentFilter(labels) {
                    const select = document.getElementById('treatment-filter');

                    // reset
                    select.innerHTML = `<option value="">All Treatments</option>`;

                    labels.sort().forEach(label => {
                        const opt = document.createElement('option');
                        opt.value = label;
                        opt.textContent = label;
                        select.appendChild(opt);
                    });
                },

                initTreatmentFilter() {
                    const table = this.table;

                    document.getElementById('treatment-filter')
                        .addEventListener('change', function () {

                            const val = this.value;

                            if (!val) {
                                table.column(2).search('').draw();
                            } else {
                                // match label text inside badges
                                table.column(2).search(val, true, false).draw();
                            }
                        });
                },

            async deletePatient(patientId) {
                if (!confirm('Are you sure you want to delete this patient?')) {
                    return;
                }

                try {
                    const response = await fetch(`/admin/patients/${patientId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.allPatients = this.allPatients.filter(patient => patient.id !== patientId);
                        this.dataTable.clear();
                        this.dataTable.rows.add(this.allPatients);
                        this.dataTable.draw();
                        alert('Patient deleted successfully');
                    } else {
                        alert(data.message || 'Failed to delete patient');
                    }
                } catch (error) {
                    console.error('Error deleting patient:', error);
                    alert('Failed to delete patient');
                }
            },

            resetPatientForm() {
                this.isEdit = false;
                this.patientId = null;
                this.formData = {
                    patient_id: '',
                    email: '',
                    date_of_birth: '',
                    user_type: 'patient',
                    labels: [''],
                    departments: [''],
                    opt_for_daily: '0'
                };
            },

            openAddPatientModal() {
                this.resetPatientForm();
                const modal = new bootstrap.Modal(document.getElementById('patient-modal'));
                modal.show();
            },

            openEditPatientModal() {
                const modalEl = document.getElementById('patient-modal');
                if (!this.modalInstance) {
                    this.modalInstance = new bootstrap.Modal(modalEl);
                }
                this.modalInstance.show();
            },

            async editPatient(id) {
                this.resetPatientForm();
                this.isEdit = true;
                this.patientId = id;
                this.isLoadingEdit = true;
                this.openEditPatientModal();

                try {
                    const response = await fetch(`/edit-patients/${id}`);
                    if (!response.ok) throw new Error('Failed to fetch patient');

                    const patient = await response.json();
                    this.formData.patient_id = patient.data.patient_id;
                    this.formData.email = patient.data.email;
                    this.formData.date_of_birth = patient.data.date_of_birth
                        ? patient.data.date_of_birth.split('T')[0]
                        : '';
                    this.formData.labels = patient.data.labels?.length
                        ? patient.data.labels.map(l => l.id)
                        : [''];
                    this.formData.departments = patient.data.departments?.length 
                        ? patient.data.departments.map(d => d.id) 
                        : [''];
                    this.formData.opt_for_daily = patient.data.daily_notification_frequency ? '1' : '0';
                } catch (error) {
                    console.error(error);
                    alert('Failed to fetch patient data');
                } finally {
                    this.isLoadingEdit = false;
                }
            },

            async addOrEditPatient() {
                this.isSaving = true;

                try {
                    const url = this.isEdit ? `/patients/${this.patientId}` : '/patients';
                    const method = this.isEdit ? 'PUT' : 'POST';

                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(this.formData)
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Server Error');
                    }

                    const result = await response.json();
                    
                    const modal = bootstrap.Modal.getInstance(document.getElementById('patient-modal'));
                    modal.hide();

                    notify('success', this.isEdit ? 'Patient updated successfully!' : 'Patient created successfully!');
                    //alert(this.isEdit ? 'Patient updated successfully!' : 'Patient created successfully!');
                    
                    this.resetPatientForm();
                    this.fetchPatients(); // Reload data

                } catch (error) {
                    console.error('Submission Error:', error);
                    notify('error', error.message);
                    //alert('Failed to save: ' + error.message);
                } finally {
                    this.isSaving = false;
                }
            },

            // Label Management Methods (keeping your existing ones)
            addNewTreatmentField() {
                this.treatmentLabels.push({ id: null, label: '', color: '#7366FF', category: 'treatment' });
            },

            addNewTreatmentField() {
                this.treatmentLabels.push({ 
                    id: null, 
                    label: '', 
                    color: this.getRandomColor(), 
                    category: 'treatment' 
                });
            },

            removeTreatmentField(index) {
                if (this.treatmentLabels.length > 1) {
                    this.treatmentLabels.splice(index, 1);
                }
            },

            addNewQuestionField() {
                this.questionLabels.push({ 
                    id: null, 
                    label: '', 
                    color: this.getRandomColor(), 
                    category: 'question' 
                });
            },

              getRandomColor() {
                const colors = [
                    '#7366FF', '#28C76F', '#EA5455', '#FF9F43', 
                    '#1E9FF2', '#FFC085', '#A8AAAE', '#00CFE8'
                ];
                return colors[Math.floor(Math.random() * colors.length)];
            },

            duplicateQuestionField(index) {
                const field = { ...this.questionLabels[index], id: null };
                this.questionLabels.splice(index + 1, 0, field);
            },

            removeQuestionField(index) {
                if (this.questionLabels.length > 1) {
                    this.questionLabels.splice(index, 1);
                }
            },

            addNewQuestionField() {
                this.questionLabels.push({ 
                    id: null, 
                    label: '', 
                    color: this.getRandomColor(), 
                    category: 'question' 
                });
            },

            addLabel() {
                this.formData.labels.push('');
            },

            removeLabel(index) {
                if (this.formData.labels.length > 1) {
                    this.formData.labels.splice(index, 1);
                }
            },

            addDepartment() {
                this.formData.departments.push('');
            },

            removeDepartment(index) {
                if (this.formData.departments.length > 1) {
                    this.formData.departments.splice(index, 1);
                }
            },

            // async submitLabel() {
            //     this.isLoading = true;
            //     const payload = {
            //         labels: [
            //             ...this.treatmentLabels,
            //             ...this.questionLabels
            //         ]
            //     };
            //     console.log(payload);
            //     try {
            //         const response = await fetch('/label', {
            //             method: 'POST',
            //             headers: {
            //                 'Content-Type': 'application/json',
            //                 'Accept': 'application/json',
            //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            //             },
            //             body: JSON.stringify(payload)
            //         });

            //         if (!response.ok) {
            //             const errorData = await response.json();
            //             throw new Error(errorData.message || 'Server Error');
            //         }

            //         const result = await response.json();
            //         notify('success', 'Successfully saved labels.');
            //         //alert('Successfully saved labels.');
                    
            //     } catch (error) {
            //         console.error('Submission Error:', error);
            //         notify('error', 'Failed to save labels.');
            //         //alert('Failed to save: ' + error.message);
            //     } finally {
            //         this.isLoading = false;
            //     }
            // }


             async submitTreatmentLabels() {
                this.isLoadingTreatment = true;
                
                try {
                    const response = await fetch('/label', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            labels: this.treatmentLabels.map(label => ({
                                ...label,
                                category: 'treatment'
                            }))
                        })
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Server Error');
                    }

                    const result = await response.json();
                    notify('success', 'Treatment labels saved successfully!');
                    
                    // Refresh the labels from server if needed
                    if (result.labels) {
                        const treatmentLabels = result.labels.filter(l => l.category === 'treatment');
                        if (treatmentLabels.length > 0) {
                            this.treatmentLabels = treatmentLabels.map(l => ({
                                id: l.id,
                                label: l.label,
                                color: l.color,
                                category: 'treatment'
                            }));
                        }
                    }
                    
                } catch (error) {
                    console.error('Submission Error:', error);
                    notify('error', 'Failed to save treatment labels: ' + error.message);
                } finally {
                    this.isLoadingTreatment = false;
                }
            },

            async submitQuestionLabels() {
                this.isLoadingQuestion = true;
                //console.log(this.questionLabels);
                //return;
                
                try {
                    const response = await fetch('/label', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            labels: this.questionLabels.map(label => ({
                                ...label,
                                category: 'question'
                            }))
                        })
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Server Error');
                    }

                    const result = await response.json();
                    notify('success', 'Question labels saved successfully!');
                    
                    // Refresh the labels from server if needed
                    if (result.labels) {
                        const questionLabels = result.labels.filter(l => l.category === 'question');
                        if (questionLabels.length > 0) {
                            this.questionLabels = questionLabels.map(l => ({
                                id: l.id,
                                label: l.label,
                                color: l.color,
                                category: 'question'
                            }));
                        }
                    }
                    
                } catch (error) {
                    console.error('Submission Error:', error);
                    notify('error', 'Failed to save question labels: ' + error.message);
                } finally {
                    this.isLoadingQuestion = false;
                }
            },
        }));
    });



</script>

@section('scripts')
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
