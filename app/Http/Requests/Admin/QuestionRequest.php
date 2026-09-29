<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class QuestionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'question_id' => 'nullable|exists:questions,id',
            'question' => 'required|string|max:500',
            'module' => 'required|string',
            //'question_type' => 'required|string',
            'question_type' => [
                'nullable',
                'string',
                Rule::requiredIf($this->module !== 'bellscale'),
            ],
            'questionlabel' => 'nullable|exists:labels,id',

            'options' => 'nullable|array',
            'options.*.option' => 'nullable|string|max:255',
            'options.*.option_value' => 'nullable|numeric',
            'rating_first_text' => 'nullable|string',
            'rating_last_text' => 'nullable|string',
            
            // ✅ NEW: Single subquestion for yes_no_with_question
            'subQuestion' => 'nullable|array',
            'subQuestion.subquestion_with_question_text' => 'nullable|string|max:500',
            'subQuestion.subquestion_with_question_answerType' => 'nullable|string|max:255',
            
            // ✅ EXISTING: Multiple subquestions for yes_no_with_popup
            'subQuestions' => 'nullable|array',
            'subQuestions.*.subquestion_with_popup_text' => 'nullable|string|max:500',
            'subQuestions.*.subquestion_with_popup_answerType' => 'nullable|string|max:255',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $questionType = $this->input('question_type');
            
            // ✅ Validate single subquestion for yes_no_with_question
            if ($questionType === 'yes_no_with_question') {
                $subQuestion = $this->input('subQuestion', []);
                
                if (!empty($subQuestion)) {
                    if (!isset($subQuestion['subquestion_with_question_text']) || 
                        trim($subQuestion['subquestion_with_question_text']) === '') {
                        $validator->errors()->add(
                            'subQuestion.subquestion_with_question_text',
                            'The subquestion text field is required.'
                        );
                    }
                    
                    if (!isset($subQuestion['subquestion_with_question_answerType']) || 
                        trim($subQuestion['subquestion_with_question_answerType']) === '') {
                        $validator->errors()->add(
                            'subQuestion.subquestion_with_question_answerType',
                            'The subquestion answer type field is required.'
                        );
                    }
                }
            }
            
            // ✅ Validate multiple subquestions for yes_no_with_popup
            if ($questionType === 'yes_no_with_popup') {
                $subQuestions = $this->input('subQuestions', []);
                
                if (!empty($subQuestions)) {
                    foreach ($subQuestions as $index => $subQuestion) {
                        if (!isset($subQuestion['subquestion_with_popup_text']) || 
                            trim($subQuestion['subquestion_with_popup_text']) === '') {
                            $validator->errors()->add(
                                "subQuestions.{$index}.subquestion_with_popup_text",
                                'The popup text field is required.'
                            );
                        }
                        
                        if (!isset($subQuestion['subquestion_with_popup_answerType']) || 
                            trim($subQuestion['subquestion_with_popup_answerType']) === '') {
                            $validator->errors()->add(
                                "subQuestions.{$index}.subquestion_with_popup_answerType",
                                'The popup answer type field is required.'
                            );
                        }
                    }
                }
            }
        });
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422)
        );
    }

    public function messages()
    {
        return [
            'question.required' => 'The question field is required.',
            'module.required' => 'The module field is required.',
            'question_type.required' => 'The question type field is required.',
            'subQuestion.subquestion_with_question_text.required' => 'The subquestion text is required.',
            'subQuestion.subquestion_with_question_answerType.required' => 'The subquestion answer type is required.',
            'subQuestions.*.subquestion_with_popup_text.required' => 'The popup text is required.',
            'subQuestions.*.subquestion_with_popup_answerType.required' => 'The popup answer type is required.',
        ];
    }
}