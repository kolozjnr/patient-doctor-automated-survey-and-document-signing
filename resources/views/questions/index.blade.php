@extends('layouts.simple.master')

@section('title', 'Questions List')

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
                    <h3>{{__(ucfirst($module))}} {{__("Questions")}}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"> <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">{{__("Questions")}}</li>
                        <li class="breadcrumb-item active">{{__(ucfirst($module))}} {{__("Questions")}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div><!-- Container-fluid starts-->
    <section x-data="QuestionsComponent('{{ $module }}')"
    @click="
        const editBtn = $event.target.closest('.edit-question-btn');
        const deleteBtn = $event.target.closest('.delete-question-btn');

        if (editBtn) editQuestion(editBtn.dataset.id);
        if (deleteBtn) deleteQuestionById(deleteBtn.dataset.id);
    "
>
 {{-- New Question modal --}}
    <div class="col-md-6">
        <div class="modal fade" id="bellscale-options-modal" tabindex="-1" role="dialog"
        aria-labelledby="bellscale-options-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content" :class="{ 'content-loading': isLoadingEdit }">
                <div class="modal-header">
                    <h5 class="modal-title" x-text="isEdit ? 'Manage Option' : 'Manage Option'"></h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body custom-scrollbar">
                    <form method="POST" @submit.prevent="addOrEditOption" :class="{ 'opacity-50 pointer-events-none': isLoadingEdit }">
                        @csrf
                        <input type="hidden" x-model="questionId">
                        
            

                        {{-- Options --}}
                        <div class="form-group mb-2">
                            <template x-for="(field, index) in bellScaleFormData.options" :key="index">
                                <div class="mb-2"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0">

                                    <div class="row align-items-end g-2">

                                        <!-- Option -->
                                        <div class="col-md-7">
                                            <label class="form-label">{{__("Option")}}</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder="e.g Yes"
                                                x-model="field.option"
                                                
                                            >
                                        </div>

                                        <!-- Option Value -->
                                        <div class="col-md-2">
                                            <label class="form-label">{{__("Value")}}</label>
                                            <input
                                                type="number"
                                                class="form-control"
                                                placeholder="e.g 10"
                                                x-model="field.option_value"
                                                
                                            >
                                        </div>

                                        <!-- Actions -->
                                        <div class="col-md-3">
                                            <label class="form-label d-block">&nbsp;</label>
                                            <div class="d-flex gap-1">
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-primary btn-sm"
                                                    @click="duplicateBellscaleOptionField(index)"
                                                    title="Duplicate"
                                                >
                                                    <i class="fa fa-copy"></i>
                                                </button>

                                                <button
                                                    type="button"
                                                    class="btn btn-outline-danger btn-sm"
                                                    @click="removeBellscaleOptionField(index)"
                                                    x-show="bellScaleFormData.options.length > 1"
                                                    title="Remove"
                                                >
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </template>

                            <!-- Add new empty row -->
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-secondary mt-2"
                                @click="addBellscaleOptionField"
                            >
                                <i class="fa fa-plus"></i> {{__("Add option")}}
                            </button>
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

    <div class="modal fade" id="question-modal" tabindex="-1" role="dialog"
        aria-labelledby="question-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content" :class="{ 'content-loading': isLoadingEdit }">
                <div class="modal-header">
                    <h5 class="modal-title" x-text="isEdit ? 'Edit Question' : 'Add New Question'"></h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body custom-scrollbar">
                    <form method="POST" @submit.prevent="addOrEditQuestion" :class="{ 'opacity-50 pointer-events-none': isLoadingEdit }">
                        @csrf
                        <input type="hidden" x-model="questionId">
                        
                        <!-- Question ID -->
                        <div class="form-group mb-3">
                            <label for="question">  <i class="fa fa-question"></i> {{__("Question")}}</label>
                            <textarea name="question" class="form-control" x-model="formData.question" id="" cols="2" rows="2"></textarea>
                        </div>

                        <div class="form-group mb-3" x-show="!isBellScale()" x-cloak>
                            <label>{{__("Category")}}</label>
                                <div class="d-flex gap-2 mb-2">
                                    <select class="form-control" x-model="formData.questionlabel" >
                                        <option value="">Select Label</option>
                                        @foreach($questionLabel as $question)
                                            <option value="{{ $question->id }}">{{ $question->label }}</option>
                                        @endforeach
                                    </select>

                                </div>
                        </div>

                        

                        <!--  -->
                        <div class="form-group mb-3">
                            <label>  <i class="fa fa-list-ul"></i> {{__("Question Type")}}</label>
                                <select class="form-control" x-model="formData.question_type">
                                    <template x-for="type in availableQuestionTypes" :key="type.value">
                                        <option :value="type.value" x-text="type.label"></option>
                                    </template>
                                </select>
                        </div>
                        {{-- <div class="form-group mb-3" x-show="!isBellScale()" x-cloak>
                            <label>  <i class="fa fa-list-ul"></i> Question Type</label>
                                <select class="form-control" x-model="formData.question_type">
                                    <option value="">Select Type</option>
                                    <option value="text">Text</option>
                                    <option value="yes_no">Yes/No</option>
                                    <option value="rating">Ratings</option>
                                    <option value="scores">Scores</option>
                                    <option value="single_option">Single Option</option>
                                    <option value="dropdown">Dropdown</option>
                                    <option value="yes_no_with_extra">Yes/No With Extra</option>
                                    <option value="yes_no_with_checkbox">Yes/No With Checkbox</option>
                                    <option value="yes_no_with_multi_checkbox">Yes/With Multi Checkbox</option>
                                    <option value="yes_no_with_question">Yes/No With Question</option>
                                    <option value="yes_no_with_popup">Yes/No With Pop Up</option>
                                    <option value="yes_no_not_sure">Yes/No Not Sure</option>
                                    <option value="month_year">Month/Year</option>
                                    <option value="date">Date</option>
                                </select>
                        </div> --}}

                        <div class="form-group mb-2" x-show="!isBellScale() && showSubQuestionSection()"  x-transition x-cloak >
                            {{-- <input type="text" class="form-control" x-model="formData.subquestion" placeholder="Sub Question"> --}}
                            <!-- Sub Question Section -->
                            <div class="sub-question-section">
                                <div class="mb-4">
                                    <label for="subQuestion" class="form-label">Sub Question Text</label>
                                    <input type="text" class="form-control" id="subQuestion"  x-model="singleSubQuestion.subquestion_with_question_text" name="subquestion_with_question_text" placeholder="{{ __('Enter your sub question here...') }}" style="width: 90% !important;">
                                </div>
{{-- 
                                <div class="section-title" style="font-size: 16px; margin-top: 30px;">
                                    <i class="bi bi-ui-radios"></i>
                                    Answer Format
                                </div> --}}

                                <div class="question-types-grid">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answerType" id="text" x-model="singleSubQuestion.subquestion_with_question_answerType" value="text">
                                        <label class="form-check-label" for="text">
                                            <i class="fa fa-text-width"></i> Text
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answerType"  id="yesNo" x-model="singleSubQuestion.subquestion_with_question_answerType" value="yes_no">
                                        <label class="form-check-label" for="yesNo">
                                            <i class="fa fa-check-circle"></i> Yes / No
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answerType"  id="rating" x-model="singleSubQuestion.subquestion_with_question_answerType" value="rating">
                                        <label class="form-check-label" for="rating">
                                            <i class="fa fa-star"></i> Rating
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answerType"  id="dropdown" x-model="singleSubQuestion.subquestion_with_question_answerType" value="dropdown">
                                        <label class="form-check-label" for="dropdown">
                                            <i class="fa fa-caret-down-square"></i> Dropdown
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answerType"  id="yesNoExtra" x-model="singleSubQuestion.subquestion_with_question_answerType" value="yes_no_with_extra">
                                        <label class="form-check-label" for="yesNoExtra">
                                            <i class="fa fa-plus-circle"></i> Yes / No With Extra
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answerType"  id="yesNoMultiple" x-model="singleSubQuestion.subquestion_with_question_answerType" value="yes_no_with_multi_checkbox">
                                        <label class="form-check-label" for="yesNoMultiple">
                                            <i class="fa fa-check2-square"></i> Yes / No With Multiple Checkbox
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answerType"  id="yesNoCheckbox" x-model="singleSubQuestion.subquestion_with_question_answerType" value="yes_no_checkbox">
                                        <label class="form-check-label" for="yesNoCheckbox">
                                            <i class="fa fa-ui-checks"></i> Yes / No With Checkbox
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answerType"  id="yesNoNotSure" x-model="singleSubQuestion.subquestion_with_question_answerType" value="yes_no_not_sure">
                                        <label class="form-check-label" for="yesNoNotSure">
                                            <i class="fa fa-question-circle"></i> Yes / No / Not Sure
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answerType"  id="monthYear" x-model="singleSubQuestion.subquestion_with_question_answerType" value="month_year">
                                        <label class="form-check-label" for="monthYear">
                                            <i class="fa fa-calendar-month"></i> Month / Year
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answerType"  id="date" x-model="singleSubQuestion.subquestion_with_question_answerType" value="date">
                                        <label class="form-check-label" for="date">
                                            <i class="fa fa-calendar-date"></i> Date
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Ratings with text --}}
                        <div class="form-group mb-2"  x-show="!isBellScale() && showRatingsWithText()"  x-transition x-cloak >
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label">{{__("First Text")}}</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    placeholder=""
                                                    x-model="formData.rating_first_text"
                                                >
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">{{__("Last Text")}}</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    placeholder=""
                                                    x-model="formData.rating_last_text"
                                                >
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                        

                        {{-- Sub questions with popup --}}
                        <div class="form-group mb-2" x-show="!isBellScale() && showSubQuestionSectionPopup()">
                            <div class="card-body">

                                <!-- Sub Questions Container -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <label class="form-label mb-0">
                                            <i class="fa fa-plus-circle"></i> {{__("Sub Questions")}}
                                        </label>
                                        <span class="question-badge">
                                            <span x-text="subQuestions.length"></span> {{__("Question")}}<span x-show="subQuestions.length !== 1">s</span>
                                        </span>
                                    </div>

                                    <template x-for="(question, index) in subQuestions" :key="question.id">
                                        <div class="sub-question-card">
                                            <button 
                                                type="button" 
                                                class="btn-remove"
                                                @click="removeQuestion(index)"
                                                x-show="subQuestions.length > 1"
                                                title="Remove question"
                                            >
                                                <i class="fa fa-trash"></i>
                                            </button>

                                            <div class="sub-question-header">
                                                <i class="bi bi-patch-question" style="font-size: 24px; color: #667eea;"></i>
                                                <input type="text" class="form-control" placeholder="Enter your sub question here..." x-model="question.subquestion_with_popup_text" style="width: 90% !important;">
                                            </div>

                                            {{-- <div class="section-label">
                                                <i class="bi bi-ui-radios"></i> Answer Format
                                            </div> --}}

                                            <div class="radio-options">
                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="radio" 
                                                        :name="'answerType' + question.id" 
                                                        :id="'text' + question.id"
                                                        value="text"
                                                        x-model="question.subquestion_with_popup_answerType"
                                                    >
                                                    <label class="form-check-label" :for="'text' + question.id">
                                                        <i class="fa fa-pencil"></i> Text
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="radio" 
                                                        :name="'answerType' + question.id" 
                                                        :id="'yesNo' + question.id"
                                                        value="yesNo"
                                                        x-model="question.subquestion_with_popup_answerType"
                                                    >
                                                    <label class="form-check-label" :for="'yesNo' + question.id">
                                                        <i class="fa fa-check-circle"></i> Yes / No
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="radio" 
                                                        :name="'answerType' + question.id" 
                                                        :id="'rating' + question.id"
                                                        value="rating"
                                                        x-model="question.subquestion_with_popup_answerType"
                                                    >
                                                    <label class="form-check-label" :for="'rating' + question.id">
                                                        <i class="fa fa-star"></i> Rating
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="radio" 
                                                        :name="'answerType' + question.id" 
                                                        :id="'dropdown' + question.id"
                                                        value="dropdown"
                                                        x-model="question.subquestion_with_popup_answerType"
                                                    >
                                                    <label class="form-check-label" :for="'dropdown' + question.id">
                                                        <i class="fa fa-caret-down-square"></i> Dropdown
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="radio" 
                                                        :name="'answerType' + question.id" 
                                                        :id="'yesNoExtra' + question.id"
                                                        value="yesNoExtra"
                                                        x-model="question.subquestion_with_popup_answerType"
                                                    >
                                                    <label class="form-check-label" :for="'yesNoExtra' + question.id">
                                                        <i class="fa fa-plus-circle"></i> Yes / No With Extra
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="radio" 
                                                        :name="'answerType' + question.id" 
                                                        :id="'yesNoMultiple' + question.id"
                                                        value="yesNoMultiple"
                                                        x-model="question.subquestion_with_popup_answerType"
                                                    >
                                                    <label class="form-check-label" :for="'yesNoMultiple' + question.id">
                                                        <i class="fa fa-check-square"></i> Yes / No Multiple Checkbox
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="radio" 
                                                        :name="'answerType' + question.id" 
                                                        :id="'yesNoCheckbox' + question.id"
                                                        value="yesNoCheckbox"
                                                        x-model="question.subquestion_with_popup_answerType"
                                                    >
                                                    <label class="form-check-label" :for="'yesNoCheckbox' + question.id">
                                                        <i class="fa fa-check-square"></i> Yes / No With Checkbox
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="radio" 
                                                        :name="'answerType' + question.id" 
                                                        :id="'yesNoNotSure' + question.id"
                                                        value="yesNoNotSure"
                                                        x-model="question.subquestion_with_popup_answerType"
                                                    >
                                                    <label class="form-check-label" :for="'yesNoNotSure' + question.id">
                                                        <i class="fa fa-question-circle"></i> Yes / No / Not Sure
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="radio" 
                                                        :name="'answerType' + question.id" 
                                                        :id="'monthYear' + question.id"
                                                        value="monthYear"
                                                        x-model="question.subquestion_with_popup_answerType"
                                                    >
                                                    <label class="form-check-label" :for="'monthYear' + question.id">
                                                        <i class="fa fa-calendar"></i> Month / Year
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="radio" 
                                                        :name="'answerType' + question.id" 
                                                        :id="'date' + question.id"
                                                        value="date"
                                                        x-model="question.subquestion_with_popup_answerType"
                                                    >
                                                    <label class="form-check-label" :for="'date' + question.id">
                                                        <i class="fa fa-calendar"></i> {{__("Date")}}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <!-- Add Button -->
                                    <div class="add-section">
                                        <button type="button" class="btn btn-primary" @click="addQuestion()">
                                            <i class="fa fa-plus-circle"></i> {{__("Add Sub Question")}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Options --}}
                        <div class="form-group mb-2" x-show="showOptions()">
                            <template x-for="(field, index) in formData.options" :key="index">
                                <div class="mb-2"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0">

                                    <div class="row align-items-end g-2">

                                        <!-- Option -->
                                        <div class="col-md-7">
                                            <label class="form-label">{{__("Option")}}</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder="e.g Yes"
                                                x-model="field.option"
                                                
                                            >
                                        </div>

                                        <!-- Option Value -->
                                        <div class="col-md-2">
                                            <label class="form-label">{{__("Value")}}</label>
                                            <input
                                                type="number"
                                                class="form-control"
                                                placeholder="e.g 10"
                                                x-model="field.option_value"
                                                
                                            >
                                        </div>

                                        <!-- Actions -->
                                        <div class="col-md-3">
                                            <label class="form-label d-block">&nbsp;</label>
                                            <div class="d-flex gap-1">
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-primary btn-sm"
                                                    @click="duplicateOptionField(index)"
                                                    title="Duplicate"
                                                >
                                                    <i class="fa fa-copy"></i>
                                                </button>

                                                <button
                                                    type="button"
                                                    class="btn btn-outline-danger btn-sm"
                                                    @click="removeOptionField(index)"
                                                    x-show="formData.options.length > 1"
                                                    title="Remove"
                                                >
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </template>

                            <!-- Add new empty row -->
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-secondary mt-2"
                                @click="addOptionField"
                            >
                                <i class="fa fa-plus"></i> {{__("Add option")}}
                            </button>
                        </div>



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
                                        <th class="c-o-light f-w-600">{{__("Type")}}</th>
                                        <th class="c-o-light f-w-600" x-show="!isBellScale()">{{__("Question Label")}}</th>
                                        <th><span class="c-o-light f-w-600">{{__("Options")}}</span></th>
                                        <th><span class="c-o-light f-w-600">{{__("Action")}}</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <!-- Loading State -->
                                    <template x-if="isLoading">
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">{{__("Loading...")}}}</span>
                                                </div>
                                                <p class="mt-2">{{__("Loading questions...")}}</p>
                                            </td>
                                        </tr>
                                    </template>
                                    
                                    <!-- Empty State -->
                                    <template x-if="!isLoading && filteredQuestions.length === 0">
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <p class="text-muted">No questions found</p>
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
    Alpine.data('QuestionsComponent', (module) => ({
        module,
        isLoading: false,
        isEdit: false,
        isSaving: false,
        isLoadingEdit: false,
        ordering: false,
        //isBellScale: false,

        questionId: null,
        allQuestions: [],
        dataTable: null,

        questions: [],
        filteredQuestions: [],
        typeFilter: '',

        formData: {
            question: '',
            question_type: '',
            parent_question_id: null,
            module: module,
            optionsInput: '',
            questionlabel: '',
            rating_first_text: '',
            rating_last_text: '',
            options: [
                {
                    option: '',
                    option_value: ''
                }
            ]
        },

        bellScaleFormData: {
            options: [
                {
                    option: '',
                    option_value: ''
                }
            ]
        },

        get availableQuestionTypes() {

        const allTypes = [
            { value: 'text', label: 'Text' },
            { value: 'yes_no', label: 'Yes/No' },
            { value: 'rating', label: 'Ratings' },
            { value: 'rating_with_text', label: 'Ratings With Text' },
            { value: 'scores', label: 'Scores' },
            { value: 'single_option', label: 'Single Option' },
            { value: 'dropdown', label: 'Dropdown' },
            { value: 'yes_no_with_extra', label: 'Yes/No With Extra' },
            { value: 'yes_no_with_checkbox', label: 'Yes/No With Checkbox' },
            { value: 'yes_no_with_multi_checkbox', label: 'Yes/With Multi Checkbox' },
            { value: 'yes_no_with_question', label: 'Yes/No With Question' },
            { value: 'yes_no_with_popup', label: 'Yes/No With Pop Up' },
            { value: 'yes_no_not_sure', label: 'Yes/No Not Sure' },
            { value: 'month_year', label: 'Month/Year' },
            { value: 'date', label: 'Date' },
            { value: 'duration', label: 'Duration' },
        ];

        if (this.isBellScale()) {
            return allTypes.filter(type =>
                ['text', 'yes_no', 'rating', 'scores'].includes(type.value)
            );
        }

        return allTypes;
    },


         isBellScale() {
            return this.module === 'bellscale';
        },

         shouldUseDarkText(hexColor) {
            hexColor = hexColor.replace('#', '');
            const r = parseInt(hexColor.substr(0, 2), 16);
            const g = parseInt(hexColor.substr(2, 2), 16);
            const b = parseInt(hexColor.substr(4, 2), 16);
            const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
            return luminance > 0.5;
        },
        
        
    questionModalInstance: null,
    bellscaleModalInstance: null,
        
        mainType: 'Yes / No With Popup',
        nextId: 1,
        
        // ✅ FIX: Start with EMPTY array instead of array with empty object
        subQuestions: [],
        singleSubQuestion: { // For yes_no_with_question (single)
            subquestion_with_question_text: '',
            subquestion_with_question_answerType: ''
        },

        // ✅ FIX: Add subquestion based on question type
        addQuestion() {
            if (this.formData.question_type === 'yes_no_with_popup') {
                this.subQuestions.push({
                    id: this.nextId++,
                    subquestion_with_popup_text: '',
                    subquestion_with_popup_answerType: ''
                });
            }
        },

        removeQuestion(index) {
            if (this.subQuestions.length > 0) {
                this.subQuestions.splice(index, 1);
            }
        },

        addOptionField() {
            this.formData.options.push({
                option: '',
                option_value: ''
            });
        },
        addBellscaleOptionField() {
            this.bellScaleFormData.options.push({
                option: '',
                option_value: ''
            });
        },
        duplicateOptionField(index) {
            const current = this.formData.options[index];

            this.formData.options.splice(index + 1, 0, {
                option: current.option,
                option_value: current.option_value
            });
        },

        removeOptionField(index) {
            if (this.formData.options.length > 1) {
                this.formData.options.splice(index, 1);
            }
        },

        duplicateBellscaleOptionField(index) {
            const current = this.bellScaleFormData.options[index];

            this.bellScaleFormData.options.splice(index + 1, 0, {
                option: current.option,
                option_value: current.option_value
            });
        },

        removeBellscaleOptionField(index) {
            if (this.bellScaleFormData.options.length > 1) {
                this.bellScaleFormData.options.splice(index, 1);
            }
        },

        //THIS WAS BEFORE WE ADDED THE OPTION VALUES

        // addOption() {
        //     const value = this.formData.optionsInput.trim();
        //     if (!value) return;
        //     if (!this.formData.options.includes(value)) {
        //         this.formData.options.push(value);
        //     }
        //     this.formData.optionsInput = '';
        // },

        // removeOption(index) {
        //     this.formData.options.splice(index, 1);
        // },

        showRatingsWithText(){
            return this.formData.question_type === 'rating_with_text';
        },


       showOptionsForRadio(answerType) {
            return [
                'dropdown',
                'yes_no_with_multi_checkbox',
                'yes_no_with_checkbox'
            ].includes(answerType);
        },


          showOptions() {
            const isMainTypeWithOptions = [
                'dropdown',
                'yes_no_with_multi_checkbox',
                'yes_no_with_checkbox',
                'single_option'
            ].includes(this.formData.question_type);

            const isSubTypeWithOptions = [
                'dropdown',
                'single_option',
                'yes_no_with_checkbox',
                'yes_no_with_multi_checkbox'
            ].includes(this.singleSubQuestion.subquestion_with_question_answerType);

            const isSubTypePopupWithOptions = this.subQuestions.some(sub => {
                return [
                    'dropdown',
                    'single_option',
                    'yes_no_with_checkbox',
                    'yes_no_with_multi_checkbox'
                ].includes(sub.subquestion_with_popup_answerType);
            });

            return isMainTypeWithOptions || isSubTypeWithOptions || isSubTypePopupWithOptions;
        },

        resetOptions() {
            this.formData.options = [];
            this.formData.optionsInput = '';
        },

        showSubQuestionSection() {
            return this.formData.question_type === 'yes_no_with_question';
        },

        showSubQuestionSectionPopup() {
            return this.formData.question_type === 'yes_no_with_popup';
        },

        
        resetSubQuestions() {
            this.subQuestions = []; // For popup (multiple)
            this.singleSubQuestion = {
                subquestion_with_question_text: '',
                subquestion_with_question_answerType: ''
            };
        },

      async init() {
            this.$watch('formData.question_type', (newValue, oldValue) => {
                // When question type changes, clear subquestions
                if (!this.isLoadingEdit && oldValue && oldValue !== newValue) {
                    this.resetSubQuestions();
                }
                const modalEl = document.getElementById('question-modal');
                modalEl.addEventListener('hidden.bs.modal', () => {
                    this.resetQuestionForm();
                    this.resetSubQuestions();
                });

                const wasShowingOptions = [
                    'dropdown',
                    'yes_no_with_multi_checkbox',
                    'yes_no_with_checkbox',
                    'single_option'
                ].includes(oldValue);

                const isShowingOptions = this.showOptions();

                if (wasShowingOptions && !isShowingOptions) {
                    this.resetOptions();
                }
            });
             await this.loadQuestions();
            this.$nextTick(() => {
                this.initializeDataTable();
                this.initQuestionLabelFilter();
            });
        },

        // All Questions
        async loadQuestions() {
            this.isLoading = true;
            try {
                const response = await fetch(`/questions/questions_get/${module}`);
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
                this.populateQuestionLabelFilter([...labelSet]);

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
            const isBell = this.isBellScale();
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
                    data: '_order',
                    width: '5%',
                    className: 'drag-cursor',
                    render: (d, t, r, meta) => meta.row + 1
                },
                { 
                    data: 'question',
                    width: isBell ? '55%' : '35%',
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
                        data: 'type',
                        width: '15%',
                        className: 'drag-cursor',
                        render: data =>
                            data
                                ? data.charAt(0).toUpperCase() + data.slice(1)
                                : '-'
                    },
                    {
                        data: 'label',
                        width: '15%',
                        className: 'drag-cursor',
                        render: data =>
                            data && data.label
                                ? `<span class="badge" style="background-color:${data.color}">
                                        ${data.label}
                                </span>`
                                : '-'
                    },
                    {
                    data: 'options',
                    width: '20%',
                    orderable: false,
                    render: (data, type, row) => {

                        if (isBell) {
                            return `
                                <button class="btn btn-sm btn-outline-primary manage-options-btn" data-id="${row.id}">
                                    {{__("Manage Options")}}
                                </button>
                            `;
                        }

                        if (!data || !data.length) {
                            return '<span class="text-muted">-</span>';
                        }

                        // Rating with text — show first/last text label
                        if (row.type === 'rating_with_text') {
                            const ratingOption = data.find(opt => opt.display_order === 0);
                            if (ratingOption) {
                                return `
                                        ${ratingOption.rating_first_text ?? '-'} → ${ratingOption.rating_last_text ?? '-'}
                                    
                                `;
                                // return `
                                //     <span class="badge bg-light text-dark border">
                                //         ${ratingOption.rating_first_text ?? '-'} → ${ratingOption.rating_last_text ?? '-'}
                                //     </span>
                                // `;
                            }
                            return '<span class="text-muted">-</span>';
                        }

                        // Rating — no options to show
                        if (row.type === 'rating') {
                            return '<span class="text-muted">-</span>';
                        }

                        // Yes/No types — no options to show
                        if (['yes_no', 'yes_no_not_sure', 'yes_no_with_extra', 'yes_no_with_question', 'yes_no_with_popup'].includes(row.type)) {
                            return '<span class="text-muted">-</span>';
                        }

                        // Default — dropdown, single_option, yes_no_with_checkbox, yes_no_with_multi_checkbox
                        const optionTexts = data
                            .filter(opt => opt.display_order !== 0) // exclude rating row
                            .map(opt => opt.option_text)
                            .filter(Boolean)
                            .join(', ');

                        return optionTexts || '<span class="text-muted">-</span>';
                    }
                },

                {
                    data: 'id',
                    width: '15%',
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
                },
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
                columnDefs: [
                    {
                        targets: 3,          // Question Label column (0-indexed)
                        visible: !isBell,    // ✅ hide when bellscale, show otherwise
                    }
                ],
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
                            { extend: "copy", text:"{{__('Copy')}}", className: "btn btn-outline-primary", attr: { title: "Copy to clipboard", class: "btn btn-outline-primary" }, exportOptions: exportConfig },
                            { extend: "csv", className: "btn btn-outline-primary", attr: { title: "Export as CSV", class: "btn btn-outline-primary" }, exportOptions: exportConfig },
                            { extend: "excel", className: "btn btn-outline-primary", attr: { title: "Export as Excel", class: "btn btn-outline-primary" }, exportOptions: exportConfig },   
                            { extend: "pdf", className: "btn btn-outline-primary", attr: { title: "Export as PDF", class: "btn btn-outline-primary" }, exportOptions: exportConfig },
                            { extend: "print", text:"{{__('Print')}}", className: "btn btn-outline-primary", attr: { title: "Print", class: "btn btn-outline-primary" }, exportOptions: exportConfig },
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

            // Manage Options (Bellscale only)
            if (isBell) {
                $('#question-table').off('click', '.manage-options-btn')
                    .on('click', '.manage-options-btn', (e) => {
                        const id = $(e.currentTarget).data('id');
                        this.openManageOptionsModal(id);
                    });
            }
        },

        async updateQuestionOrder(orderUpdates) {
            try {
                this.ordering = true;
                const response = await fetch(`/questions/update-order/${module}`, {
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
                
        populateQuestionLabelFilter(labels) {
            const select = document.getElementById('question-label-filter');

            select.innerHTML = `<option value="">All question labels</option>`;

            labels.sort().forEach(label => {
                const opt = document.createElement('option');
                opt.value = label;
                opt.textContent = label;
                select.appendChild(opt);
            });
        },

        initQuestionLabelFilter() {
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
                const response = await fetch(`/questions/delete/${questionId}`, {
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
            this.subQuestions = [];
            this.resetSubQuestions();

            this.formData = {
                question: '',
                question_type: '',
                parent_question_id: null,
                module: this.module,
                questionlabel: '',
                optionsInput: '',
                rating_first_text: '',
                rating_last_text: '',
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

            if (!this.questionModalInstance) {
                this.questionModalInstance = new bootstrap.Modal(modalEl);
            }

            this.questionModalInstance.show();
        },

        openEditBellscaleOptions() {
            const modalEl = document.getElementById('bellscale-options-modal');

            if (!this.bellscaleModalInstance) {
                this.bellscaleModalInstance = new bootstrap.Modal(modalEl);
            }

            this.bellscaleModalInstance.show();
        },

        async openManageOptionsModal(questionId) {
            this.questionId = questionId;
            this.isEdit = false;
            
            // Reset form before fetching
            this.bellScaleFormData = {
                question: '',
                options: [{ option: '', option_value: '' }]
            };

            this.isLoadingEdit = true;
            this.openEditBellscaleOptions();

            try {
                const response = await fetch(`/questions/bellscale_options/${questionId}`, {
                    headers: { 
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const result = await response.json();

                if (result.success) {
                    this.bellScaleFormData.options = result.data.length
                        ? result.data.map(opt => ({
                            option: opt.option_text,
                            option_value: opt.option_value ?? ''
                        }))
                        : [{ option: '', option_value: '' }];
                }
            } catch (error) {
                console.error('Failed to fetch bellscale options:', error);
            } finally {
                this.isLoadingEdit = false;
            }
        },

        async addOrEditOption() {
            this.isSaving = true;

            try {
                const payload = {
                    question_id: this.questionId,
                    options: this.bellScaleFormData.options
                };

                const response = await fetch(`/questions/save_options`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.success) {
                    bootstrap.Modal.getInstance(
                        document.getElementById('bellscale-options-modal')
                    ).hide();
                    notify('success', data.message || 'Option updated successfully');
                    this.initializeDataTable();
                } else {
                    alert(result.message);
                }
            } catch (error) {
                console.error('Failed to save bellscale options:', error);
            } finally {
                this.isSaving = false;
            }
        },


        async editQuestion(id) {
            this.resetQuestionForm();
            this.isEdit = true;
            this.questionId = id;
            this.isLoadingEdit = true;
            this.openEditQuestionModal();

            try {
                const response = await fetch(`/questions/edit/${id}`);
                if (!response.ok) throw new Error('Failed to fetch question');

                const question = await response.json();
                
                // 1. Populate Main Form
                this.formData.question = question.data.question;
                this.formData.question_type = question.data.type;
                this.formData.questionlabel = question.data.label?.id ?? null;
                this.formData.rating_first_text = question.data.rating_first_text ?? null;
                this.formData.rating_last_text = question.data.rating_last_text   ?? null;
                console.log("Label", question.data.label?.label);
                //this.formData.options = question.data.options || [];
                this.formData.options = [];
                    if (Array.isArray(question.data.options)) {
                        question.data.options.forEach(opt => {
                            console.log('Option Value:', typeof opt);

                            // CASE 1: Already correct format
                            if (typeof opt === 'object' && opt.option !== undefined) {
                                this.formData.options.push({
                                    option: opt.option_text,
                                    option_value: opt.option_value ?? ''
                                });
                            }

                            // CASE 2: "Yes|10"
                            else if (typeof opt === 'string' && opt.includes('|')) {
                                const [option, value] = opt.split('|');
                                this.formData.options.push({
                                    option: option_text.trim(),
                                    option_value: option_value ? Number(option_value) : ''
                                });
                            }

                            // CASE 3: "Yes"
                            else if (typeof opt === 'string') {
                                this.formData.options.push({
                                    option: opt,
                                    option_value: ''
                                });
                            }

                            // CASE 4: { Yes: 10 }
                            else if (typeof opt === 'object') {
                                const key = Object.keys(opt)[0];
                                console.log('Key:', key, 'Value:', opt[key]);
                                // this.formData.options.push({
                                //     option: key,
                                //     option_value: opt[key]
                                // });

                               // this wprkd with optoos relationship
                                this.formData.options.push({
                                    option: opt.option_text,
                                    option_value: opt.option_value !== null ? parseInt(opt.option_value): ''
                                });

                            }

                        });
                    }

                    // Ensure at least one row exists
                    if (this.formData.options.length === 0) {
                        this.formData.options.push({ option: '', option_value: '' });
                    }

                // 2. Handle Sub-questions based on type
                if (question.data.children && question.data.children.length > 0) {
                    
                    if (this.formData.question_type === 'yes_no_with_popup') {
                        // Populate the ARRAY for multiple sub-questions
                        this.subQuestions = question.data.children.map(child => ({
                            id: child.id,
                            subquestion_with_popup_text: child.question,
                            subquestion_with_popup_answerType: child.type
                        }));
                    } 
                    else if (this.formData.question_type === 'yes_no_with_question') {
                        const firstChild = question.data.children[0];
                        console.log(firstChild);
                        console.log('subDOM', this.singleSubQuestion);
                        this.singleSubQuestion = {
                            id: firstChild.id,
                            subquestion_with_question_text: firstChild.question,
                            subquestion_with_question_answerType: firstChild.type
                        };
                    }
                }

            } catch (error) {
                console.error(error);
                alert('Failed to fetch question data');
            } finally {
                this.isLoadingEdit = false;
            }
        },

        async addOrEditQuestion() {
            this.isSaving = true;

            try {
                const payload = {
                    question: this.formData.question,
                    module: this.module,
                    question_type: this.formData.question_type,
                    questionlabel: this.formData.questionlabel,
                    question_id: this.isEdit ? this.questionId : null,
                    parent_question_id: this.formData.parent_question_id,
                    rating_first_text: this.formData.rating_first_text,
                    rating_last_text: this.formData.rating_last_text,
                    options: this.formData.options
                };

                //console.log('Payload:', payload);
                //return;
                if (this.formData.question_type === 'yes_no_with_question') {
                    if (this.singleSubQuestion.subquestion_with_question_text && 
                        this.singleSubQuestion.subquestion_with_question_text.trim() !== '' &&
                        this.singleSubQuestion.subquestion_with_question_answerType && 
                        this.singleSubQuestion.subquestion_with_question_answerType.trim() !== '') {
                        
                        payload.subQuestion = this.singleSubQuestion; // Singular, not array
                    }
                }
                
                if (this.formData.question_type === 'yes_no_with_popup') {
                    const validSubQuestions = this.subQuestions.filter(sub => {
                        return sub.subquestion_with_popup_text && 
                            sub.subquestion_with_popup_text.trim() !== '' &&
                            sub.subquestion_with_popup_answerType && 
                            sub.subquestion_with_popup_answerType.trim() !== '';
                    });

                    if (validSubQuestions.length > 0) {
                        payload.subQuestions = validSubQuestions; // Plural, array
                    }
                }

                console.log('Payload:', payload);
                //return; 
                
                const response = await fetch('/questions/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
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
