<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\QuestionRequest;
use App\Repositories\Admin\QuestionRepository;

class QuestionController extends Controller
{
    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }
    public function index(string $module)
    {
        $questionLabel = Label::where('category', 'question')->get();
        return view('questions.index', [
            'module' => $module,
            'questionLabel' => $questionLabel
        ]);
    }

    // public function list(string $module)
    // {
    //     $questionLabel = Label::where('category', 'question')->get();
    //     return view('questions.question_l', [
    //         'module' => $module,
    //         'questionLabel' => $questionLabel
    //     ]);
    // }

    public function fetch($module)
    {

        $questions = $this->questionRepository->getQuestionsByModuleName($module);

        return response()->json([
            'success' => true,
            'data' => $questions
        ]);
    }

    public function edit($id)
    {
        $question = $this->questionRepository->find($id);

        if (!$question) {
            return response()->json([
                'success' => false,
                'message' => 'Question not found!',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $question,
        ]);
    }

    public function save(QuestionRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Normalize sub-questions based on question type
            $normalizedSubQuestions = $this->normalizeSubQuestions($data);
            $data['subQuestions'] = $normalizedSubQuestions;

            // Create or update parent question
            $question = $this->saveParentQuestion($data);

            // Handle sub-questions if present
            if (!empty($normalizedSubQuestions)) {
                $this->questionRepository->syncSubQuestions(
                    $question->id,
                    $normalizedSubQuestions,
                    $data['module']
                );
            } else {
                // If no subquestions provided, remove any existing ones
                $this->questionRepository->removeSubQuestions($question->id);
            }

            return response()->json([
                'success' => true,
                'message' => $data['question_id'] ? 'Question updated successfully!' : 'Question created successfully!',
                'data' => $question->load('children'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save question: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Normalize sub-questions based on question type
     */
    protected function normalizeSubQuestions(array $data): array
    {
        $normalizedSubQuestions = [];

        // ✅ Handle yes_no_with_question (single subquestion)
        if ($data['question_type'] === 'yes_no_with_question') {
            if (!empty($data['subQuestion'])) {
                $normalizedSubQuestions[] = [
                    'text' => $data['subQuestion']['subquestion_with_question_text'],
                    'answer_type' => $data['subQuestion']['subquestion_with_question_answerType'],
                ];
            }
            return $normalizedSubQuestions;
        }

        // ✅ Handle yes_no_with_popup (multiple subquestions)
        if ($data['question_type'] === 'yes_no_with_popup') {
            if (!empty($data['subQuestions'])) {
                foreach ($data['subQuestions'] as $sub) {
                    $normalizedSubQuestions[] = [
                        'text' => $sub['subquestion_with_popup_text'],
                        'answer_type' => $sub['subquestion_with_popup_answerType'],
                    ];
                }
            }
            return $normalizedSubQuestions;
        }
       // dd($normalizedSubQuestions);
        return $normalizedSubQuestions;
    }

    /**
     * Save parent question (create or update)
     */
    protected function saveParentQuestion(array $data)
    {
        if (!empty($data['question_id'])) {
            return $this->questionRepository->updateParent(
                $data['question_id'],
                $data
            );
        }

        return $this->questionRepository->createParent($data);
    }

    public function updateOrder(Request $request, $module)
    {
        try {
            $orders = $request->input('orders');
            
            foreach ($orders as $orderData) {
                DB::table('questions')
                    ->where('id', $orderData['id'])
                    ->where('module_name', $module)
                    ->update(['_order' => $orderData['order']]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getBellscaleOptions($questionId)
    {
        try {
            // Fix: was passing wrong variable ($id) and not returning the data
            $options = $this->questionRepository->getBellScaleOption($questionId);

            return response()->json([
                'success' => true,
                'message' => 'Bellscale options fetched successfully',
                'data'    => $options  // Fix: must actually return the options!
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get bellscale options: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveOrUpdateBellscaleOptions(Request $request)
{
    // Validate that question_id exists in the request body
    $request->validate([
        'question_id' => 'required|integer|exists:questions,id',
        'options'     => 'required|array|min:1',
        'options.*.option'       => 'required|string',
        'options.*.option_value' => 'nullable|numeric',
    ]);

    try {
        $question = $this->questionRepository->saveOrUpdateBellscaleOptions(
            $request->all(),
            (int) $request->question_id  // read from body, not URL
        );

        return response()->json([
            'success' => true,
            'message' => 'Bellscale options saved successfully',
            'data'    => $question
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to save bellscale options: ' . $e->getMessage()
        ], 500);
    }
}
    public function destroy($id)
    {
        try {
            $this->questionRepository->deleteQuestionAndChildren($id);

            return response()->json([
                'success' => true,
                'message' => 'Question and its sub-questions deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete question: ' . $e->getMessage(),
            ], 500);
        }
    }

}
