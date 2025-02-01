<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::all();
        return Inertia::render('Patients/Index', ['patients' => $patients]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'contact_info' => 'nullable|string|max:255',
        ]);
        Patient::create($validatedData);
        return back()->with('status', 'Patient created successfully.');
    }

    public function update(Request $request, Patient $patient)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'contact_info' => 'nullable|string|max:255',
        ]);
        $patient->update($validatedData);
        return back()->with('status', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return back()->with('status', 'Patient deleted successfully.');
    }
}
