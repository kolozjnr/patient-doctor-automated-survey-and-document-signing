@extends('layouts.simple.master')

@section('title', 'Questions List')

@section('css')

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">


    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

@endsection

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
    </style>

@section('main_content')
<section
    x-data="QuestionsComponent('{{ $module }}')"
    @click="
        const editBtn = $event.target.closest('.edit-question-btn');
        const deleteBtn = $event.target.closest('.delete-question-btn');

        if (editBtn) editQuestion(editBtn.dataset.id);
        if (deleteBtn) deleteQuestionById(deleteBtn.dataset.id);
    "
>
 <div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>{{ ucfirst($module) }} List</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"> <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                            </svg></a></li>
                    <li class="breadcrumb-item">Questions</li>
                    <li class="breadcrumb-item active">Questions List</li>
                </ol>
            </div>
        </div>
        
    </div>
    {{-- New Question modal --}}
    <div class="col-md-6">
    <div class="modal fade" id="question-modal" tabindex="-1" role="dialog"
        aria-labelledby="question-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
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
                            <label for="question">  <i class="fa fa-question"></i> Question</label>
                            <textarea name="question" class="form-control" x-model="formData.question" id="" cols="2" rows="2"></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label>Question Labels</label>
                                <div class="d-flex gap-2 mb-2">
                                    <select class="form-control" x-model="formData.questionlabel" required>
                                        <option value="">Select Label</option>
                                        @foreach($questionLabel as $question)
                                            <option value="{{ $question->id }}">{{ $question->label }}</option>
                                        @endforeach
                                    </select>

                                </div>
                        </div>

                        

                        <!--  -->
                        <div class="form-group mb-3">
                            <label>  <i class="fa fa-list-ul"></i> Question Type</label>
                                <select class="form-control" x-model="formData.question_type" required>
                                    <option value="">Select Type</option>
                                    <option value="text">Text</option>
                                    <option value="yes_no">Yes/No</option>
                                    <option value="rating">Ratings</option>
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
                        </div>

                        <div class="form-group mb-2" x-show="showSubQuestionSection()"  x-transition x-cloak >
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

                        {{-- Sub questions with popup --}}
                        <div class="form-group mb-2" x-show="showSubQuestionSectionPopup()">
                            <div class="card-body">

                                <!-- Sub Questions Container -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <label class="form-label mb-0">
                                            <i class="fa fa-plus-circle"></i> Sub Questions
                                        </label>
                                        <span class="question-badge">
                                            <span x-text="subQuestions.length"></span> Question<span x-show="subQuestions.length !== 1">s</span>
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
                                                        <i class="fa fa-calendar"></i> Date
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <!-- Add Button -->
                                    <div class="add-section">
                                        <button type="button" class="btn btn-primary" @click="addQuestion()">
                                            <i class="fa fa-plus-circle"></i> Add Sub Question
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Options --}}
                        <div class="form-group mb-2" x-show="showOptions()">
                            <!-- Input -->
                            <input type="text"
                                class="form-control"
                                placeholder="Enter option and press Enter"
                                x-model="formData.optionsInput"
                                @keydown.enter.prevent="addOption()">

                            <!-- Tags -->
                            <div class="mt-2 d-flex flex-wrap gap-2">
                                <template x-for="(option, index) in formData.options" :key="index">
                                    <span class="badge bg-primary d-flex align-items-center gap-2">
                                        <span x-text="option"></span>
                                        <button type="button"
                                                class="btn-close btn-close-white btn-sm"
                                                @click="removeOption(index)">
                                        </button>
                                    </span>
                                </template>
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
</div><!-- Container-fluid starts-->
<div class="container-fluid user-list-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-no-border text-end">
                    <div class="card-header-right-icon">
                        <a class="btn btn-primary f-w-500" href="#" data-bs-toggle="modal" data-bs-target="#question-modal">
                            <i class="fa-solid fa-plus pe-2"></i>Add {{ ucfirst($module) }}</a>
                        </div>
                </div>
                <div class="card-body pt-0 px-0" x-init="fetchQuestions()">
                    <div class="list-product user-list-table">
                        <div class="table-responsive custom-scrollbar">
                             <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Filter by Treatment:</label>
                                        <select id="type-filter" class="form-select mb-3" style="width: 250px">
                                            <option value="">All Types</option>
                                        </select>
                                    </div>
                                </div>
                            <table class="table" id="question-table">
                                <thead>
                                    <tr>
                                        <th> <span class="c-o-light f-w-600">S/N</span></th>
                                        <th> <span class="c-o-light f-w-600">Question</span></th>
                                        <th> <span class="c-o-light f-w-600">Type</span></th>
                                        <th> <span class="c-o-light f-w-600">Question Label</span></th>
                                        <th> <span class="c-o-light f-w-600">Options</span></th>
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
    Alpine.data('QuestionsComponent', (module) => ({
        module,
        isLoading: false,
        isEdit: false,
        isSaving: false,
        isLoadingEdit: false,

        questionId: null,
        allQuestions: [],
        dataTable: null,

        formData: {
            question: '',
            question_type: '',
            parent_question_id: null,
            module: module,
            optionsInput: '',
            questionlabel: '',
            options: []
        },

        
         shouldUseDarkText(hexColor) {
            hexColor = hexColor.replace('#', '');
            const r = parseInt(hexColor.substr(0, 2), 16);
            const g = parseInt(hexColor.substr(2, 2), 16);
            const b = parseInt(hexColor.substr(4, 2), 16);
            const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
            return luminance > 0.5;
        },
        
        modalInstance: null,
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

        addOption() {
            const value = this.formData.optionsInput.trim();
            if (!value) return;
            if (!this.formData.options.includes(value)) {
                this.formData.options.push(value);
            }
            this.formData.optionsInput = '';
        },

        removeOption(index) {
            this.formData.options.splice(index, 1);
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

        init() {
            this.$watch('formData.question_type', (newValue, oldValue) => {
                // When question type changes, clear subquestions
                if (!this.isLoadingEdit && oldValue && oldValue !== newValue) {
                    this.resetSubQuestions();
                }
                //clear the edit form when modal is hidden........./
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
        },

        // All Questions
        async fetchQuestions() {
            this.isLoading = true;
            try {
                const response = await fetch(`/questions/questions_get/${this.module}`);
                const data = await response.json();
                
                if (data.success) {
                    console.log(data.data);
                    this.allQuestions = data.data;
                    this.initDataTable();
                } else {
                    alert(data.message || 'Failed to load questions');
                }
            } catch (error) {
                console.error('Error fetching questions:', error);
                alert('Failed to load questions');
            } finally {
                this.isLoading = false;
            }
        },

      initDataTable() {
        const self = this;

        if (this.dataTable) {
            this.dataTable.clear().destroy();
        }

        const tableData = this.prepareTableData();

        this.dataTable = $('#question-table').DataTable({
            data: tableData,
            pageLength: 10,
            ordering: false,
            // dom: '<"d-flex justify-content-between align-items-center mb-3"Bf>rtip',
            // buttons: [
            //     {
            //         extend: 'copy',
            //         className: 'btn btn-sm btn-secondary',
            //         exportOptions: { columns: [0, 1, 2] } // Exclude the Action column
            //     },
            //     {
            //         extend: 'csv',
            //         className: 'btn btn-sm btn-secondary',
            //         exportOptions: { columns: [0, 1, 2] }
            //     },
            //     {
            //         extend: 'excel',
            //         title: 'Questions Export',
            //         className: 'btn btn-sm btn-success',
            //         exportOptions: { columns: [0, 1, 2] }
            //     },
            //     {
            //         extend: 'pdf',
            //         title: 'Questions Export',
            //         className: 'btn btn-sm btn-danger',
            //         exportOptions: { columns: [0, 1, 2] }
            //     },
            //     {
            //         extend: 'print',
            //         className: 'btn btn-sm btn-info',
            //         exportOptions: { columns: [0, 1, 2] }
            //     }
            // ],

            columns: [

                // S/N (shared by parent & children)
                {
                    data: '_serial'
                },

                // Question (indent child)
                {
                    data: 'question',
                    render: function (data, type, row) {
                        if (row._isChild) {
                            return `<span class="ms-4 text-muted">↳ ${data}</span>`;
                        }
                        return `<span>${data}</span>`;
                    }
                },

                // Type
                {
                    data: 'type',
                    render: data => data ? data.replaceAll('_', ' ') : '-'
                },
                { 
                        data: 'label', // Points to the "label" object in your JSON
                        render: function(data, type, row) {
                            if (!data) {
                                return '-';
                            }
                            
                            const labelText = data.label || 'Unknown';
                            const bgColor = data.color || '#6c757d';
                            const textColor = self.shouldUseDarkText(bgColor) ? '#000' : '#fff';
                            
                            return `<span class="badge" style="background-color: ${bgColor}; color: ${textColor};">${labelText}</span>`;
                        },
                        searchable: true
                    },

                // Options
                {
                    data: 'options',
                    render: function (data) {
                        if (!Array.isArray(data) || data.length === 0) {
                            return '<span class="text-muted">N/A</span>';
                        }

                        return data.map(opt =>
                            `<span class="badge bg-secondary me-1">${opt}</span>`
                        ).join('');
                    }
                },


                // Action (parents only)
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        if (type === 'export') return '';
                        if (row._isChild) return '-';

                        return `
                            <div class="d-flex gap-2">
                                <a href="/question/${row.id}" class="btn btn-sm btn-light">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-warning edit-question-btn" data-id="${row.id}">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-question" data-id="${row.id}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ]
        });

        this.populateTypeFilter(tableData);

        $('#type-filter').off('change').on('change', function () {
            self.dataTable.column(2).search(this.value).draw();
        });

        $(document).off('click', '.delete-question').on('click', '.delete-question', e => {
            this.deleteQuestion(e.currentTarget.dataset.id);
        });
    },

    populateTypeFilter(data) {
        const types = [...new Set(data.map(q => q.type).filter(Boolean))];

        const select = $('#type-filter');
        select.find('option:not(:first)').remove();

        types.forEach(type => {
            select.append(
                `<option value="${type.replaceAll('_', ' ')}">
                    ${type.replaceAll('_', ' ')}
                </option>`
            );
        });
    },

        prepareTableData() {
            const rows = [];
            let serial = 1;

            this.allQuestions.forEach(parent => {
                rows.push({
                    ...parent,
                    _serial: serial,
                    _isChild: false
                });

                if (parent.children && parent.children.length) {
                    parent.children.forEach(child => {
                        rows.push({
                            ...child,
                            _serial: serial,
                            _isChild: true
                        });
                    });
                }
                serial++;
            });

            return rows;
        },


        async deleteQuestion(questionId) {
            if (!confirm('Are you sure you want to delete this question?')) {
                return;
            }

            try {
                const response = await fetch(`/admin/questions/${questionId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.allQuestions = this.allQuestions.filter(question => question.id !== questionId);
                    this.dataTable.clear();
                    this.dataTable.rows.add(this.allQuestions);
                    this.dataTable.draw();
                    alert('Question deleted successfully');
                } else {
                    alert(data.message || 'Failed to delete question');
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

            if (!this.modalInstance) {
                this.modalInstance = new bootstrap.Modal(modalEl);
            }

            this.modalInstance.show();
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
                this.formData.options = question.data.options || [];
                this.formData.questionlabel = question.data.questionlabel;

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

        // ✅ FIX: Filter out empty subquestions before sending
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
                    options: this.formData.options
                };

                // console.log('Payload:', payload);
                // return;
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
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script src="{{ asset('assets/js/datatable/datatables/dataTables.select.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/select.bootstrap5.js') }}"></script>
    
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/trash_popup.js') }}"></script>
    <script src="{{ asset('assets/js/common-check.js') }}"></script>
    
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
@endsection