@extends('layouts.simple.master')

@section('title', 'FAQ List')

@section('css')

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/autoFill.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/keyTable.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/fixedHeader.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/rowReorder.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select/bootstrap-select.min.css') }}">


       <style>
        .form-container {
            max-width: 700px;
            margin: 0 auto;
        }
        .sub-question-section {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
            border-radius: 15px;
            padding: 20px;
            margin-top: 25px;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 12px;
            font-size: 15px;
        }
        .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        /* .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 15px;
            transition: all 0.3s ease;
        } */
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .form-check {
            margin-bottom: 15px;
            padding-left: 0;
        }
        .form-check-input {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            cursor: pointer;
            border: 2px solid #667eea;
        }
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        .form-check-label {
            font-size: 12px;
            color: #444;
            cursor: pointer;
            user-select: none;
        }
        .question-types-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .section-title i {
            margin-right: 10px;
            color: #667eea;
        }
        .icon-header {
            display: flex;
            align-items: center;
        }
        .icon-header i {
            margin-right: 15px;
            font-size: 22px;
        }
        .card-body {
            padding: 35px;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 12px;
            font-size: 15px;
        }
        .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .sub-question-card {
            background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%);
            border-radius: 16px;
            padding: 22px;
            margin-bottom: 20px;
            position: relative;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .sub-question-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }
        /* .form-control {
            width: 90% !important;
            border: 2px solid rgba(255,255,255,0.8);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 15px;
            background: white;
            transition: all 0.3s ease;
        } */
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
        }
        .btn-remove {
            position: absolute;
            /* top: 15px; */
            right: 5px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid #ff6b6b;
            color: #ff6b6b;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 18px;
        }
        .btn-remove:hover {
            background: #ff6b6b;
            color: white;
            transform: rotate(90deg);
        }
        .radio-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-top: 20px;
        }
       
        .btn-add {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 14px 10px;
            border-radius: 12px;
            font-weight: normal;
            font-size: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            /* display: flex; */
            /* align-items: center; */
            gap: 8px;
        }
        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        .section-label {
            font-size: 14px;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .icon-header {
            display: flex;
            align-items: center;
        }
        .icon-header i {
            margin-right: 15px;
            font-size: 32px;
        }
        .add-section {
            /* display: flex; */
            justify-content: center;
            margin-top: 25px;
        }
        .question-badge {
            background: white;
            color: #667eea;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 13px;
        }

        /* Row reorder */
/* =====================================================
   TRELLO-STYLE DATATABLE ROW REORDER
   Clean • Flat • Calm • No Distortion
===================================================== */

/* Base table stability */
table.dataTable {
    border-collapse: collapse !important;
}

/* Prevent text selection while dragging */
.dt-rowReorder-moving *,
.dt-rowReorder-float * {
    user-select: none !important;
}

/* =====================================================
   DRAG CURSOR
===================================================== */

.drag-cursor {
    cursor: grab !important;
}

.drag-cursor:active {
    cursor: grabbing !important;
}

/* =====================================================
   ACTION / NO-DRAG COLUMN
===================================================== */

td.no-drag,
td.no-drag *,
.no-drag,
.no-drag * {
    cursor: pointer !important;
    pointer-events: auto !important;
}

td.no-drag {
    position: relative;
    z-index: 5;
}

/* =====================================================
   ROW HOVER (SUBTLE LIKE TRELLO)
===================================================== */

table.dataTable tbody tr:hover td.drag-cursor {
    background-color: #f4f5f7 !important;
}

table.dataTable tbody tr:hover td.no-drag {
    background-color: transparent !important;
}

/* =====================================================
   ORIGINAL ROW WHILE DRAGGING
   (Flat, subtle, no borders, no scale)
===================================================== */

.dt-rowReorder-moving {
    background-color: #27476d !important;
    opacity: 0.85 !important;
    outline: 2px solid #4c9aff !important;
    outline-offset: -2px;
    box-shadow: none !important;
    transform: none !important;
    z-index: 2 !important;
}

.dt-rowReorder-moving td {
    background-color: transparent !important;
    border: none !important;
    color: #172b4d !important;
}

/* =====================================================
   FLOATING CLONE (THIS IS THE "CARD")
===================================================== */

.dt-rowReorder-float {
    background-color: #ffffff !important;
    color: #172b4d !important;

    border-radius: 6px !important;
    border: none !important;

    box-shadow:
        0 8px 16px rgba(9, 30, 66, 0.25),
        0 0 0 1px rgba(9, 30, 66, 0.15);

    opacity: 1 !important;
    cursor: grabbing !important;
}

.dt-rowReorder-float td {
    background-color: transparent !important;
    border: none !important;
    color: #172b4d !important;
}

/* =====================================================
   DROP TARGET INDICATOR (TRELLO LINE)
===================================================== */

table.dataTable tbody tr.dt-rowReorder-hover {
    position: relative;
    background-color: #f4f5f7 !important;
}

table.dataTable tbody tr.dt-rowReorder-hover::before {
    content: '';
    position: absolute;
    left: 0;
    right: 0;
    top: -2px;
    height: 3px;
    /* background-color: #4c9aff; */
    border-radius: 2px;
}

table.dataTable tbody tr.dt-rowReorder-hover td {
    background-color: #f4f5f7 !important;
}

/* =====================================================
   SMOOTH REORDER ANIMATION
===================================================== */

table.dataTable tbody tr {
    transition: background-color 0.15s ease !important;
}

/* =====================================================
   REMOVE DATATABLES DEFAULT DRAG ARTIFACTS
===================================================== */

.dt-rowReorder-noOverflow {
    overflow: visible !important;
}

/* =====================================================
   OPTIONAL: DRAG HANDLE INDICATOR (LEFT BAR)
===================================================== */

table.dataTable tbody tr:hover td.drag-cursor::before {
    content: '';
    position: absolute;
    left: 0;
    top: 4px;
    bottom: 4px;
    width: 4px;
    /* background-color: #4c9aff; */
    border-radius: 2px;
}


    </style>
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>{{__("FAQ Questions")}}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <div class="col-12 d-flex justify-content-end">
                                <button class="btn btn-primary" type="button" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#categoryModal">
                                    {{ __("Category") }}
                                </button>
                            </div>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"> <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">{{__("Questions")}}</li>
                        <li class="breadcrumb-item active">{{__("FAQ Questions")}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div><!-- Container-fluid starts-->
    <section x-data="QuestionsComponent()"
    @click="
        const editBtn = $event.target.closest('.edit-question-btn');
        const deleteBtn = $event.target.closest('.delete-question-btn');

        if (editBtn) editQuestion(editBtn.dataset.id);
        if (deleteBtn) deleteQuestionById(deleteBtn.dataset.id);
    "
>

    {{-- Category Modal starts --}}
    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog"
        aria-labelledby="categoryModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-600">{{__('FAQ Categories')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pt-2">
                    <form class="needs-validation" novalidate @submit.prevent="submitCategory">

                        {{-- Column Headers --}}
                        <div class="row g-2 mb-1 px-1">
                            <div class="col-4">
                                <label class="form-label fw-semibold text-muted small mb-0">{{__("Category")}}</label>
                            </div>
                            <div class="col-4">
                                <label class="form-label fw-semibold text-muted small mb-0">{{__("Header")}}</label>
                            </div>
                            <div class="col-2"></div>
                        </div>

                        {{-- Repeating Fields --}}
                        <div class="row g-2">
                            <template x-for="(field, index) in faqCategory" :key="index">
                                <div class="col-12"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 -translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 -translate-y-2">

                                    <div class="row g-2 align-items-center">
                                        {{-- Category --}}
                                        <div class="col-4">
                                            <input class="form-control"
                                                type="text"
                                                :placeholder="'{{__('Category')}} ' + (index + 1)"
                                                x-model="field.category"
                                                required>
                                        </div>

                                        {{-- Header --}}
                                        <div class="col-4">
                                            <input class="form-control"
                                                type="text"
                                                :placeholder="'{{__('Header')}} ' + (index + 1)"
                                                x-model="field.header">
                                        </div>

                                        {{-- Actions --}}
                                        <div class="col-2 d-flex gap-1">
                                            <button type="button"
                                                class="btn btn-outline-primary btn-sm"
                                                @click="duplicateCategoryField(index)"
                                                title="{{__('Duplicate')}}">
                                                <i class="fa-solid fa-copy"></i>
                                            </button>
                                            <button type="button"
                                                class="btn btn-outline-danger btn-sm"
                                                @click="removeCategoryField(index)"
                                                x-show="faqCategory.length > 1"
                                                title="{{__('Remove')}}">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Add Another --}}
                        <div class="mt-3">
                            <button type="button"
                                class="btn btn-outline-secondary btn-sm"
                                @click="addNewCategoryField">
                                <i class="fa-solid fa-plus me-1"></i>{{__("Add Another Category")}}
                            </button>
                        </div>

                        <hr class="my-3">

                        {{-- Submit --}}
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">
                                {{__("Cancel")}}
                            </button>
                            <button class="btn btn-primary" type="submit" :disabled="isLoadingCategory">
                                <span x-show="isLoadingCategory"
                                    class="spinner-border spinner-border-sm me-2"
                                    role="status" aria-hidden="true">
                                </span>
                                <span x-text="isLoadingCategory ? '{{__('Saving')}}...' : '{{__('Save Categories')}}'"></span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
{{-- Category modal ends --}}
 {{-- New Question modal --}}
    <div class="col-md-6">
      

    <div class="modal fade" id="question-modal" tabindex="-1" role="dialog"
        aria-labelledby="question-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content" :class="{ 'content-loading': isLoadingEdit }">
                <div class="modal-header">
                    <h5 class="modal-title" x-text="isEdit ? '{{__("Edit Question")}}' : '{{__("Add New Question")}}'"></h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body custom-scrollbar">
                    <form method="POST" @submit.prevent="addOrEditFaq" enctype="multipart/form-data" :class="{ 'opacity-50 pointer-events-none': isLoadingEdit }">
                        @csrf
                        <input type="hidden" x-model="questionId">
                        
                        <!-- Question ID -->
                        <div class="form-group mb-3">
                            <label for="question">  <i class="fa fa-question"></i> {{__("Question")}}</label>
                            <textarea name="question" class="form-control" x-model="formData.question" id="" cols="2" rows="2"></textarea>
                        </div>

                          <div class="form-group mb-3">
                            <label for="question">  <i class="fa fa-answer"></i> {{__("Answer")}}</label>
                            <textarea name="answer" class="form-control" x-model="formData.answer" id="" cols="2" rows="2"></textarea>
                        </div>
                        

                        <!--  -->
                        <div class="form-group mb-3">
                            <label>  <i class="fa fa-list-ul"></i> {{__("Category")}}</label>
                                <select class="form-control" x-model="formData.category_id">
                                <option value="">-- {{__("Select Category")}} --</option>

                                @foreach($faqCategory as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        
                        <div class="form-group mb-3">
                            <label>  <i class="fa fa-list-ul"></i> {{__("Media")}}</label>
                                <input type="file" class="form-control" @change="formData.file = $event.target.files[0]">
                        </div>


                        <!-- Footer -->
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">
                                Close
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
{{-- Question modal end --}}
    <div class="container-fluid product-report-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body px-0 pt-0">
                        <div class="top-body">
                            <div class="row common-f-start g-sm-3 g-2">
                                {{-- <div class="col-auto">
                                    <label class="form-label">Label</label>
                                </div> --}}
                                <div class="col-auto">
                                    <select id="question-label-filter" class="form-select w-auto">
                                        <option value="">{{__("All question labels")}}</option>
                                    </select>

                                    {{-- <div class="range-dropdown" id="reportrange"><span></span></div> --}}
                                </div>
                                {{-- <label class="form-label">Type</label></div>
                                <div class="col-auto">
                                    <select id="type-filter" class="form-select w-auto">
                                        <option value="">All Type</option>
                                    </select>
                                </div> --}}
                            </div>
                        </div>
                        <div class="product-report position-relativ" :class="{ 'content-loading': ordering }">
                        <div class="recent-table table-responsive custom-scrollbar">
                            <table class="table" id="question-table">
                                <thead>
                                    <tr>
                                        <th><span class="c-o-light f-w-600">S/N</span></th>
                                        <th><span class="c-o-light f-w-600">{{__("Question")}}</span></th>
                                        <th><span class="c-o-light f-w-600">{{__("Answer")}}</span></th>
                                        <th><span class="c-o-light f-w-600">{{__("Category")}}</span></th>
                                        <th><span class="c-o-light f-w-600">{{__("Image")}}</span></th>
                                        <th><span class="c-o-light f-w-600">{{__("Action")}}</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <!-- Loading State -->
                                    <template x-if="isLoading">
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">{{__("Loading...")}}</span>
                                                </div>
                                                <p class="mt-2">{{__("Loading questions...")}}</p>
                                            </td>
                                        </tr>
                                    </template>
                                    
                                    <!-- Empty State -->
                                    <template x-if="!isLoading && filteredQuestions.length === 0">
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <p class="text-muted">{{__("No questions found")}}</p>
                                                <template x-if="typeFilter">
                                                    <button @click="typeFilter = ''; filterByType()" class="btn btn-sm btn-primary mt-2">
                                                        Clear Filter
                                                    </button>
                                                </template>
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
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('alpine:init', () => {
    window.existingCategory = @json($faqCategory ?? []);
    Alpine.data('QuestionsComponent', () => ({
        isLoading: false,
        isEdit: false,
        isSaving: false,
        isLoadingEdit: false,
        ordering: false,
        isLoadingCategory: false,

        questionId: null,
        allQuestions: [],
        dataTable: null,

        questions: [],
        filteredQuestions: [],
        typeFilter: '',

        faqCategory: window.existingCategory && window.existingCategory.length > 0 
            ? window.existingCategory
                .map(c => ({
                    id: c.id,
                    category: c.category,
                    header: c.header
                }))
            : [{ id: null, category: '', header: '' }],

            addNewCategoryField() {
                this.faqCategory.push({ id: null, category: '', header: '' });
            },
        

            removeCategoryField(index) {
                if (this.faqCategory.length > 1) {
                    this.faqCategory.splice(index, 1);
                }
            },
            duplicateCategoryField(index) {
                const source = this.faqCategory[index];
                this.faqCategory.splice(index + 1, 0, {
                    id: null,
                    category: '',
                    header: ''
                });
            },

        formData: {
            question: '',
            answer: '',
            parent_question_id: null,
            category_id: null,
            file: null
        },

          async submitCategory() {
                this.isLoadingCategory = true;
                
                try {
                    const response = await fetch('/faq/save-category', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            cats: this.faqCategory.map(cat => ({
                                ...cat
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
                    this.isLoadingCategory = false;
                }
            },
        
        
    questionModalInstance: null,
    bellscaleModalInstance: null,

      async init() {
            await this.loadQuestions();
            this.$nextTick(() => {
                this.initializeDataTable();
                this.initfaqCategoryFilter();

                // ← ADD THIS BLOCK
                document.getElementById('question-modal')
                    .addEventListener('hidden.bs.modal', () => {
                        this.resetQuestionForm();
                    });
            });
        },

        // All Questions
        async loadQuestions() {
            this.isLoading = true;
            try {
                const response = await fetch(`/faq/get-all-faqs`);
                const result = await response.json();
                
                if (result.success) {
                    this.questions = result.data;
                    this.filteredQuestions = [...this.questions];

                      const labelSet = new Set();

                this.questions.forEach(q => {
                    if (q.label && q.label.label) {
                        labelSet.add(q.label.label);
                    }
                });
                this.populatefaqCategoryFilter([...labelSet]);

                } else {
                    console.error('Failed to load questions');
                }
            } catch (error) {
                console.error('Error loading questions:', error);
            } finally {
                this.isLoading = false;
            }
        },
        
        filterByType() {
            if (!this.typeFilter) {
                this.filteredQuestions = [...this.questions];
            } else {
                this.filteredQuestions = this.questions.filter(
                    question => question.type === this.typeFilter
                );
            }
            
            // Refresh DataTable if it exists
            if (this.dataTable) {
                this.dataTable.clear().draw();
                setTimeout(() => {
                    this.dataTable.draw();
                }, 100);
            }
        },

        initializeDataTable() {
            if ($.fn.DataTable.isDataTable('#question-table')) {
                $('#question-table').DataTable().destroy();
                $('#question-table').empty();
            }
            const stripHTML = function(data) {
                if (!data) return '';
                if (typeof data !== 'string') return data;
                const div = document.createElement('div');
                div.innerHTML = data;
                return (div.textContent || div.innerText || '').replace(/\s+/g, ' ').trim();
            };

            const exportConfig = {
                columns: ':not(:last-child)',
                format: {
                    body: (data, row, column, node) => {
                        if (column === 4) {
                            const rowData = $('#question-table').DataTable().row(row).data();
                            return rowData.options ? rowData.options.join(', ') : '';
                        }
                        return stripHTML(data);
                    }
                }
            };
            
            let columns = [
                { 
                    data: 'question_order',
                    width: '5%',
                    className: 'drag-cursor',
                    render: (d, t, r, meta) => meta.row + 1
                },
                { 
                    data: 'question',
                    width: '35%',
                    className: 'drag-cursor',
                    render: (data, type) => {
                        if (!data) return 'N/A';
                        if (type === 'display') {
                            const maxLength = 60;
                            const shortText = data.length > maxLength
                                ? data.substring(0, maxLength) + '...'
                                : data;
                            return `<span title="${data.replace(/"/g, '&quot;')}">${shortText}</span>`;
                        }
                        return data;
                    }
                },
                {
                    data: 'answer',
                    width: '25%',
                    className: 'drag-cursor',
                    render: (data, type) => {
                        if (!data) return '-';
                        if (type === 'display') {
                            const maxLength = 60;
                            return data.length > maxLength
                                ? `<span title="${data.replace(/"/g, '&quot;')}">${data.substring(0, maxLength)}...</span>`
                                : data;
                        }
                        return data;
                    }
                },
                {
                    data: 'category',
                    width: '15%',
                    className: 'drag-cursor',
                    render: (data) => data ? data.category : '-'
                },
                {
                    // signed_url / media
                    data: 'signed_url',
                    width: '10%',
                    orderable: false,
                    render: (data) => data
                        ? `<a href="${data}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fa fa-file"></i></a>`
                        : '<span class="text-muted">-</span>'
                },
                {
                    data: 'id',
                    width: '10%',
                    orderable: false,
                    render: (data) => `
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm edit-question-btn" data-id="${data}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-sm btn-light delete-question-btn" data-id="${data}">
                                <i class="fa fa-trash text-danger"></i>
                            </button>
                        </div>
                    `
                }
            ];

            // Initialize DataTable
            this.table = $('#question-table').DataTable({
                data: this.filteredQuestions,
                columns: columns,
                responsive: true,
                autoWidth: false,
                destroy: true,
                pageLength: 10,
                ordering: true,
                searching: true,
                rowReorder: {
                    dataSrc: '_order',
                    selector: '.drag-cursor'
                },
                info: true,
                language: {
                    emptyTable: "No questions available"
                },
                layout: {
                    topStart: {
                        buttons: [
                            { extend: "copy", text:'{{__("Copy")}}', className: "btn btn-outline-primary", attr: { title: "{{__('Copy to clipboard')}}", class: "btn btn-outline-primary" }, exportOptions: exportConfig },
                            { extend: "csv", text:'{{__("CSV")}}', className: "btn btn-outline-primary", attr: { title: "{{__('Export as CSV')}}", class: "btn btn-outline-primary" }, exportOptions: exportConfig },
                            { extend: "excel", text:'{{__("Excel")}}', className: "btn btn-outline-primary", attr: { title: "{{__('Export as Excel')}}", class: "btn btn-outline-primary" }, exportOptions: exportConfig },
                            { extend: "pdf", text:'{{__("PDF")}}', className: "btn btn-outline-primary", attr: { title: "{{__('Export as PDF')}}", class: "btn btn-outline-primary" }, exportOptions: exportConfig },
                            { extend: "print", text:'{{__("Print")}}', className: "btn btn-outline-primary", attr: { title: "{{__('Print')}}", class: "btn btn-outline-primary" }, exportOptions: exportConfig },
                            {
                                text: '<i class="fa fa-plus"></i> {{__("Add Question")}}',
                                className: 'btn btn-primary ms-2',
                                attr: {
                                    class: "btn btn-primary ms-2"
                                },
                                action: () => { new bootstrap.Modal(document.getElementById('question-modal')).show(); }
                            }
                        ],
                    }
                }
            });
            this.table.on('row-reorder', async (e, diff, edit) => {
                if (diff.length === 0) return;
                const orderUpdates = diff.map(item => ({
                    id: this.table.row(item.node).data().id,
                    order: item.newPosition + 1
                }));
                await this.updateQuestionOrder(orderUpdates);
            });
            $('#question-table').off('click', '.edit-question-btn')
                .on('click', '.edit-question-btn', (e) => {
                    const id = $(e.currentTarget).data('id');
                    this.editQuestion(id);
                });
            $('#question-table').off('click', '.delete-question-btn')
                .on('click', '.delete-question-btn', (e) => {
                    const id = $(e.currentTarget).data('id');
                    this.deleteQuestion(id);
                });
        },

        async updateQuestionOrder(orderUpdates) {
            try {
                this.ordering = true;
                console.log(orderUpdates);
                //return;
                const response = await fetch(`/faq/update-order`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ orders: orderUpdates })
                });

                const result = await response.json();
                
                if (result.success) {
                    // Update local data
                    orderUpdates.forEach(update => {
                        const question = this.questions.find(q => q.id === update.id);
                        if (question) {
                            question._order = update.order;
                        }
                    });
                    
                    // Show success message (optional)
                    notify('success', 'Order updated succesfully')
                    //console.log('Order updated successfully');
                    window.location.reload();
                    //await this.loadQuestions();
                } else {
                    notify('error', 'Failed to update order')
                    console.error('Failed to update order');
                    // Reload questions to reset the order
                    //await this.loadQuestions();
                }
            } catch (error) {
                console.error('Error updating order:', error);
                // Reload questions to reset the order
                await this.loadQuestions();
            }
            finally {
                this.ordering = false;
            }
        },
                
        populatefaqCategoryFilter(labels) {
            const select = document.getElementById('question-label-filter');

            select.innerHTML = `<option value="">All question labels</option>`;

            labels.sort().forEach(label => {
                const opt = document.createElement('option');
                opt.value = label;
                opt.textContent = label;
                select.appendChild(opt);
            });
        },

        initfaqCategoryFilter() {
            const table = this.dataTable;
            document
                .getElementById('question-label-filter')
                .addEventListener('change', function () {

                    const value = this.value;

                    if (!value) {
                        table.column(3).search('').draw();
                    } else {
                        // search inside badge text
                        table.column(3).search(value, true, false).draw();
                    }
                });
        },

        async deleteQuestion(questionId) {
            if (!confirm('Are you sure you want to delete this question?')) {
                return;
            }
            try {
                const response = await fetch(`/faq/delete/${questionId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // this.allQuestions = this.allQuestions.filter(question => question.id !== questionId);
                    // this.dataTable.clear();
                    // this.dataTable.rows.add(this.allQuestions);
                    // this.dataTable.draw();
                    //alert('Question deleted successfully');
                    notify('success', data.message || 'Question deleted successfully');

                    setTimeout(() => {
                        location.reload();
                    }, 1500)
                } else {
                    notify('error', data.message || 'Failed to delete question');
                    //alert(data.message || 'Failed to delete question');
                }
            } catch (error) {
                console.error('Error deleting question:', error);
                alert('Failed to delete question');
            }
        },

        resetQuestionForm() {
            this.isEdit = false;
            this.questionId = null;

            this.formData = {
                question: '',
                answer: '',
                category_id: null,
                file: null,
                question_type: '',
                parent_question_id: null,
                options: []
            };
        },

        openAddQuestionModal() {
            this.resetQuestionForm();
            const modal = new bootstrap.Modal(document.getElementById('question-modal'));
            modal.show();
        },

       openEditQuestionModal() {
            const modalEl = document.getElementById('question-modal');
            // Always get the existing instance or create a new one
            this.questionModalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            this.questionModalInstance.show();
        },

        openEditBellscaleOptions() {
            const modalEl = document.getElementById('bellscale-options-modal');

            if (!this.bellscaleModalInstance) {
                this.bellscaleModalInstance = new bootstrap.Modal(modalEl);
            }

            this.bellscaleModalInstance.show();
        },


        async editQuestion(id) {
            this.resetQuestionForm();
            this.isEdit = true;
            this.questionId = id;
            this.isLoadingEdit = true;
            this.openEditQuestionModal();

            try {
                const response = await fetch(`/faq/show/${id}`);
                if (!response.ok) throw new Error('Failed to fetch question');

                const question = await response.json();
                // 1. Populate Main Form
                this.formData.question = question.data.question;
                this.formData.answer = question.data.answer;
                this.formData.category_id = question.data.category?.id ?? null;
                console.log("Label", question.data.category?.category);

            } catch (error) {
                console.error(error);
                alert('Failed to fetch question data');
            } finally {
                this.isLoadingEdit = false;
            }
        },

 
        async addOrEditFaq() {
            this.isSaving = true;

            try {
                const formPayload = new FormData();
                    formPayload.append('question', this.formData.question);
                    formPayload.append('answer', this.formData.answer ?? '');
                    formPayload.append('category_id', this.formData.category_id);
                   if (this.isEdit && this.questionId) formPayload.append('id', this.questionId);
                    if (this.formData.file) formPayload.append('file', this.formData.file);

                    // for (let [key, value] of formData.entries()) {
                    //     console.log(key, value);
                    // }
                    // return;
                    const response = await fetch('/faq/save', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formPayload
                });
                const result = await response.json();
                if (!response.ok) {
                    if (result.errors) {
                        const errorMessages = Object.values(result.errors).flat().join('\n');
                        throw new Error(errorMessages);
                    }
                    throw new Error(result.message || 'Save failed');
                }

                notify('success', result.message || 'Question saved successfully!');

                bootstrap.Modal.getInstance(
                    document.getElementById('question-modal')
                ).hide();

                this.resetQuestionForm();
                this.isEdit = false;

                setTimeout(() => location.reload(), 1200);

            } catch (error) {
                notify('error', error.message);
                console.error('Save error:', error);
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
    
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/trash_popup.js') }}"></script>
    <script src="{{ asset('assets/js/common-check.js') }}"></script>

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
