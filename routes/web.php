<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\SymptomController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AnimalDiseaseController;
use App\Http\Controllers\AnimalSymptomController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::middleware(['auth', 'isadmin'])->group(function () {
    Route::resource('users', UserController::class);
    
    Route::resource('diseases', DiseaseController::class)->except(['show']);
    Route::resource('symptoms', SymptomController::class)->except(['show']);
    Route::resource('animals', AnimalController::class)->except(['show']);
    Route::resource('animal_symptoms', AnimalSymptomController::class)->except(['show']);
    Route::resource('animal_diseases', AnimalDiseaseController::class)->except(['show']);
    Route::resource('rules', RuleController::class)->except(['show']);
});
Route::middleware('auth')->group(function () {
    Route::resource('history', HistoryController::class);
    Route::get('/diagnosis/result', [DiagnosisController::class, 'result'])->name('diagnosis.result');
    Route::get('/diagnosis', [DiagnosisController::class, 'index'])->name('diagnosis');
    Route::get('/diagnosis/symptoms', [DiagnosisController::class, 'getSymptoms'])->name('diagnosis.symptoms');
    Route::post('/diagnosis/process', [DiagnosisController::class, 'process'])->name('diagnosis.process');
});
