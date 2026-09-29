<?php

use Illuminate\Foundation\Http\FormRequest;

class SaveSurveyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',

             'send_to' => 'required|in:department,individual_patient',
            'department_id' => 'required_if:send_to,department|exists:departments,id',
            'patient_ids' => 'required_if:send_to,individual_patient|array',
            'patient_ids.*' => 'exists:users,id',

            'frequency' => 'required|in:once,daily,weekly,monthly,custom',

            'survey_delivery_date' => 'nullable|date',

            'questions' => 'required|array|min:1',
            'questions.*' => 'exists:questions,id',

            // Custom recurrence
            'custom_reoccurrence' => 'nullable|array',

            'custom_reoccurrence.repeat.interval' =>
                'required_if:frequency,custom|integer|min:1',

            'custom_reoccurrence.repeat.unit' =>
                'required_if:frequency,custom|in:day,week,month',

            'custom_reoccurrence.repeat_on' =>
                'nullable|array',

            'custom_reoccurrence.repeat_on.*' =>
                'integer|between:0,6',

            'custom_reoccurrence.ends.type' =>
                'required_if:frequency,custom|in:never,on,after',

            'custom_reoccurrence.ends.on' =>
                'nullable|date|required_if:custom_reoccurrence.ends.type,on',

            'custom_reoccurrence.ends.after' =>
                'nullable|integer|min:1|required_if:custom_reoccurrence.ends.type,after',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->frequency !== 'custom') {
            $this->merge([
                'custom_reoccurrence' => null
            ]);
        }
    }
}
