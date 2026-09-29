<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Document\DocumentController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\Notifications\NotificationController;
use App\Http\Controllers\Api\Patient\PatientController;
use App\Http\Controllers\Api\Questions\QuestionsController;
use App\Http\Controllers\Api\Surveys\SurveyController;
use Illuminate\Support\Facades\Route;

Route::post('/upload-consent', [PatientController::class, 'uploadConsent']);
Route::prefix('v1')->middleware(['api.locale:nl'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/upload-consent/{id}', [PatientController::class, 'uploadConsent']);
        //FCM TOKEN
        Route::post('/device/token', [PatientController::class, 'storeFcmToken']);
    });

    Route::middleware('auth:sanctum')->prefix('questions')->group(function () {
        Route::get('/general', [QuestionsController::class, 'getGeneralQuestions']);
        Route::post('/store-answers', [QuestionsController::class, 'postGeneralAnswers']);
        // Bellscale routes
        Route::get('/bellscale', [QuestionsController::class, 'getBellscaleQuestions']);
        Route::post('/store-bellscale-answers', [QuestionsController::class, 'postBellscaleAnswers']);
    });

    Route::middleware('auth:sanctum')->prefix('surveys')->group(function () {
        Route::get('/data', [SurveyController::class, 'getData']);
        Route::get('/fetch-surveys', [SurveyController::class, 'getPatientSurveyApi']);
        Route::get('/survey/{id}', [SurveyController::class, 'getSingleSurveyApi']);
        Route::post('/save-answers', [SurveyController::class, 'saveAnswers']);
    });

    Route::middleware('auth:sanctum')->prefix('patients')->group(function () {
        Route::post('update-profile', [PatientController::class, 'updateProfile']);
    });

    Route::middleware('auth:sanctum')->prefix('documents')->group( function() {
        Route::get('/get-signed-docs', [DocumentController::class, 'getSignedDocuments']);
        Route::get('/get-simple-docs', [DocumentController::class, 'getSimpleDocuments']);
        Route::get('/download/{token}', [DocumentController::class, 'download']);
    });

    Route::middleware('auth:sanctum')->prefix('faq')->group(function () {
        Route::get('/', [FaqController::class, 'index']);
        Route::post('/track-video', [FaqController::class, 'trackVideo']);
    });

    Route::middleware('auth:sanctum')->prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/{id}', [NotificationController::class, 'show']);
        Route::post('/read/{id}', [NotificationController::class, 'viewNotification']);
    });
});