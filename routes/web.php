<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DentistController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProcedureController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/analisi-avanzata', function () {
    return Inertia::render('AnalisiAvanzata');
})->middleware(['auth', 'verified'])->name('analisi-avanzata');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::put('/appointments/{appointment}', [AppointmentController::class, 'update']);
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy']);
    Route::get('/patients', [PatientController::class, 'index']);
    Route::post('/patients', [PatientController::class, 'store']);
    Route::put('/patients/{patient}', [PatientController::class, 'update']);
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy']);
    Route::get('/dentists', [DentistController::class, 'index']);
    Route::post('/dentists', [DentistController::class, 'store']);
    Route::put('/dentists/{dentist}', [DentistController::class, 'update']);
    Route::delete('/dentists/{dentist}', [DentistController::class, 'destroy']);
    Route::get('/procedures', [ProcedureController::class, 'index']);
    Route::post('/procedures', [ProcedureController::class, 'store']);
    Route::put('/procedures/{procedure}', [ProcedureController::class, 'update']);
    Route::delete('/procedures/{procedure}', [ProcedureController::class, 'destroy']);
    Route::get('/dashboard', [AppointmentController::class, 'index'])->name('dashboard');
    Route::get('/gestione-appuntamenti', [AppointmentController::class, 'index'])->name('gestione-appuntamenti');
    Route::get('/anagrafica-pazienti', [PatientController::class, 'Index'])->name('anagrafica-pazienti');
    Route::get('/anagrafica-dentisti', [DentistController::class, 'Index'])->name('anagrafica-dentisti');
    Route::get('/gestione-procedure', [ProcedureController::class, 'Index'])->name('gestione-procedure');
});

require __DIR__ . '/auth.php';
