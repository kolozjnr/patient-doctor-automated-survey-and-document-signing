<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Label;
use App\Models\User;
use App\Repositories\Admin\PatientRepository;
use App\Services\BunnyStorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


    

    
class PatientController extends Controller
{
    protected $patientRepository;

    public function __construct(PatientRepository $patientRepository, BunnyStorageService $bunnyStorageService)
    {
        $this->patientRepository = $patientRepository;
        $this->bunnyStorageService = $bunnyStorageService;
    }
    public function index()
    {
        $labels = Label::all();
        //dd($labels);
        $departments = Department::all();
        return view('patients.patient_list', compact('labels', 'departments'));
    }

    // public function llist()
    // {
    //     $labels = Label::where('category', 'treatment')->get();
    //     $departments = Department::all();
    //     return view('patients.products', compact('labels', 'departments'));
    // }
  
    public function add_patient()
    {
        return view('patients.add_patient');
    }

    public function showConsent(Request $request, $id)
    {
        $patient = $this->patientRepository->show($id);
        
        abort_if(!$patient, 404);

        if (filled($patient->consent)) {
            return redirect('myapp://consent-complete?status=already_signed');
        }

        // Get cached URL or create new submission
        $submissionUrl = Cache::get("docuseal_url:{$patient->id}");
        
        if (!$submissionUrl) {
            $submissionUrl = $this->patientRepository->createDocuSealSubmission($patient);
            
            if (!$submissionUrl) {
                abort(500, 'Unable to generate consent form');
            }
        }

        return view('patients.consent-sign', [
            'patient' => $patient,
            'submissionUrl' => $submissionUrl
        ]);
    }

   public function consentStatus($status)
    {
        // Validate status parameter
        if (!in_array($status, ['success', 'failed', 'cancelled', 'skipped'])) {
            abort(404);
        }
        
        return view('patients.consent-status', ['status' => $status]);
    }


    public function getGeneralAssessment($id)
    {
        $patient = $this->patientRepository->getGeneralAssessment($id);
        abort_if(!$patient, 404);

        return response()->json([
            'success' => true,
            'data' => $patient
        ]);
    }
    public function getPatientSurveys($id): JsonResponse
    {
        try {
                // Get surveys grouped by batch_uuid
                $surveys = $this->patientRepository->getPatientSurveys($id);

                return response()->json([
                    'success' => true,
                    'data' => $surveys
                ]);

            } catch (\Exception $e) {
                Log::error('Get surveys data error: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to load surveys data',
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
    }
    
    public function getSurveyAssessment($id, $patientId)
    {
        $survey = $this->patientRepository->getSurveyAssessment($id, $patientId);
        abort_if(!$survey, 404);

        return response()->json([
            'success' => true,
            'data' => $survey
        ]);
    }

    public function getBellscaleAssesment($id)
    {
        $bellscale = $this->patientRepository->getBellscaleAssesment($id);
        abort_if(!$bellscale, 404);

        return response()->json([
            'success' => true,
            'data' => $bellscale
        ]);
    }

    public function role_permission()
    {
        return view('patients.role_permission');
    }

    /**
     * Store a new patient
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|unique:users,patient_id',
            'first_name' => 'required|string|min:2',
            'last_name' => 'required|string|min:2',
            'email' => 'required|email|unique:users,email',
            'date_of_birth' => 'required|date',
            'opt_for_daily' => 'required',
            'user_type' => 'required|in:patient,employee',
            'labels' => 'required|array|min:1',
            'labels.*' => 'exists:labels,id',
            'departments' => 'required|array|min:1',
            'departments.*' => 'exists:departments,id',
        ],
        
        // [
        //     'patient_id.required' => 'Patient ID is required.',
        //     'patient_id.unique' => 'This Patient ID already exists. Please use a different one.',

        //     'first_name.required' => 'First Name is required',
        //     'last_name.required' => 'Last Name is required',

        //     'email.required' => 'Email address is required.',
        //     'email.email' => 'Please enter a valid email address.',
        //     'email.unique' => 'This email is already registered.',

        //     'date_of_birth.required' => 'Date of birth is required.',
        //     'date_of_birth.date' => 'Please provide a valid date of birth.',

        //     'opt_for_daily.required' => 'Please select a daily notification preference.',

        //     'user_type.required' => 'User type is required.',
        //     'user_type.in' => 'Invalid user type selected.',

        //     'labels.required' => 'Please select at least one label.',
        //     'labels.min' => 'Please select at least one label.',

        //     'departments.required' => 'Please select at least one department.',
        //     'departments.min' => 'Please select at least one department.',
        // ]
        
        );

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('validation.failed'),
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }


        try {
            $patient = $this->patientRepository->createPatient($request->all());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('patients.created_successfully'),
                    'data' => $patient
                ], 201);
            }

            return redirect()->back()->with('success', __('patients.created_successfully'));
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create patient: ' . $e->getMessage()
                ], 422);
            }
            return redirect()->back()->with('error', __('patients.create_failed'))->withInput();
        }
    }

    /**
     * Update an existing patient
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'date_of_birth' => 'required|date',
            'opt_for_daily' => 'sometimes',
            'user_type' => 'sometimes|in:patient,employee',
            'labels' => 'sometimes|array|min:1',
            'labels.*' => 'exists:labels,id',
            'departments' => 'sometimes|array|min:1',
            'departments.*' => 'exists:departments,id',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $patient = $this->patientRepository->updatePatient($id, $request->all());

            if (!$patient) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Patient not found'
                    ], 404);
                }
                return redirect()->back()->with('error', 'Patient not found');
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Patient updated successfully',
                    'data' => $patient
                ], 200);
            }

            return redirect()->back()->with('success', 'Patient updated successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update patient: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Failed to update patient')->withInput();
        }
    }

    /**
     * Get all patients
     */
    public function getPatients()
    {
        try {
            $patients = $this->patientRepository->getPatients();
            
             $patientsWithSignedUrls = $patients->map(function ($patient) {
            // Generate signed URL if consent exists
            if (!empty($patient->consent)) {
                // Extract just the path from the full CDN URL
                // From: https://ecc-pain.b-cdn.net/patients/7/consent/signed_1769518281_zo7Ad.pdf
                // To: patients/7/consent/signed_1769518281_zo7Ad.pdf
                
                $cdnUrl = rtrim(config('services.bunny.cdn_url'), '/'); // https://ecc-pain.b-cdn.net
                $path = str_replace($cdnUrl . '/', '', $patient->consent);
                
                // Generate signed URL (expires in 24 hours)
                $patient->consent_signed_url = $this->bunnyStorageService->getSignedUrl($path, 86400);
            } else {
                $patient->consent_signed_url = null;
            }
            
            return $patient;
        });

            return response()->json([
                'success' => true,
                'data' => $patientsWithSignedUrls
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch patients: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single patient for editing
     */
    public function edit($id)
    {
        try {
            $patient = $this->patientRepository->getPatient($id);

            if (!$patient) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $patient
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch patient: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function show($id)
    {
        try {
            $patient = $this->patientRepository->show($id);

            if (!$patient) {
                return redirect()->back()->with('error', 'Patient not found!');
            }

            return view('patients.show', compact('patient'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to fetch patient: ' . $e->getMessage());
        }
    }

    /**
     * Smart wearable devices
     */

    public function wearableData($id)
    {
        $wearables = $this->patientRepository->wearableData($id);

        if (!$wearables) {
            return response()->json([
                'success' => false,
                'message' => 'Wearable data not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $wearables
        ], 200);
    }

      /**
     * Delete a patient
     */
    public function destroy($id)
    {
        try {
            //dd($id);
            $deleted = $this->patientRepository->deletePatient($id);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Patient deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete patient: ' . $e->getMessage()
            ], 500);
        }
    }
    
}




