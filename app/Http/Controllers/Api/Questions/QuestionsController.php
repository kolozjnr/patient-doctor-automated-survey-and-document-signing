<?php

namespace App\Http\Controllers\Api\Questions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Admin\QuestionRepository;

class QuestionsController extends Controller
{
    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    public function getGeneralQuestions(Request $request)
    {
        //dd(Auth('api')->user());
        $questions = $this->questionRepository->getGeneralQuestions();
        return response()->json($questions);
    }

    public function getBellscaleQuestions()
    {
        $result = $this->questionRepository->getBellscaleQuestions();

        return response()->json([
            'success' => true,
            ...$result,
        ]);
}

    public function postGeneralAnswers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'answers' => 'required|array|min:1',

            'answers.*.question_id' => 'required|integer|exists:questions,id',
            'answers.*.type' => 'required|string|in:text,single_option,multiple_option,optional,table,rating_with_text,duration,yes_no_with_question,yes_no_with_popup',

            'answers.*.answer' => 'nullable|string',
            'answers.*.optional_answer' => 'nullable|string',
            'answers.*.answers' => 'nullable|array',
            'answers.*.table_answer' => 'nullable|array',
            'answers.*.option_id' => 'nullable|integer|exists:question_options,id',
        ]);

       // dd($request->all());
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $this->questionRepository->storeBulkAnswers($request->only('answers'));

        return response()->json([
            'success' => true,
            'message' => 'Answers submitted successfully'
        ]);
    }

    public function postBellscaleAnswers(Request $request){
        $validator = Validator::make($request->all(), [

            'answers' => 'required|array|min:1',

            'answers.*.question_id' => 'required|integer|exists:questions,id',
            'answers.*.option_id'   => 'nullable|integer|exists:question_options,id',

            'answers.*.answer_text'   => 'nullable|string',
            'answers.*.answer_number' => 'nullable|numeric',
            'answers.*.answer_boolean'=> 'nullable|boolean',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }


        $this->questionRepository->storeBellScaleAnswers($request->only('answers'));

        return response()->json([
            'success' => true,
            'message' => 'Bellscale answers submitted successfully'
        ]);
    }
}
