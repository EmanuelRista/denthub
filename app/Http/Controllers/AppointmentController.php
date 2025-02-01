<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return Appointment::with(['patient', 'dentist', 'procedure'])->get();
    }

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
        return response()->json($appointment, 201);
    }

    public function update(Request $request, Appointment $appointment)
    {
        // Validazione e aggiornamento
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response()->json(null, 204);
    }
}
