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
    x-data="documentComponent()" 
    @click="
        const editBtn = $event.target.closest('.edit-document-btn');
        const deleteBtn = $event.target.closest('.delete-document-btn');

        if (editBtn) editDepartment(editBtn.dataset.id);
        if (deleteBtn) deleteDocument(deleteBtn.dataset.id);
    "
>
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                <h3>{{__('Documents List')}}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"> <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">{{__("Documents")}}</li>
                        <li class="breadcrumb-item active">{{__("Documents List")}}</li>
                    </ol>
                </div>
            </div>
        </div>
          {{-- New document modal --}}
        <div class="col-md-6">
        <div class="modal fade" id="document-modal" tabindex="-1" role="dialog"
            aria-labelledby="document-modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document" :class="{ 'content-loading': isLoadingEdit }">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" x-text="isEdit ? '{{__("Edit document")}}' : 'Add New document'"></h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body custom-scrollbar">
                        <form method="POST" @submit.prevent="addOrEditdocument" :class="{ 'opacity-50 pointer-events-none': isLoadingEdit }">
                            @csrf
                        
                            <!-- Email -->
                            <div class="form-group mb-3">
                                <label for="name">{{__("Name")}}</label>
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
                                    {{__("Close")}}
                                </button>
                                <button class="btn btn-primary" type="submit" :disabled="isSaving">
                                    <span x-show="isSaving" class="spinner-border spinner-border-sm me-2" 
                                        role="status" aria-hidden="true"></span>
                                    <span x-text="isSaving ? '{{__("Saving")}}...' : '{{__("Save")}}'"></span>
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
                    <div class="card-body px-0 pt-0">
                        <div class="top-body">
                            <div class="row common-f-start g-sm-3 g-2 mb-3">
                                <div class="col-auto"><label class="form-label"></label></div>
                                <div class="col-auto">
                                    {{-- <div class="range-dropdown" id="reportrange"><span></span></div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="product-report">
                            <div x-show="isLoading" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">{{__("Loading...")}}</span>
                                </div>
                                <p class="mt-2">{{__("Loading documents")}}...</p>
                            </div>
                            <div x-show="!isLoading" class="product-report">
                            <div class="recent-table table1-responsive custom-scrollbar">
                                <div class="recent-table table1-responsive custom-scrollbar">
                                    <table class="table" id="document-table">
                                        <thead class="mt-5">
                                            <tr>
                                                <th> <span class="c-o-light f-w-600">S/N</span></th>
                                                <th> <span class="c-o-light f-w-600">{{__("Title")}}</span></th>
                                                <th> <span class="c-o-light f-w-600">{{__("Type")}}</span></th>
                                                <th> <span class="c-o-light f-w-600">{{__("Patients")}}</span></th>
                                                <th> <span class="c-o-light f-w-600">{{__("Count")}}</span></th>
                                                <th> <span class="c-o-light f-w-600">{{__("Created At")}}</span></th>
                                                <th> <span class="c-o-light f-w-600">{{__("Status")}}</span></th>
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
        </div>
    </div><!-- Container-fluid Ends-->
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
    // window.existingdocuments = @json($documents);
    
    Alpine.data('documentComponent', () => ({
        isLoading: false,
        documents: [],

        // Patient Form State
        isEdit: false,
        isSaving: false,
        isLoadingEdit: false,
        formData: {
            name: '',
        },
        
        modalInstance: null,

        init(){
            this.fetchDocuments(),
            this.listenForStatusToggle()
        },

        listenForStatusToggle() {
            document.querySelector('#document-table').addEventListener('change', async (e) => {
                const toggle = e.target.closest('.status-toggle');
                if (!toggle) return;

                const docId     = toggle.dataset.id;
                const isChecked = toggle.checked;
                const newStatus = isChecked ? 'active' : 'inactive';

                // Grab the badge next to the toggle
                const badge = toggle.closest('.status-wrapper')?.querySelector('.status-badge');

                if (badge) {
                    badge.textContent  = isChecked ? 'Active' : 'Inactive';
                    badge.className    = `badge status-badge ${isChecked ? 'bg-success' : 'bg-secondary'}`;
                }

                toggle.disabled = true; // prevent double-click

                try {
                    const response = await fetch(`/documents/update-status/${docId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ status: newStatus }),
                    });

                    const result = await response.json();

                    if (!response.ok || !result.success) {
                        // Revert on failure
                        toggle.checked = !isChecked;
                        if (badge) {
                            badge.textContent = !isChecked ? 'Active' : 'Inactive';
                            badge.className   = `badge status-badge ${!isChecked ? 'bg-success' : 'bg-secondary'}`;
                        }
                        alert(result.message ?? 'Failed to update status.');
                    }
                } catch (error) {
                    console.error('Status update error:', error);
                    // Revert on network error
                    toggle.checked = !isChecked;
                    if (badge) {
                        badge.textContent = !isChecked ? 'Active' : 'Inactive';
                        badge.className   = `badge status-badge ${!isChecked ? 'bg-success' : 'bg-secondary'}`;
                    }
                    alert('Network error. Please try again.');
                } finally {
                    toggle.disabled = false;
                }
            });
        },

        //All Documents
        async fetchDocuments() {
            this.isLoading = true;
            try {
                const response = await fetch('/documents/fetch-documents');
                const data = await response.json();
                this.documents = data.documents;

                // Pass the data directly to the DataTable
                this.renderDataTable(this.documents);
            } catch (error) {
                console.error('Error:', error);
            } finally{
                this.isLoading = false;
            }
        },

    renderDataTable(data) {
        if ($.fn.DataTable.isDataTable('#document-table')) {
            $('#document-table').DataTable().destroy();
        }

        $('#document-table').DataTable({
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
                    width: '40px',
                    className: 'text-start'
                },
                {
                    targets: 4,
                    width: '25px',
                    className: 'text-start'
                },
                {
                    targets: 5,
                    width: '30px',
                    className: 'text-start'
                },
                { 
                    targets: 6,
                    width: '130px',
                    orderable: false,
                    className: 'text-start'
                },

                {
                    targets: 7,
                    width: '100px',
                    orderable: false,
                    className: 'text-start'
                },
            ],

            columns: [
            { 
                data: null,
                render: (data, type, row, meta) => meta.row + 1,
                className: 'text-start'
            },

            // 2️⃣ Title
            {
                data: 'title',
                className: 'text-start',
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

            // 3️⃣ Document Type
            { 
                data: 'document_type',
                className: 'text-start',
                render: function(data) {
                    return data ?? '-';
                }
            },

            // 4️⃣ Patients Count
            {
                data: 'assignments_count',
                className: 'text-start',
                render: function(data) {
                    return `<span class="badge bg-dark">
                                ${data} patient${data !== 1 ? 's' : ''}
                            </span>`;
                }
            },

            // 5️⃣ Signed Count
            {
                data: 'signed_counts',
                className: 'text-start',
                render: function(data) {
                    return `<span class="badge bg-success">
                                ${data ?? 0} signed
                            </span>`;
                }
            },

            // 6️⃣ Created At
            {
                data: 'created_at',
                className: 'text-start',
                render: function (data, type) {

                    if (!data) return '-';

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
            // 7️⃣ Status Toggle
            {
                data: 'status',
                orderable: false,
                render: function(data, type, row) {
                    const isActive = data === 'active';
                    return `
                        <div class="d-flex align-items-center gap-2 status-wrapper">
                            <div class="form-check form-switch mb-0">
                                <input
                                    type="checkbox"
                                    class="form-check-input status-toggle"
                                    role="switch"
                                    data-id="${row.id}"
                                    ${isActive ? 'checked' : ''}
                                    style="cursor:pointer; width:2.5em; height:1.25em;"
                                >
                            </div>
                            <span class="badge status-badge ${isActive ? 'bg-success' : 'bg-secondary'}">
                                ${isActive ? 'Active' : 'Inactive'}
                            </span>
                        </div>
                    `;
                }
            },

            // 8️⃣ Action
            {
                data: null,
                orderable: false,
                width: '100px',
                className: 'text-start',
                render: function (data, type, row) {
                    return `
                        <div class="d-flex align-items-center gap-2">
                            <a href="/documents/show/${row.id}" class="btn btn-outline-primary btn-sm" title="View">
                                            <i class="fa fa-eye"></i>
                            </a>
                             <a href="${row.signed_url}" class="btn btn-outline-primary btn-sm" target="_blank"
                rel="noopener" title="View">
                                    <i class="fa fa-download"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger delete-document-btn" data-id="${row.id}">
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
                        // { extend: 'copy', text: '{{ __("Copy") }}', className: 'btn btn-outline-primary', attr: { class: "btn btn-outline-primary" } },
                        { extend: 'csv', text: '{{ __("CSV") }}', className: 'btn btn-outline-primary', attr: { class: "btn btn-outline-primary" } },
                        { extend: 'excel', text: '{{ __("Excel") }}', className: 'btn btn-outline-primary', attr: { class: "btn btn-outline-primary" } },
                        { extend: 'pdf', text: '{{ __("PDF") }}', className: 'btn btn-outline-primary', attr: { class: "btn btn-outline-primary" } },
                        { extend: 'print', text: '{{ __("Print") }}', className: 'btn btn-outline-primary', attr: { class: "btn btn-outline-primary" } },
                        {
                        text: '<i class="fa fa-plus"></i> {{__("Add Document")}}',
                        className: 'btn btn-primary ms-2',
                        attr: {
                            class: "btn btn-primary ms-2"
                        },
                        action: function () {
                            window.location.href = "{{ route('admin.documents.create') }}";
                        }
                        }
                    ],
                },
                topEnd: {
                    search: { placeholder: '{{__("Search here")}}...' },
                },
            },
            language: {
                search: "",
                searchPlaceholder: "{{__('Search here')}}...",
                paginate: {
                    previous: '<i class="fa fa-chevron-left"></i>',
                    next: '<i class="fa fa-chevron-right"></i>'
                }
            }
            });
        },

            async deleteDocument(docId) {
                if (!confirm('Are you sure you want to delete this patient?')) {
                    return;
                }

                try {
                    const response = await fetch(`/documents/delete/${docId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Remove from allPatients array
                        //this.allDocuments = this.allDocuments.filter(document => document.id !== docId);
                        
                        // Reload DataTable with updated data
                        //this.dataTable.clear();
                        // this.dataTable.rows.add(this.allPatients);
                        // this.dataTable.draw();

                        notify('success', 'document deleted successfully');

                        setTimeout(() => {
                            location.reload();
                        }, 100);
                                
                    } else {
                        notify('error', data.message || 'Failed to delete document');
                    }
                } catch (error) {
                    console.error('Error deleting document:', error);
                    //alert('Failed to delete document');
                }
            },

            

        // Reset document form
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
            const modal = new bootstrap.Modal(document.getElementById('document-modal'));
            modal.show();
        },

        // Open modal for editing document
        openEditDepartmentModal() {
            const modalEl = document.getElementById('document-modal');

            if (!this.modalInstance) {
                this.modalInstance = new bootstrap.Modal(modalEl);
            }

            this.modalInstance.show();
        },

        //get document for editing
        async editDepartment(id) {
            this.resetDepartmentForm();
            this.isEdit = true;
            this.departmentId = id;
            this.isLoadingEdit = true;
            this.openEditDepartmentModal();

            try {
                const response = await fetch(`/edit-departments/${id}`);
                if (!response.ok) throw new Error('Failed to fetch document');

                const document = await response.json();
                console.log('document Data:', document);
                this.formData.name = document.document.name;
                //console.log('Form Data:', this.formData);

            } catch (error) {
                console.error(error);
                alert('Failed to fetch document data');
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
                notify('success', this.isEdit ? 'document updated successfully!' : 'document created successfully!');
                console.log('Success:', result);

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('document-modal'));
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
