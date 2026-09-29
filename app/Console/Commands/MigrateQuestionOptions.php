<?php

namespace App\Console\Commands;

use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateQuestionOptions extends Command
{
    protected $signature = 'migrate:question-options';
    protected $description = 'Migrate question options from JSON to question_options table';

    public function handle()
    {
        $this->info('Starting migration...');
        $startTime = microtime(true);

        DB::transaction(function () {
            $questions = Question::whereNotNull('options')->get();
            
            $this->info("Found {$questions->count()} questions to migrate");
            $this->newLine();

            $migratedCount = 0;
            $skippedCount = 0;
            $totalOptions = 0;

            $bar = $this->output->createProgressBar($questions->count());
            $bar->start();

            foreach ($questions as $question) {
                $options = is_string($question->options) 
                    ? json_decode($question->options, true) 
                    : $question->options;

                if (empty($options) || !is_array($options)) {
                    $skippedCount++;
                    $bar->advance();
                    continue;
                }

                $question->options()->delete();

                foreach ($options as $index => $option) {
                    // Handle both simple strings and array structures
                    $optionText = is_string($option) ? $option : ($option['option'] ?? null);
                    
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => $optionText,
                        'option_value' => rand(2, 6),
                        'display_order' => $index + 1,
                    ]);
                    $totalOptions++;
                }

                $migratedCount++;
                $bar->advance();
            }

            $bar->finish();
            $this->newLine(2);

            $this->info('✓ Migration Complete!');
            $this->table(
                ['Metric', 'Count'],
                [
                    ['Questions migrated', $migratedCount],
                    ['Questions skipped', $skippedCount],
                    ['Total options created', $totalOptions],
                ]
            );
        });

        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);
        $this->info("Time taken: {$duration} seconds");

        return 0;
    }
}