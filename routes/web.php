<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\SymptomController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\UserController;

// Route login dan logout
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Route dashboard
Route::get('/', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Route untuk lupa password
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request')->middleware('guest');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email')->middleware('guest');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset')->middleware('guest');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])
    ->name('password.update')->middleware('guest');

// ========================
//      Master Data
// ========================
Route::middleware(['auth'])->group(function () {
    // Data Penyakit
    Route::resource('diseases', DiseaseController::class)->except(['show']);

    // Data Gejala
    Route::resource('symptoms', SymptomController::class)->except(['show']);

    // ========================
    //      Sistem Pakar
    // ========================
    // Kelola Rule (Certainty Factor)
    Route::resource('rules', RuleController::class)->except(['show']);

    // ========================
    //      Riwayat Diagnosa
    // ========================
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');

    // ========================
    //      Manajemen Pengguna
    // ========================
    Route::resource('users', UserController::class)->middleware('admin');
});
