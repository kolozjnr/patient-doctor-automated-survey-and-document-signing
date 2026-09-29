<?php

namespace App\Repositories\Admin;

use App\Models\Label;
use Prettus\Repository\Eloquent\BaseRepository;

class LabelRepository extends BaseRepository
{
    public function model()
    {
        return Label::class;
    }

    public function getTreatmentLabel()
    {
        return $this->model->where('category', 'treatment')->get();
    }

    public function storeOrUpdate(array $labels): void
    {
        if (empty($labels)) {
            return;
        }

        // 1. Identify the category being managed (assuming one category per request)
        $currentCategory = $labels[0]['category'];
        //dd($currentCategory);

        // 2. Collect IDs sent in the request to keep them
        $incomingIds = collect($labels)->pluck('id')->filter()->toArray();

        // 3. Delete only labels that:
        //    - Belong to THIS specific category
        //    - ARE NOT in the incoming payload
        $this->model->where('category', $currentCategory)
                    ->whereNotIn('id', $incomingIds)
                    ->delete();

        $processedIds = [];
        
        foreach ($labels as $data) {
            if (!empty($data['id'])) {
                if (in_array($data['id'], $processedIds)) continue;
                
                $label = $this->model->find($data['id']);
                if ($label) {
                    $label->update([
                        'label'    => $data['label'],
                        'color'    => $data['color'],
                        'category' => $data['category'],
                    ]);
                    $processedIds[] = $data['id'];
                }
            } else {
                // Check if it exists by name within the category to prevent duplicates
                $this->model->updateOrCreate(
                    ['label' => $data['label'], 'category' => $data['category']],
                    ['color' => $data['color']]
                );
            }
        }
    }
}
