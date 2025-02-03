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
        return Inertia::render('AnagraficaPazienti', ['patients' => $patients]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'contact_info' => 'nullable|string|max:255',
        ]);
        Patient::create($validatedData);
        session()->flash('status', 'Patient created successfully');
        return Inertia::location(back()->getTargetUrl());
    }

    public function update(Request $request, Patient $patient)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'contact_info' => 'nullable|string|max:255',
        ]);
        $patient->update($validatedData);
        session()->flash('status', 'Patient updated successfully');
        return Inertia::location(back()->getTargetUrl());
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        session()->flash('status', 'Patient deleted successfully');
        return Inertia::location(back()->getTargetUrl());
    }
}
