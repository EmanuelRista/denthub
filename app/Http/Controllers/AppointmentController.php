<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the appointments.
     */
    public function index()
    {
        $appointments = Appointment::with(['patient', 'dentist', 'procedure'])->get();
        return Inertia::render('Appointments/Index', [
            'appointments' => $appointments,
        ]);
    }

    /**
     * Display appointments in the data management view.
     */
    public function dataManagement()
    {
        $appointments = Appointment::with(['patient', 'dentist', 'procedure'])->get();
        return Inertia::render('DataManagement', [
            'appointments' => $appointments,
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
        // Validazione dei dati
        $validatedData = $request->validate([
            // Validazioni per l'aggiornamento
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
