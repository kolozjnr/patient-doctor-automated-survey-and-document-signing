<?php
namespace App\Repositories\Admin;

use App\Enums\RoleEnum;
use App\Models\bellscaleAnswer;
use App\Models\BellscaleCycle;
use App\Models\DocumentAssignment;
use App\Models\generalAnswer;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Eloquent\BaseRepository;
use Spatie\Permission\Models\Role;

class PatientRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\User';
    }

    /**
     * Create a new patient (user) with labels
     */
    public function createPatient($data)
    {
        try {
            DB::beginTransaction();

            $checkPatiendtId = $this->model
                ->where('patient_id', $data['patient_id'])
                ->exists();

            if ($checkPatiendtId) {
                throw new \Exception('Patient with this Patient ID already exists.');
            }

            // Create patient as user
            $patient = $this->model->create([
                'patient_id' => $data['patient_id'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'date_of_birth' => $data['date_of_birth'],
                'daily_notification_frequency' => $data['opt_for_daily'],
                'password' => Hash::make('123456789'),
                'user_type' => $data['user_type'],
                'status' => 'Nieuw',
            ]);

            $roleName = ($data['user_type'] === 'employee') 
                ? RoleEnum::EMPLOYEE 
                : RoleEnum::PATIENT;

            $role = Role::where('name', $roleName)->first();
            $patient->assignRole($role);

            // Attach labels (many-to-many relationship)
             if (!empty($data['labels']) && is_array($data['labels'])) {
                $patient->labels()->sync($data['labels']);
            }

            if (!empty($data['departments']) && is_array($data['departments'])) {
                $patient->departments()->sync($data['departments']);
            }

            DB::commit();

            return $patient->load('labels', 'departments', 'roles');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show($id)
    {
        return $this->model->find($id);
    }

    public function showPatientConsent($id)
    {
        return $this->model::with('assignments')->find($id);
    }
    public function getGeneralAssessment($id)
    {
        try {
            $assessment = generalAnswer::where('user_id', $id)
                ->with('question.label', 'option')
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->get();

            return $assessment;
        } catch (\Exception $e) {
            throw $e;
        }
    }

public function getPatientSurveys($userId)
{
    return Survey::whereHas('users', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        })
        ->with('users')
        ->withCount([
            'users as batch_patient_count',
            'surveyQuestions as questions_count'
        ])
        ->orderBy('created_at', 'desc')
        ->get([
            'id',
            'title',
            'description',
            'frequency',
            'survey_delivery_date',
            'cron_status',
            'created_at'
        ]);
}



    public function getSurveyAssessment($id, $patientId)
    {
        try{
            // $assessment = SurveyAnswer::where('survey_id', $id)
            // ->where('user_id', $patientId)
            // ->with('question', 'survey', 'survey.users','option')
            // ->orderBy('created_at', 'desc')
            // ->get();

            $assessment = SurveyAnswer::where('survey_id', $id)
            ->where('user_id', $patientId)
            ->select([
                'id',
                'question_id',
                'answer',
                'numeric_answer_value',
                'option_id'
            ])
            ->with([
                'question:id,question',
                'option:id,option_text'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

            return $assessment;
            
        }catch (\Exception $e){
            throw $e;
        }
    }

    



        public function getBellscaleAssesment($id)
        {
            $bellscale = bellscaleAnswer::where('user_id', $id)
                ->with('question','option')
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->get();
    
            return $bellscale;
        }

    /**
     * Update an existing patient with labels
     */
    public function updatePatient(int $id, array $data)
    {
        try {
            DB::beginTransaction();

            $patient = $this->model->find($id);

            if (!$patient) {
                return null;
            }

            $patient->update([
                'patient_id' => $data['patient_id'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'date_of_birth' => $data['date_of_birth'],
                'daily_notification_frequency' => $data['opt_for_daily'],
            ]);

            $patient->labels()->sync(
                !empty($data['labels']) && is_array($data['labels'])
                    ? $data['labels']
                    : []
            );

            $patient->departments()->sync(
                !empty($data['departments']) && is_array($data['departments'])
                    ? $data['departments']
                    : []
            );

            DB::commit();

            return $patient->load(['labels', 'departments']);

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

   

    public function updateConsent(int $id, string $path): bool
    {
        return $this->model
        ->where('id', $id)
        ->update([
            'consent' => $path,
            'requires_consent' => false,
        ]) > 0;
    }

    public function createDocuSealSubmission($user, string $documentTitle = '')
    {
        // Validate prerequisites
        if (empty($user->email)) {
            Log::error('Cannot create DocuSeal submission: user missing email', [
                'patient_id' => $user->id
            ]);
            return null;
        }

        $apiKey = config('services.docuseal.api_key');
        $templateId = config('services.docuseal.template_id');

        if (empty($apiKey) || empty($templateId)) {
            Log::error('DocuSeal configuration missing', [
                'has_api_key' => !empty($apiKey),
                'has_template_id' => !empty($templateId),
            ]);
            return null;
        }

        try {
            $payload = [
                'template_id' => (int) $templateId,
                'send_email' => false,
                'submitters' => [
                    [
                        'role' => 'First Party',
                        'email' => $user->email,
                        'external_id' => (string) $user->id,
                        'metadata' => [
                            'patient_id' => $user->id,
                        ],
                    ]
                ],
            ];

            $response = Http::withHeaders([
                'X-Auth-Token' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.docuseal.com/submissions', $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                $submissionUrl = $data[0]['embed_src'] ?? null;
                
                if (!$submissionUrl) {
                    Log::error('DocuSeal returned success but no URL', [
                        'response' => $data,
                        'patient_id' => $user->id,
                    ]);
                    return null;
                }

                // Cache the URL
                Cache::put(
                    "docuseal_url:{$user->id}", 
                    $submissionUrl, 
                    now()->addMinutes(50)
                );
                
                Log::info('DocuSeal submission created', [
                    'patient_id' => $user->id,
                    'url' => $submissionUrl,
                ]);
                
                return $submissionUrl;
            }

            Log::error('DocuSeal API returned error', [
                'patient_id' => $user->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
            
        } catch (\Exception $e) {
            Log::error('DocuSeal API exception', [
                'patient_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function getBellscaleStatus(int $userId): array
    {
        $cycle = BellscaleCycle::where('user_id', $userId)
            ->latest()
            ->first();

        $isDue = is_null($cycle) || $cycle->next_due_at->isPast();

        return [
            'is_due' => $isDue,
            'next_due_at' => $cycle?->next_due_at,
        ];
    }



    /**
     * Get wearable data for a patient
     */
    public function wearableData($id)
    {
        // return $this->model->find($id)->wearables;

        // Dummy minute timestamps (5 minutes)
        $timestamps = [
            "2025-11-30 10:00",
            "2025-11-30 10:01",
            "2025-11-30 10:02",
            "2025-11-30 10:03",
            "2025-11-30 10:04"
        ];

        return [
            "heart_rate" => [
                "time"  => $timestamps,
                "value" => [70, 72, 75, 78, 80]
            ],
            "spo2" => [
                "time"  => $timestamps,
                "value" => [97, 98, 98, 97, 99]
            ],
            "sleep" => [
                "time"  => $timestamps,
                "value" => [1, 2, 3, 2, 4] // 1=Awake,2=Light,3=Deep,4=REM
            ],
            "hrv" => [
                "day"   => ["Mon", "Tue", "Wed", "Thu", "Fri"],
                "value" => [35, 40, 42, 38, 45]
            ],
            "temperature" => [
                "day"   => ["Mon", "Tue", "Wed", "Thu", "Fri"],
                "value" => [0.1, -0.2, 0.3, 0.0, 0.4]
            ],
            "steps" => [
                "time"  => $timestamps,
                "value" => [10, 25, 35, 50, 65]
            ]
        ];
    }



    /**
     * Delete a patient
     */
    public function deletePatient($id)
    {
        try {
            DB::beginTransaction();

            $patient = $this->model->find($id);

            if (!$patient) {
                return false;
            }

            // Detach all labels first
            $patient->labels()->detach();

            // Delete patient (or soft delete)
            $patient->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get all patients with their labels
     */

    public function getPatients()
    {
        //Note Survey, Documesnts also make use of this, so becsrefull when making changes
       // return Cache::remember('patients_list', now()->addMinutes(50), function () {
            return $this->model
                ->where('user_type', 'patient')
                ->with('labels')
                ->with('departments')
                ->latest()
                //->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($user) {
                    // Get labels with their details
                    $labels = $user->labels;
                    
                    // Store full label objects for display
                    $user->treatment_labels = $labels->map(function($label) {
                        return [
                            'id' => $label->id,
                            'name' => $label->label,
                            'color' => $label->color ?? '#6c757d',
                            'category' => $label->category ?? 'treatment'
                        ];
                    })->toArray();
                    
                    // Determine background color for the row
                    if (count($labels) === 1) {
                        $user->row_bg_color = $labels->first()->color ?? '#ffffff';
                        $user->row_text_dark = $this->shouldUseDarkText($user->row_bg_color);
                    } elseif (count($labels) > 1) {
                        $user->row_bg_color = '#f8f9fa';
                        $user->row_text_dark = true;
                    } else {
                        $user->row_bg_color = '#ffffff';
                        $user->row_text_dark = true;
                    }
                    
                    // Keep original labels as array of IDs for compatibility
                    $user->labels = $labels->pluck('id')->toArray();
                    
                    // Format created_at for display
                    $user->created_at_formatted = $user->created_at->format('d M Y, h:i A');
                    
                    // Add status badge class
                    $user->status_class = $user->status === 'active' ? 'badge-light-success' : 'badge-light-danger';
                    
                    // Build full name
                    $user->full_name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: $user->email;
                    
                    $departments = $user->departments;
                    $user->department_ids = $departments->pluck('id')->toArray();
                    $user->department_names = $departments->pluck('name')->implode(', ');

                    return $user;
                });
       // });
    }

    public function getPatientsXXX()
    {
        return $this->model
            ->where('user_type', 'patient')
            ->with('labels')
            ->with('departments')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($user) {
                // Get labels with their details
                $labels = $user->labels;
                
                // Store full label objects for display
                $user->treatment_labels = $labels->map(function($label) {
                    return [
                        'id' => $label->id,
                        'name' => $label->label, // Using 'label' field from your data
                        'color' => $label->color ?? '#6c757d',
                        'category' => $label->category ?? 'treatment'
                    ];
                })->toArray();
                
                // Determine background color for the row
                if (count($labels) === 1) {
                    // Single treatment: use the treatment color
                    $user->row_bg_color = $labels->first()->color ?? '#ffffff';
                    // Calculate if we need dark text for contrast
                    $user->row_text_dark = $this->shouldUseDarkText($user->row_bg_color);
                } elseif (count($labels) > 1) {
                    // Multiple treatments: use neutral color
                    $user->row_bg_color = '#f8f9fa'; // Light gray
                    $user->row_text_dark = true;
                } else {
                    // No treatments: default white
                    $user->row_bg_color = '#ffffff';
                    $user->row_text_dark = true;
                }
                
                // Keep original labels as array of IDs for compatibility
                $user->labels = $labels->pluck('id')->toArray();
                
                // Format created_at for display
                $user->created_at_formatted = $user->created_at->format('d M Y, h:i A');
                
                // Add status badge class - handle null status
                $user->status_class = $user->status === 'active' ? 'badge-light-success' : 'badge-light-danger';
                
                // Build full name from first_name and last_name
                $user->full_name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: $user->email;
                $departments = $user->departments;

                // IDs for edit form
                $user->department_ids = $departments->pluck('id')->toArray();

                // Names for table display
                $user->department_names = $departments->pluck('name')->implode(', ');

                return $user;
            });
    }

    
    /**
     * Determine if dark text should be used based on background color
     */
    private function shouldUseDarkText($hexColor)
    {
        // Remove # if present
        $hexColor = ltrim($hexColor, '#');
        
        // Convert to RGB
        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));
        
        // Calculate relative luminance
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        
        // Return true if background is light (needs dark text)
        return $luminance > 0.5;
    }

    /**
     * Get a single patient with labels
     */
    public function getPatient($id)
    {
        return $this->model
            ->where('user_type', 'patient')
            ->with('labels')
            ->with('departments')
            ->find($id);
    }

    /**
     * Get patients by label
     */
    public function getPatientsByLabel($labelId)
    {
        return $this->model
            ->where('user_type', 'patient')
            ->whereHas('labels', function ($query) use ($labelId) {
                $query->where('labels.id', $labelId);
            })
            ->with('labels')
            ->get();
    }

    /**
     * Search patients
     */
    public function searchPatients($searchTerm)
    {
        return $this->model
            ->where('user_type', 'patient')
            ->where(function ($query) use ($searchTerm) {
                $query->where('email', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('patient_id', 'LIKE', "%{$searchTerm}%");
            })
            ->with('labels')
            ->get();
    }

    //Patinets API Methods starts here
    public function updateProfile(User $user, array $data)
    {
        try {
            DB::beginTransaction();

            $user->update([
                'first_name'   => $data['first_name'] ?? $user->first_name,
                'last_name'    => $data['last_name'] ?? $user->last_name,
                'email'        => $data['email'] ?? $user->email,
                'phone' => $data['phone'] ?? $user->phone,
                'country_code' => $data['country_code'] ?? $user->country_code,
                'date_of_birth'=> $data['date_of_birth'] ?? $user->date_of_birth,
                'height'       => $data['height'] ?? $user->height,
                'weight'       => $data['weight'] ?? $user->weight,
            ]);

            DB::commit();

            return $user->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
