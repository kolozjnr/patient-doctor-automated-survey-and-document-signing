@extends('layouts.simple.master')

@section('title', 'Departments List')

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


    <style>
        /* Space between export buttons/search and table header */
.dataTables_wrapper .dt-layout-row.dt-layout-table {
    margin-top: 20px;
}

    </style>
@endsection

@section('main_content')
<section
    x-data="DepartmentComponent()"
    @click="
        const editBtn = $event.target.closest('.edit-department-btn');
        const deleteBtn = $event.target.closest('.delete-department-btn');

        if (editBtn) editDepartment(editBtn.dataset.id);
        if (deleteBtn) deleteDepartmentById(deleteBtn.dataset.id);
    "
>
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                <h3>{{__('Department List')}}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"> <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">{{__("Departments")}}</li>
                        <li class="breadcrumb-item active">{{__("Department List")}}</li>
                    </ol>
                </div>
            </div>
        </div>
          {{-- New Department modal --}}
        <div class="col-md-6">
        <div class="modal fade" id="department-modal" tabindex="-1" role="dialog"
            aria-labelledby="department-modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document" :class="{ 'content-loading': isLoadingEdit }">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" x-text="isEdit ? '{{__("Edit Department")}}' : '{{__("Add New Department")}}'"></h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body custom-scrollbar">
                        <form method="POST" @submit.prevent="addOrEditDepartment" :class="{ 'opacity-50 pointer-events-none': isLoadingEdit }">
                            @csrf
                        
                            <!-- Email -->
                            <div class="form-group mb-3">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" 
                                    x-model="formData.name" required>
                            </div>

                        {{-- <div class="form-group mb-3">
                                <label for="category">Category</label>
                                <select class="form-control" x-model="formData.category">
                                    <option value="" disabled selected>Select Category</option>
                                </select>

                            </div> --}}

                            <!-- Footer -->
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">
                                    Close
                                </button>
                                <button class="btn btn-primary" type="submit" :disabled="isSaving">
                                    <span x-show="isSaving" class="spinner-border spinner-border-sm me-2" 
                                        role="status" aria-hidden="true"></span>
                                    <span x-text="isSaving ? '{{__("Saving...")}}' : '{{__("Save")}}'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div><!-- Container-fluid starts-->
    <div class="container-fluid product-report-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body px-0 pt-0" x-init="fetchDepartments()">
                        <div class="top-body">
                            <div class="row common-f-start g-sm-3 g-2 mb-3">
                                <div class="col-auto"><label class="form-label"></label></div>
                                <div class="col-auto">
                                    {{-- <div class="range-dropdown" id="reportrange"><span></span></div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="product-report">
                            <div class="recent-table table1-responsive custom-scrollbar">
                                <table class="table" id="department-table">
                                    <thead class="mt-5">
                                        <tr>
                                            <th> <span class="c-o-light f-w-600">S/N</span></th>
                                            <th> <span class="c-o-light f-w-600">{{__("Name")}}</span></th>
                                            <th> <span class="c-o-light f-w-600">{{__("Created At")}}</span></th>
                                            <th> <span class="c-o-light f-w-600">{{__("Action")}}</span>
                                            </th>
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

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
    // window.existingDepartments = @json($departments);
    
    Alpine.data('DepartmentComponent', () => ({
        
        isLoading: false,
        departments: [],

        // Patient Form State
        isEdit: false,
        isSaving: false,
        isLoadingEdit: false,
        formData: {
            name: '',
        },
        
        modalInstance: null,

        //All Departments
        async fetchDepartments() {
        try {
            const response = await fetch('/departments_get');
            const data = await response.json();
            this.departments = data.departments;

            // Pass the data directly to the DataTable
            this.renderDataTable(this.departments);
        } catch (error) {
            console.error('Error:', error);
        }
    },

    renderDataTable(data) {
        if ($.fn.DataTable.isDataTable('#department-table')) {
            $('#department-table').DataTable().destroy();
        }

        $('#department-table').DataTable({
            data: data,
            autoWidth: false,
            processing: true,
            columnDefs: [
                {
                    targets: 0,
                    width: '60px',
                    className: 'text-start'
                },
                {
                    targets: 1,
                    width: '100px',
                    className: 'text-start'
                },
                {
                    targets: 2,
                    width: '100px',
                    className: 'text-start'
                },
                {
                    targets: 3,
                    width: '100px',
                    orderable: false,
                    className: 'text-start'
                }
            ],

            columns: [
                { 
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1, // S/N logic
                    className: 'text-start'
                },
                { 
                    data: 'name',
                    className: 'text-start'
                },
               {
                    data: 'created_at',
                    className: 'text-start',
                    render: function (data, type) {
                        if (!data) return '-';

                        // Keep raw value for sorting
                        if (type === 'sort' || type === 'type') {
                            return data;
                        }

                        const date = new Date(data);

                        return date.toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });
                    }
                },
                {
                    data: null,
                    orderable: false,
                    className: 'text-start',
                    render: function (data, type, row) {
                        // Return the HTML for buttons
                        // These will be caught by the @click listener on your <section>
                        return `
                            <div class="common-flex">
                                <button class="btn btn-sm btn-outline-primary edit-department-btn" data-id="${row.id}">
                                    <i class="fa fa-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-department-btn" data-id="${row.id}">
                                    <i class="fa fa-trash"></i>
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
                        extend: 'copy', 
                        text: "{{ __('Copy') }}",
                        className: 'btn btn-outline-primary', 
                        attr: { title: "{{ __('Copy to clipboard') }}", class: "btn btn-outline-primary" } 
                    },
                    { 
                        extend: 'csv', 
                        text: "{{ __('CSV') }}",
                        className: 'btn btn-outline-primary', 
                        attr: { title: "{{ __('Export as CSV') }}", class: "btn btn-outline-primary" } 
                    },
                    { 
                        extend: 'excel', 
                        text: "{{ __('Excel') }}",
                        className: 'btn btn-outline-primary', 
                        attr: { title: "{{ __('Export as Excel') }}", class: "btn btn-outline-primary" } 
                    },
                    { 
                        extend: 'pdf', 
                        text: "{{ __('PDF') }}",
                        className: 'btn btn-outline-primary', 
                        attr: { title: "{{ __('Export as PDF') }}", class: "btn btn-outline-primary" } 
                    },
                    { 
                        extend: 'print', 
                        text: "{{ __('Print') }}",
                        className: 'btn btn-outline-primary', 
                        attr: { title: "{{ __('Print Table') }}", class: "btn btn-outline-primary" } 
                    },
                    {
                        // Custom Button with Icon
                        text: '<i class="fa fa-plus"></i> {{ __("Add Department") }}',
                        className: 'btn btn-primary ms-2',
                        attr: {
                            title: "{{ __('Create a new department') }}",
                            class: "btn btn-primary ms-2"
                        },
                        action: function () {
                            const modal = new bootstrap.Modal(
                                document.getElementById('department-modal')
                            );
                            modal.show();
                        }
                    }
                ],
            },
            topEnd: {
                search: { placeholder: "{{ __('Search here') }}..." },
            },
        },
        language: {
            search: "",
            searchPlaceholder: "{{ __('Search here') }}...",
            paginate: {
                previous: '<i class="fa fa-chevron-left"></i>',
                next: '<i class="fa fa-chevron-right"></i>'
            }
        }
        });
    },

            async deletePatient(patientId) {
                if (!confirm('Are you sure you want to delete this patient?')) {
                    return;
                }

                try {
                    const response = await fetch(`/departments/${patientId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Remove from allPatients array
                        this.allPatients = this.allPatients.filter(patient => patient.id !== patientId);
                        
                        // Reload DataTable with updated data
                        this.dataTable.clear();
                        this.dataTable.rows.add(this.allPatients);
                        this.dataTable.draw();
                        
                        alert('Department deleted successfully');
                    } else {
                        alert(data.message || 'Failed to delete department');
                    }
                } catch (error) {
                    console.error('Error deleting department:', error);
                    alert('Failed to delete department');
                }
            },

            

        // Reset department form
        resetDepartmentForm() {
            this.isEdit = false;
            this.departmentId = null;
            this.formData = {
                department_id: '',
                name: '',
            };
        },

        // Open modal for adding new patient
        openAddPatientModal() {
            this.resetDepartmentForm();
            const modal = new bootstrap.Modal(document.getElementById('department-modal'));
            modal.show();
        },

        // Open modal for editing department
        openEditDepartmentModal() {
            const modalEl = document.getElementById('department-modal');

            if (!this.modalInstance) {
                this.modalInstance = new bootstrap.Modal(modalEl);
            }

            this.modalInstance.show();
        },

        //get department for editing
        async editDepartment(id) {
            this.resetDepartmentForm();
            this.isEdit = true;
            this.departmentId = id;
            this.isLoadingEdit = true;
            this.openEditDepartmentModal();

            try {
                const response = await fetch(`/edit-departments/${id}`);
                if (!response.ok) throw new Error('Failed to fetch department');

                const department = await response.json();
                console.log('Department Data:', department);
                this.formData.name = department.department.name;
                //console.log('Form Data:', this.formData);

            } catch (error) {
                console.error(error);
                alert('Failed to fetch department data');
            } finally {
                // Remove loading state
                this.isLoadingEdit = false;
            }
        },


        // Submit patient form (add or edit)
        async addOrEditDepartment() {
            this.isSaving = true;
            console.log('isEdit:', this.isEdit);
            console.log('departmentId:', this.departmentId);
            console.log('URL:', this.isEdit ? `/departments/${this.departmentId}` : '/departments');

            //return;

            try {
                const url = this.isEdit ? `/departments/${this.departmentId}` : '/departments';
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
                notify('success', this.isEdit ? 'Department updated successfully!' : 'Department created successfully!');
                console.log('Success:', result);

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('department-modal'));
                modal.hide();

               
                // Reset form
                this.isEdit = false;
                this.resetDepartmentForm();

                // Reload page or update table
                setTimeout(() => {
                    location.reload();
                }, 1500);

            } catch (error) {
                console.error('Submission Error:', error);
                window.dispatchEvent(new CustomEvent('alert', {
                    detail: {
                        type: 'error',
                        message: 'Failed to save: ' + error.message
                    }
                }));
            } finally {
                this.isSaving = false;
            }
        },
    }));
});

</script>


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
