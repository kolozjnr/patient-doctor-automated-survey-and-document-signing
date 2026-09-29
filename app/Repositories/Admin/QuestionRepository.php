<?php

namespace App\Repositories\Admin;

use App\Enums\UserStatus;
use App\Models\BellscaleAnswer;
use App\Models\BellscaleCycle;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\User;
use Illuminate\Support\Collection;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionRepository
{
    public function model()
    {
        return Question::class;
    }

    // public function getQuestionsByModuleName($module)
    // {
    //     return $this->model()::where('module_name', $module)
    //         ->whereNull('parent_question_id')
    //         ->whereNull('deleted_at')
    //         ->with('children')
    //         ->with('label')
    //         ->with('options')
    //         ->orderBy('_order', 'asc')
    //         ->get();
    // }

    public function getQuestionsByModuleName($module)
    {
        if($module === 'general')
            {
            return $this->model()::select('questions.*')
            ->join('labels', 'questions.question_label', '=', 'labels.id')
            ->where('questions.module_name', $module)
            ->whereNull('questions.parent_question_id')
            ->whereNull('questions.deleted_at')
            ->with('children')
            ->with('label')
            ->with('options')
             ->orderByRaw("
                CASE labels.label
                    WHEN 'SF12' THEN 1
                    WHEN 'NRS' THEN 2
                    WHEN 'BPI' THEN 3
                    WHEN 'OLBPDQ' THEN 4
                    WHEN 'NDI' THEN 5
                    WHEN 'HADS' THEN 6
                    WHEN 'PCS' THEN 7
                    WHEN 'Ervaringen' THEN 8
                    ELSE 9
                END
            ")
            ->orderBy('questions._order', 'asc')
            ->get();

            }
            return $this->model()::where('module_name', $module)
            ->whereNull('parent_question_id')
            ->whereNull('deleted_at')
            ->with('children')
            ->with('label')
            ->with('options')
            ->orderBy('_order', 'asc')
            ->get();
        
    }

    public function getSurveyQuestions()
    {
        return $this->model()::select('id', 'question')
            ->whereNull('parent_question_id')
            ->whereNull('deleted_at')
            ->latest()
            ->get();
    }

        /**
     * Find question by ID
     */
    public function find(int $id): ?Question
    {
        return $this->model()::with('children', 'label', 'options')->find($id);
    }

       /**
     * Create parent question
     */
    public function createParentXXX(array $data): Question
    {
        return Question::create([
            'question' => $data['question'],
            'type' => $data['question_type'],
            'question_label' => $data['questionlabel'] ?? null,
            'module_name' => $data['module'],
            'options' => $data['options'] ?? null,
            'is_subquestion' => 0,
            'parent_question_id' => null,
        ]);
    }
    
    public function createParent(array $data): Question
    {
        DB::transaction(function () use ($data, &$question) {

            $question = Question::create([
                'question' => $data['question'],
                'type' => $data['question_type'] ?? null,
                'question_label' => $data['questionlabel'] ?? null,
                'module_name' => $data['module'],
                'is_subquestion' => 0,
                'parent_question_id' => null,
            ]);

        //     if (!empty($data['options'])) {
        //         foreach ($data['options'] as $index => $opt) {
        //             QuestionOption::create([
        //                 'question_id' => $question->id,
        //                 'option_text' => $opt['option'],
        //                 'option_value' => $opt['option_value'] !== ''
        //                     ? $opt['option_value']
        //                     : null,
        //                 'display_order' => $index + 1,
        //             ]);
        //         }
        //     }
        

        //     if (($data['question_type'] ?? null) === 'rating_with_text' && isset($data['rating_first_text'], $data['rating_last_text'])) {
        //     QuestionOption::insert([[
        //         'question_id'   => $question->id,
        //         'option_text'   => null,
        //         'option_value'  => null,
        //         'rating_first_text'   => $data['rating_first_text'],
        //         'rating_last_text'     => $data['rating_last_text'],
        //         'display_order' => 0,
        //     ]]);
        // }


           $noOptionTypes = ['duration', 'text', 'textarea', 'date', 'number'];

            // Always wipe old options
            $question->options()->delete();

            // Regular options — skip for rating_with_text, no-option types, or empty options
            if (
                !in_array($data['question_type'] ?? null, [...$noOptionTypes, 'rating_with_text']) &&
                !empty($data['options'])
            ) {
                $options = array_map(fn($opt, $index) => [
                    'question_id'       => $question->id,
                    'option_text'       => $opt['option'],
                    'option_value'      => $opt['option_value'] !== '' ? $opt['option_value'] : null,
                    'rating_first_text' => null,
                    'rating_last_text'  => null,
                    'display_order'     => $index + 1,
                ], $data['options'], array_keys($data['options']));

                QuestionOption::insert($options);
            }

            // Rating with text row
            if (
                ($data['question_type'] ?? null) === 'rating_with_text' &&
                isset($data['rating_first_text'], $data['rating_last_text'])
            ) {
                QuestionOption::insert([[
                    'question_id'       => $question->id,
                    'option_text'       => null, 
                    'option_value'      => null,
                    'rating_first_text' => $data['rating_first_text'],
                    'rating_last_text'  => $data['rating_last_text'],
                    'display_order'     => 0,
                ]]);
            }

        });

        return $question->load('options');
    }

    /**
     * Update parent question
     */

    public function updateParent(int $id, array $data): Question
    {
        return DB::transaction(function () use ($id, $data) {

            $question = Question::findOrFail($id);

            // update question itself
            $question->update([
                'question'       => $data['question'],
                'type'           => $data['question_type'],
                'question_label' => $data['questionlabel'] ?? null,
                'module_name'    => $data['module'],
            ]);

            $noOptionTypes = ['duration', 'text', 'textarea', 'date', 'number'];

            // Always wipe old options
            $question->options()->delete();

            // Regular options — skip for rating_with_text, no-option types, or empty options
            if (
                !in_array($data['question_type'] ?? null, [...$noOptionTypes, 'rating_with_text']) &&
                !empty($data['options'])
            ) {
                $options = array_map(fn($opt, $index) => [
                    'question_id'       => $question->id,
                    'option_text'       => $opt['option'],
                    'option_value'      => $opt['option_value'] !== '' ? $opt['option_value'] : null,
                    'rating_first_text' => null,
                    'rating_last_text'  => null,
                    'display_order'     => $index + 1,
                ], $data['options'], array_keys($data['options']));

                QuestionOption::insert($options);
            }

            // Rating with text row
            if (
                ($data['question_type'] ?? null) === 'rating_with_text' &&
                isset($data['rating_first_text'], $data['rating_last_text'])
            ) {
                QuestionOption::insert([[
                    'question_id'       => $question->id,
                    'option_text'       => null,
                    'option_value'      => null,
                    'rating_first_text' => $data['rating_first_text'],
                    'rating_last_text'  => $data['rating_last_text'],
                    'display_order'     => 0,
                ]]);
            }

// // Regular options — skip if rating_with_text or options are empty/null
// if (
//     ($data['question_type'] ?? null) !== 'rating_with_text' &&
//     !empty($data['options'])
// )
// //dd('here');
//  {
//     $options = array_map(fn($opt, $index) => [
//         'question_id'   => $question->id,
//         'option_text'   => $opt['option'],
//         'option_value'  => $opt['option_value'] !== '' ? $opt['option_value'] : null,
//         'rating_first_text' => null,
//         'rating_last_text'  => null,
//         'display_order' => $index + 1,
//     ], $data['options'], array_keys($data['options']));

//     QuestionOption::insert($options);
// }

// // Rating with text row
// if (
//     ($data['question_type'] ?? null) === 'rating_with_text' &&
//     isset($data['rating_first_text'], $data['rating_last_text'])
// ) {
//     QuestionOption::insert([[
//         'question_id'       => $question->id,
//         'option_text'       => 'null',
//         'option_value'      => null,
//         'rating_first_text' => $data['rating_first_text'],
//         'rating_last_text'  => $data['rating_last_text'],
//         'display_order'     => 0,
//     ]]);
// }
       
            return $question->load('options');
        });
    }
    public function updateParentXXX(int $id, array $data): Question
    {
        $question = Question::findOrFail($id);

        $question->update([
            'question' => $data['question'],
            'type' => $data['question_type'],
            'question_label' => $data['questionlabel'] ?? null,
            'module_name' => $data['module'],
            'options' => $data['options'] ?? null,
        ]);

        return $question->fresh();
    }

    /**
     * Sync sub-questions (delete old, create new)
     */
    public function syncSubQuestions(int $parentId, array $subQuestions, string $module): void
    {
        DB::transaction(function () use ($parentId, $subQuestions, $module) {
            // Remove old sub-questions
            Question::where('parent_question_id', $parentId)->delete();

            // Insert new ones
            foreach ($subQuestions as $sub) {
                Question::create([
                    'question' => $sub['text'],
                    'type' => $sub['answer_type'],
                    'module_name' => $module,
                    'is_subquestion' => 1,
                    'parent_question_id' => $parentId,
                ]);
            }
        });
    }

    /**
     * Remove all sub-questions for a parent
     */
    public function removeSubQuestions(int $parentId): void
    {
        Question::where('parent_question_id', $parentId)->delete();
    }

    /**
     * Get question with children
     */
    public function getWithChildren(int $id): Question
    {
        return Question::with('children')->findOrFail($id);
    }

    /**
     * Get all questions by module
     */
    public function getByModule(string $module)
    {
        return Question::where('module_name', $module)
            ->where('is_subquestion', 0)
            ->with('children', 'options')
            ->with('label')
            ->get();
    }

    /**
     * API users Start here
     */
    public function getGeneralQuestions(): ?Collection
    {
            return $this->model()::select('questions.*')
            ->join('labels', 'questions.question_label', '=', 'labels.id')
            ->where('questions.module_name', 'general')
            ->whereNull('questions.parent_question_id')
            ->whereNull('questions.deleted_at')
            ->with('children')
            ->with('label')
            ->with('options')
             ->orderByRaw("
                CASE labels.label
                    WHEN 'SF12' THEN 1
                    WHEN 'NRS' THEN 2
                    WHEN 'BPI' THEN 3
                    WHEN 'OLBPDQ' THEN 4
                    WHEN 'NDI' THEN 5
                    WHEN 'HADS' THEN 6
                    WHEN 'PCS' THEN 7
                    WHEN 'Ervaringen' THEN 8
                    ELSE 9
                END
            ")
            ->orderBy('questions._order', 'asc')
            ->get();

            
            // return $this->model()::where('module_name', 'gener')
            // ->whereNull('parent_question_id')
            // ->whereNull('deleted_at')
            // ->with('children')
            // ->with('label')
            // ->with('options')
            // ->orderBy('_order', 'asc')
            // ->get();

        //return $question;
    }

    public function getBellscaleQuestions(): array
    {
        $userId = auth()->id();

        $cycle = BellscaleCycle::where('user_id', $userId)
            ->latest()
            ->first();

        $isDue = is_null($cycle) || $cycle->next_due_at->isPast();

        if (!$isDue) {
            return [
                'is_due'      => false,
                'next_due_at' => $cycle->next_due_at,
                'questions'   => [],
            ];
        }

        $questions = $this->model()::where('module_name', 'bellscale')
            ->where('is_subquestion', 0)
            ->with(['children', 'options', 'label'])
            ->whereNull('deleted_at')
            ->orderBy('_order', 'asc')
            ->get();

        return [
            'is_due'      => true,
            'next_due_at' => $cycle?->next_due_at,
            'questions'   => $questions,
        ];
    }

    public function getBellScaleOption(int $questionId)
    {
        return Question::findOrFail($questionId)->options()->get();
    }

    public function saveOrUpdateBellscaleOptions(array $data, int $id): Question
{
    return DB::transaction(function () use ($id, $data) {
        $question = Question::findOrFail($id);

        if (!empty($data['options'])) {
            $question->options()->delete();

            foreach ($data['options'] as $index => $option) {
                $question->options()->create([
                    'option_text'   => $option['option'],
                    'option_value'  => $option['option_value'] !== '' ? $option['option_value'] : null,
                    'display_order' => $index + 1,
                ]);
            }
        }

        return $question->load('options');
    });
}


    // THIS WAS MEANT TO GROUP BY LABEL FROM BACKEND BUT mOBILE DEV SAID HE WILL HANLDE THAT IN THE APP
    // public function getGeneralQuestions(): ?Collection
    // {
    //     return $this->model()::where('module_name', 'general')
    //         ->where('is_subquestion', 0)
    //         ->whereNull('deleted_at')
    //         ->with(['children', 'label'])
    //         // 1. Order by your new sort column
    //         ->orderBy('_order', 'asc') 
    //         ->get()
    //         // 2. Group the results by the label name or ID
    //         ->groupBy(function($item) {
    //             return $item->label->label ?? '-'; 
    //         });
    // }




    public function storeBulkAnswers(array $payload)
    {
        $userId = auth()->user('web')->id;

        //dd($userId);
        DB::transaction(function () use ($payload, $userId) {

            foreach ($payload['answers'] as $item) {

                $question = $this->model()::find($item['question_id']);

                if (!$question) {
                    continue;
                }

                $data = [
                    'user_id' => $userId,
                    'answer' => null,
                    'answers' => null,
                    'optional_answer' => null,
                    'table_answer' => null,
                    'option_id' => null,
                ];

                switch ($item['type']) {
                    case 'text':
                    case 'duration':
                    case 'rating_with_text':
                        $data['answer'] = $item['answer'] ?? null;
                        break;
                    case 'single_option':
                    case 'dropdown':
                    case 'yes_no_with_multi_checkbox':
                    case 'yes_no_with_checkbox':
                       //$data['answer'] = $item['answer'] ?? null;
                        $data['option_id'] = $item['option_id'] ?? null;
                        break;

                    case 'multiple_option':
                        $data['answers'] = $item['answers'] ?? null;
                        break;

                    case 'table':
                        $data['table_answer'] = $item['table_answer'] ?? null;
                        break;
                    default:
                        $data['answer'] = $item['answer'] ?? null;
                }

                $question->generalAnswers()->create($data);
            }
            
            User::where('id', $userId)->update([
                'status' => UserStatus::GeneralFilled,
            ]);

        });
    }


    public function storeBellScaleAnswers(array $data)
    {
        $userId = auth()->user('web')->id;
        $answers = $data['answers'];

        //dd(now()->addMinutes(6));

        foreach ($answers as $answer) {
            BellscaleAnswer::create([
                'user_id'        => $userId,
                'question_id'    => $answer['question_id'],
                'option_id'      => $answer['option_id'] ?? null,
                'answer'         => $answer['answer_text'] ?? null,
                'answer_number'  => $answer['answer_number'] ?? null,
                'answer_boolean' => $answer['answer_boolean'] ?? null,
            ]);
        }

        BellscaleCycle::create([
            'user_id'           => $userId,
            'last_completed_at' => now(),
            'next_due_at' => now()->addMinutes(5),
            'reminder_sent'     => false,
        ]);
    }

    public function deleteQuestionAndChildren(int $id): void
    {
        try {
            DB::transaction(function () use ($id) {
                $question = $this->model()::findOrFail($id);

                // Delete children first -- handled by cascade
                //$question->children()->delete();

                $question->delete();
            
                return true;
            });
        } catch (\Exception $e) {
            throw $e;
        }
    }

}
