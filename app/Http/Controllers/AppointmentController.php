<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Dentist;
use App\Models\Procedure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;


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
        } else if ($request->route()->getName() === 'analisi-avanzata') {
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
            // Altri campi con le loro validazioni
        ]);

        $appointment = Appointment::create($validatedData);

        // In un'applicazione Inertia.js, potresti voler reindirizzare o ritornare una risposta JSON
        // per aggiornare la vista senza ricaricare la pagina
        return back()->with('status', 'Appointment created successfully.');
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
            // Altri campi se necessario
        ]);

        $appointment->update($validatedData);
        return back()->with('status', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified appointment from storage.
     */
    public function destroy(Request $request, Appointment $appointment)
    {
        $appointment->delete();

        return back()->with('status', 'Appointment deleted successfully.');
    }
}
