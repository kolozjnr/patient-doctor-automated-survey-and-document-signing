@extends('layouts.simple.master')

@section('title', 'Patients List')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
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
                <h3>{{__('Departments List')}}</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"> <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                            </svg></a></li>
                    <li class="breadcrumb-item">Departments</li>
                    <li class="breadcrumb-item active">Departments List</li>
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
                    <h5 class="modal-title" x-text="isEdit ? 'Edit Department' : 'Add New Department'"></h5>
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

                       <div class="form-group mb-3">
                            <label for="category">Category</label>
                            <select class="form-control" x-model="formData.category">
                                <option value="" disabled selected>Select Category</option>
                            </select>

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
</div><!-- Container-fluid starts-->
<div class="container-fluid user-list-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-no-border text-end">
                    <div class="card-header-right-icon">
                        <a class="btn btn-primary f-w-500" href="#" data-bs-toggle="modal" data-bs-target="#department-modal">
                            <i class="fa-solid fa-plus pe-2"></i>Add Department</a>
                        </div>
                </div>
                <div class="card-body pt-0 px-0" x-init="fetchDepartments()">
                    <div class="list-product user-list-table">
                        <div class="table-responsive custom-scrollbar">
                             <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Filter by Category:</label>
                                        <select 
                                            id="category-filter"
                                            class="form-select">
                                            <option value="">All Categories</option>
                                        </select>
                                    </div>
                                </div>
                            <table class="table" id="department-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th> <span class="c-o-light f-w-600">Department ID</span></th>
                                        <th> <span class="c-o-light f-w-600">Name</span></th>
                                        <th> <span class="c-o-light f-w-600">Actions</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-if="isLoading">
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
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

    {{-- <button @click="notify('success', 'Form submitted successfully!')">
    Submit Form
</button>

<button @click="notify('error', 'Failed to delete item')">
    Delete Item
</button>

<button @click="notify('warning', 'You are about to delete this item')">
    Warning
</button> --}}


</section>
@endsection

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
            category: '',
        },
        
        modalInstance: null,

        //All Departments
        async fetchDepartments() {
            this.isLoading = true;
            try {
                const response = await fetch('{{ route("admin.departments.get") }}');
                const data = await response.json();
                console.log(data);
                if (data.success) {
                    this.departments = data.departments;
                    this.initDataTable();
                    this.populateCategoryFilter();
                } else {
                    alert(data.message || 'Failed to load departments');
                }
            } catch (error) {
                console.error('Error fetching departments:', error);
                alert('Failed to load departments');
            } finally {
                this.isLoading = false;
            }
        },
        initDataTable() {
                const self = this;
                
                // Destroy existing DataTable if it exists
                if (this.dataTable) {
                    this.dataTable.destroy();
                }

                this.dataTable = $('#department-table').DataTable({
                    data: this.departments,
                    pageLength: 10,
                    order: [[2, 'desc']],
                    columns: [
                        {
                            data: null,
                            defaultContent: '',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'id',
                            defaultContent: '-'
                        },
                        {
                            data: 'name',
                            defaultContent: '-'
                        },
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            render: (_, __, row) => `
                                <div class="common-align gap-2 justify-content-start">
                                    <a class="square-white" href="/department/${row.id}">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    <a href="#" class="square-white edit-department-btn" data-id="${row.id}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="#" class="square-white delete-department-btn" data-id="${row.id}">
                                        <svg>
                                            <use href="{{ asset('assets/svg/icon-sprite.svg#trash1') }}"></use>
                                        </svg>
                                    </a>
                                </div>
                            `
                        }
                    ],
                    language: {
                        info: "Showing _START_ to _END_ of _TOTAL_ departments",
                        emptyTable: "No departments found",
                        zeroRecords: "No matching departments found"
                    }
                });

                // Custom filter function for categories
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        const selectedCategory = $('#category-filter').val();  // ← Fixed
                        if (selectedCategory === '') {
                            return true; // Show all if no filter selected
                        }
                        
                        const department = self.departments[dataIndex];  // ← Fixed
                        if (!department.category) {
                            return false;
                        }
                        
                        return department.category === selectedCategory;  // ← Fixed
                    }
                );

                // Apply filter on change
                $('#category-filter').on('change', function() {
                    self.dataTable.draw();
                });
            },
            populateCategoryFilter() {
                const categoriesSet = new Set();
                // Collect all unique categories from departments
                this.departments.forEach(department => {
                    if (department.category && department.category.trim() !== '') {
                        categoriesSet.add(department.category);
                    }
                });
                
                // Convert Set to Array and sort alphabetically
                const categories = Array.from(categoriesSet).sort();
                
                // Get the select element
                const select = $('#category-filter');
                
                // Clear existing options except "All Categories"
                select.find('option:not(:first)').remove();
                
                // Populate dropdown with categories
                categories.forEach(category => {
                    select.append(`<option value="${category}">${category}</option>`);
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
                category: '',
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
                this.formData.category = department.department.category?.length
                    ? department.department.category
                    : [''];
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
                console.log('Success:', result);

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('department-modal'));
                modal.hide();

                // Show success message
                notify('success', this.isEdit ? 'Patient updated successfully!' : 'Patient created successfully!');

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

<style>
/* Optional custom styling */
.color-preview {
    transition: background-color 0.3s ease;
}

.form-control[type="color"]::-webkit-color-swatch-wrapper {
    padding: 0;
}

.form-control[type="color"]::-webkit-color-swatch {
    border: none;
    border-radius: 0.375rem;
}

.input-group-text {
    background-color: transparent;
    border-left: 0;
}
</style>

@section('scripts')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/dataTables.select.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/select.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/trash_popup.js') }}"></script>
    <script src="{{ asset('assets/js/common-check.js') }}"></script>
@endsection
