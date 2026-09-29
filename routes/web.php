<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\LabelController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SurveyController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/test-email', function () {
    Mail::raw('Hello from Resend 🚀', function ($message) {
        $message->to('codedkolobanny@gmail.com')
                ->subject('Resend Test Mail');
    });

    return 'Email sent!';
});

Route::get('lang/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'nl'])) {
        abort(400);
    }
    session(['locale' => $locale]);

    return redirect()->back();
})->name('lang.switch');


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes(['register' => false, 'verify' => false]);

Route::get('/', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }
    if (Auth::guard('web')->check()) {
        return redirect()->route('patient.dashboard');
    }
    return redirect()->route('login');
});


Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])
     ->name('admin.login.form')
     ->middleware('guest:admin');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login');
});

Route::view('box-layout', 'page_layouts.box_layout')->name('box_layout');

    //consent signing
Route::get('/consent/{id}', [PatientController::class, 'showConsent']) ->name('web.consent.view')
    ->middleware('signed');
    Route::get('/documents/sign/{token}', [DocumentController::class, 'showConsent'])
    ->name('admin.document.sign')
    ->middleware('signed');
//consent response
Route::get('/consent/status/{patient}/{status}', function ($patientId, $status) {
    $patient = \App\Models\User::findOrFail($patientId);
    return response()->json([
        'status' => $status,
        'patient_id' => $patient->id,
    ]);
});

    
// Route::post('/upload-consent-test', function(Request $request) {
//     \Log::info('Test webhook', $request->all());
//     return response()->json($request->all());
// });

// Doctor pages
Route::middleware('auth:admin')->as('admin.')->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard-stats', [DashboardController::class, 'getDashboardStats'])->name('dashboard.stats');
    //Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    

    //labels
    Route::resource('label', LabelController::class);
    Route::get('label', [LabelController::class, 'status'])->name('label.status');
   

    //patient
    Route::get('profile', [PatientController::class, 'profile'])->name('patient_profile');
    Route::get('add_patient', [PatientController::class, 'add_patient'])->name('add_patient');
    Route::get('list-patients',  [PatientController::class, 'index'])->name('list_patients');
    Route::get('permissions-roles', [PatientController::class, 'role_permission'])->name('role_permission');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::match(['put', 'post'], '/patients/{id}', [PatientController::class, 'update'])->name('patients.update');
    Route::get('/edit-patients/{id}', [PatientController::class, 'edit'])->name('patients.edit');
    Route::get('/patient/{id}', [PatientController::class, 'show'])->name('patient.show');
    Route::delete('/patients/{id}', [PatientController::class, 'destroy'])->name('patients.destroy');

    Route::get('/patients_get', [PatientController::class, 'getPatients'])->name('patients.get');
    Route::get('/wearable-data/{id}', [PatientController::class, 'wearableData'])->name('patient.wearable_data');
    Route::get('/get-general-assessment/{id}', [PatientController::class, 'getGeneralAssessment'])->name('patient.get_general_assessment');
    Route::get('get-patient-surveys/{id}', [PatientController::class, 'getPatientSurveys'])->name('patient.get_patient_surveys');
    Route::get('/get-survey-assessment/{id}/{patientId}', [PatientController::class, 'getSurveyAssessment'])->name('patient.get_survey_assessment');
    Route::get('bellscale-assesment/{id}', [PatientController::class, 'getBellscaleAssesment']);
    // Route::delete('/patients/{id}', [PatientController::class, 'destroy'])->name('patients.destroy');
    
 //Departments
 Route::get('/departments', [DepartmentController::class, 'index'])->name('departments');
 //Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
 //Route::match(['put', 'post'], '/update-departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');
 // Store a new department
Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');

// Update an existing department
Route::put('/departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');

 Route::get('/edit-departments/{id}', [DepartmentController::class, 'edit'])->name('departments.edit');
 Route::get('/departments_get', [DepartmentController::class, 'getDepartments'])->name('departments.get');
 Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

 //Documents
 Route::prefix('documents')->group(function () {
    Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/show/{id}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/save-or-update', [DocumentController::class, 'saveOrUpdate'])->name('documents.saveOrUpdate');
    Route::get('/fetch-patients', [DocumentController::class, 'fetchPatients'])->name('documents.fetchPatients');
    Route::get('/fetch-single-document/{id}', [DocumentController::class, 'getPatientsAssignment']);
    Route::get('/fetch-documents', [DocumentController::class, 'getAllDocuments'])->name('documents.fetchDocuments');
    Route::post('/update-status/{id}', [DocumentController::class, 'updateStatus']);
    Route::delete('/delete/{id}', [DocumentController::class, 'destroy']);
    
    Route::get('/download/{token}', [DocumentController::class, 'download'])
    ->name('documents.download')
    ->middleware('signed');
 });
 //Questions
 Route::prefix('questions')->group(function () {
    Route::get('{module}', [QuestionController::class, 'index'])->name('questions.index');
    Route::post('/save', [QuestionController::class, 'save'])->name('questions.save');
    Route::get('/questions_get/{module}', [QuestionController::class, 'fetch'])->name('questions.fetch');
    Route::get('/edit/{id}', [QuestionController::class, 'edit'])->name('questions.edit');
    // GET still uses URL param to fetch options for a specific question
    Route::get('/bellscale_options/{id}', [QuestionController::class, 'getBellscaleOptions']);

    // POST reads question_id from request body
    Route::post('/save_options', [QuestionController::class, 'saveOrUpdateBellscaleOptions']);
    //Route::post('/update_options/{id}', [QuestionController::class, 'saveOrUpdateBellscaleOptions']);
    //Route::get('/list/{module}', [QuestionController::class, 'list'])->name('questions.list');
    Route::post('/update-order/{module}', [QuestionController::class, 'updateOrder'])->name('questions.updateOrder');
    Route::delete('/delete/{id}', [QuestionController::class, 'destroy'])->name('questions.destroy');
});

//survey
Route::prefix('surveys')->group(function () {
    Route::get('/', [SurveyController::class, 'index'])->name('survey.index');
    Route::get('/{id}/edit', [SurveyController::class, 'editSurvey'])->name('survey.edit');
    Route::get('/{id}/get-edit-data', [SurveyController::class, 'getEditData'])->name('survey.edit.data');
    Route::get('/get-data', [SurveyController::class, 'getData'])->name('survey.getData');
    Route::get('/create', [SurveyController::class, 'create'])->name('survey.create');
    Route::get('/show/{id}', [SurveyController::class, 'show'])->name('survey.show');
    Route::post('/save-or-update', [SurveyController::class, 'saveOrUpdate'])->name('survey.saveOrUpdate');
    Route::get('/fetch-patients', [SurveyController::class, 'fetchPatients'])->name('survey.fetchPatients');
    Route::get('/fetch-treatments', [SurveyController::class, 'fetchTreatments'])->name('survey.fetchTreatments');
    Route::get('/fetch-questions', [SurveyController::class, 'fetchQuestions'])->name('survey.fetchQuestions');
    Route::delete('/{id}', [SurveyController::class, 'destroy'])->name('destroy');
    Route::post('/bulk-delete', [SurveyController::class, 'bulkDelete'])->name('survey.bulk-delete');
    Route::get('/get-survey-patients/{id}', [SurveyController::class, 'getSurveyPatients'])->name('survey.getSurveyPatients'); 
});

Route::prefix('reports')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/questions-report', [ReportController::class, 'questionReport'])->name('reports.questionReport');
    Route::get('/single-question-response/{questionId}/{module}', [ReportController::class, 'singleQuestionReport'])->name('reports.single_question_report');
    Route::get('/bellscale-report', [ReportController::class, 'bellscaleReport'])->name('reports.bellscaleReport');
    Route::get('/surveys-report', [ReportController::class, 'getSurveyReport'])->name('reports.getSurveyReport');
    Route::get('/chart-report', [ReportController::class, 'chartReport'])->name('reports.chartReport');
    Route::get('/survey-chart-report', [ReportController::class, 'surveyChartReport'])->name('reports.surveyChartReport');
    Route::get('/questions-report/{module}', [ReportController::class, 'questionsReport'])->name('reports.questions_report');
    Route::get('/get-bellscale-report/{module}', [ReportController::class, 'getBellscaleReport']);

    Route::get('get-chart-report', [ReportController::class, 'getChartReport']);
    Route::get('/question-charts', [ReportController::class, 'getQuestionCharts'])->name('reports.question-charts');

    Route::get('/survey-charts', [ReportController::class, 'getSurveyChartReport'])->name('reports.surveyChartReport');
    Route::get('/get-survey-chart-settings', [ReportController::class, 'getSurveyChartSettings'])->name('reports.surveyChartSettings');
    Route::get('/get-surveys', [ReportController::class, 'getBatchSurveys']);
    Route::get('/get-survey-users/{surveyId}', [ReportController::class, 'userSurveys'])->name('reports.survey.user_surveys');
    Route::get('/survey/{surveyId}', [ReportController::class, 'surveyReport'])
    ->name('reports.survey');

    Route::get('/get-survey-answers/{surveyId}/user/{userId}', [ReportController::class, 'userSurveyReport'])
    ->name('reports.survey.user');
});

Route::prefix('faq')->group(function(){
    Route::get('/', [FaqController::class, 'index'])->name('faq.index');
    Route::post('/save', [FaqController::class, 'saveOrUpdate']);
    Route::get('/get-all-faqs', [FaqController::class, 'fetch']);
    Route::get('show/{id}', [FaqController::class, 'edit']);
    Route::post('/update-order', [FaqController::class, 'updateOrder'])->name('faq.updateOrder');
    Route::post('/save-category', [FaqController::class, 'storeFaqCat'])->name('faq.category.saveOrUpdate');
    Route::delete('/delete/{id}', [FaqController::class, 'destroy'])->name('questions.destroy');
});

//Settings
Route::prefix('settings')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/{survey}/questions', [SettingsController::class, 'surveyQuestions']);

    Route::post('/question-charts/store', [SettingsController::class, 'storeOrUpdate'])
         ->name('admin.question-charts.store');
    Route::post('/survey-charts/store', [SettingsController::class, 'storeOrUpdateSurvey'])
         ->name('admin.survey-charts.store');
         
});


//error_page
Route::view('error-403', 'error_pages.error_403')->name('error_403');
Route::view('error-404', 'error_pages.error_404')->name('error_404');
Route::view('error-500', 'error_pages.error_500')->name('error_500');

//authentication
// Route::view('login', 'authentication.login')->name('login');
Route::view('login-one', 'authentication.login_one')->name('login_one');
Route::view('login-two', 'authentication.login_two')->name('login_two');
Route::view('login-three', 'authentication.login_three')->name('login_three');
Route::view('login-with-tooltip', 'authentication.login_with_tooltip')->name('login_with_tooltip');
Route::view('login-with-sweetalert', 'authentication.login_with_sweetalert')->name('login_with_sweetalert');
Route::view('sign-up', 'authentication.sign_up')->name('sign_up');
Route::view('sign-up-with-bg-image', 'authentication.sign_up_with_bg_image')->name('sign_up_with_bg_image');
Route::view('sign-up-with-image-two', 'authentication.sign_up_with_image_two')->name('sign_up_with_image_two');
Route::view('sign-up-wizard', 'authentication.sign_up_wizard')->name('sign_up_wizard');
Route::view('account-restricted', 'authentication.account_restricted')->name('account_restricted');
Route::view('unlock', 'authentication.unlock')->name('unlock');
Route::view('forget-password', 'authentication.forget_password')->name('forget_password');
Route::view('reset-password', 'authentication.reset_password')->name('reset_password');
Route::view('maintenance', 'authentication.maintenance')->name('maintenance');

//support_ticket
Route::view('support-ticket', 'support_ticket')->name('support_ticket');
});

// patient pages
Route::middleware('auth:web')->prefix('patient')->group(function () {
    Route::get('/dashboard', fn() => view('user.dashboard'));
});


// Route::group(['middleware' => ['auth'], 'as' => 'admin.'], function () {
    
// });





