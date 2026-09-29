<?php

namespace App\Repositories\Admin;

use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\FaqVideouser;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class FaqRepository extends BaseRepository
{
    public function model()
    {
        return Faq::class;
    }
    public function show(int $id): ?Faq
    {
        return $this->model()::with('category')->find($id);
    }

    public function getAllFaq()
    {
        return $this->model()::with('category')
        ->orderBy('question_order', 'asc')
        ->get();
    }



    public function saveFaq(array $data): Faq
    {
        return Faq::create([
            'question' => $data['question'],
            'answer' => $data['answer'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'imageurl'  => $data['bunny_url'] ?? null,
            'question_order' => Faq::where('category_id', $data['category_id'])->max('question_order') + 1,
        ]);
    }


    public function updateFaq(array $data, int $id): Faq
    {
        $faq = Faq::findOrFail($id);

        $faq->update([
            'question' => $data['question'] ?? $faq->question,
            'answer' => $data['answer'] ?? $faq->answer,
            'category_id' => $data['category_id'] ?? $faq->category_id,
            // Only overwrite file_url if a new file was uploaded
            'imageurl' => $data['bunny_url'] ?? $faq->imageurl,
        ]);

        return $faq->fresh();
    }

    public function trackVideo(array $data, int $userId)
    {
        return FaqVideouser::updateOrCreate(
            [
                'faq_id'  => $data['faq_id'],
                'user_id' => $userId,
            ],
            [
                'watch_status' => $data['hide'],
            ]
        );
    }

    public function storeOrUpdate(array $cats): void
    {
        if (empty($cats)) {
            return;
        }
        $incomingIds = collect($cats)->pluck('id')->filter()->toArray();

        FaqCategory::whereNotIn('id', $incomingIds)->delete();

        $order = 1;

        foreach ($cats as $data) {

            if (!empty($data['id'])) {

                $cat = FaqCategory::find($data['id']);

                if ($cat) {
                    $cat->update([
                        'header' => $data['header'],
                        'category' => $data['category'],
                        'category_order' => $order
                    ]);
                }

            } else {

                FaqCategory::create([
                    'header' => $data['header'],
                    'category' => $data['category'],
                    'category_order' => $order
                ]);
            }

            $order++;
        }
    }

    public function deleteQuestion(int $id): void
    {
        try {
            DB::transaction(function () use ($id) {
                $question = $this->model()::findOrFail($id);

                $question->delete();
            
                return true;
            });
        } catch (\Exception $e) {
            throw $e;
        }
    }
}