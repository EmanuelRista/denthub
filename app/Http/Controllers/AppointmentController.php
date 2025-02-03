<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Dentist;
use App\Models\Procedure;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the appointments.
     */
    public function index(Request $request)
    {
        $appointments = Appointment::with(['patient', 'dentist', 'procedure'])->get();
        $patients = Patient::all()->toArray();
        $dentists = Dentist::all()->toArray();
        $procedures = Procedure::all()->toArray();

        if ($request->route()->getName() === 'dashboard') {
            $view = 'Dashboard';
        } elseif ($request->route()->getName() === 'analisi-avanzata') {
            $view = 'AnalisiAvanzata';
        } else {
            $view = 'GestioneAppuntamenti';
        }

        return Inertia::render($view, [
            'appointments' => $appointments,
            'patients' => $patients,
            'dentists' => $dentists,
            'procedures' => $procedures,
        ]);
    }

    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        // Validazione dei dati
        $validatedData = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:dentists,id',
            'procedure_id' => 'required|exists:procedures,id',
            'appointment_date' => 'required|date',
            'appointment_status' => 'required|in:scheduled,completed,cancelled',
        ]);

        $appointment = Appointment::create($validatedData);

        session()->flash('status', 'Appointment created successfully');
        return Inertia::location(back()->getTargetUrl());
    }

    /**
     * Update the specified appointment in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validatedData = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:dentists,id',
            'procedure_id' => 'required|exists:procedures,id',
            'appointment_date' => 'required|date',
            'appointment_status' => 'required|in:scheduled,completed,cancelled',
        ]);

        $appointment->update($validatedData);
        session()->flash('status', 'Appointment updated successfully');
        return Inertia::location(back()->getTargetUrl());
    }
    /**
     * Remove the specified appointment from storage.
     */
    public function destroy(Request $request, Appointment $appointment)
    {
        $appointment->delete();
        session()->flash('status', 'Appointment deleted successfully');
        return Inertia::location(back()->getTargetUrl());
    }
}
