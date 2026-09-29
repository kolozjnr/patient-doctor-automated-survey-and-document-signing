<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\FaqVideouser;
use App\Models\User;
use App\Repositories\Admin\PatientRepository;
use App\Services\BunnyStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    public function __construct(PatientRepository $patientRepository)
    {
        $this->patientRepository = $patientRepository;
    }

    public function login(Request $request)
    {
        // Validation messages come automatically from lang/nl/validation.php
        $request->validate([
            'patient_id'    => ['required', 'string'],
            'date_of_birth' => ['required', 'date'],
            //'fcm_token'     => ['nullable', 'string'],
        ]);

        $key = 'login|' . $request->patient_id . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'message' => 'Te veel pogingen. Probeer het later opnieuw.',
            ], 429);
        }

        $user = User::select([
                'id', 'patient_id', 'first_name', 'last_name',
                'requires_consent', 'status', 'consent', 'date_of_birth', 'email',
            ])
            ->where('patient_id', $request->patient_id)
            ->where('date_of_birth', $request->date_of_birth)
            ->first();

        if (!$user) {
            RateLimiter::hit($key, 60);
            return response()->json([
                'message' => 'Ongeldige inloggegevens.',
            ], 401);
        }

        RateLimiter::clear($key);

        if ($user->status === UserStatus::New) {
            $user->update(['status' => UserStatus::LoggedIn]);
        }
        // if ($request->filled('fcm_token')) {
        //     $user->update(['fcm_token' => $request->fcm_token]);
        // }


        $token = $user->createToken('auth_token')->plainTextToken;
        $bellscaleStatus = $this->patientRepository->getBellscaleStatus($user->id);
        $videoRecord = FaqVideouser::where('user_id', $user->id)->first();

        $response = [
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user' => [
                'id'               => $user->id,
                'patient_id'       => $user->patient_id,
                'first_name'       => $user->first_name,
                'last_name'        => $user->last_name,
                'email'            => $user->email,
                'requires_consent' => $user->requires_consent,
                'status'           => $user->status,
                'is_bellscale_due' => $bellscaleStatus['is_due'],
                'faq_video'        => (bool) ($videoRecord?->watch_status ?? false),
            ],
        ];

        if (empty($user->consent)) {
            $docusealUrl = $this->patientRepository->createDocuSealSubmission($user);

            if ($docusealUrl) {
                $response['consent_url'] = URL::temporarySignedRoute(
                    'web.consent.view',
                    now()->addMinutes(50),
                    ['id' => $user->id]
                );
            }
        }

        return response()->json($response);
    }



}
