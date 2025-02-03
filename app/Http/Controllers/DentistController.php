<?php

namespace App\Http\Controllers;

use App\Models\Dentist;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DentistController extends Controller
{
    /**
     * Display a listing of the dentists.
     */
    public function index()
    {
        $dentists = Dentist::all();
        return Inertia::render('AnagraficaDentisti', ['dentists' => $dentists]);
    }

    /**
     * Store a newly created dentist in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
        ]);
        Dentist::create($validatedData);
        session()->flash('status', 'Dentist created successfully');
        return Inertia::location(back()->getTargetUrl());
    }

    /**
     * Update the specified dentist in storage.
     */
    public function update(Request $request, Dentist $dentist)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
        ]);
        $dentist->update($validatedData);
        session()->flash('status', 'Dentist updated successfully');
        return Inertia::location(back()->getTargetUrl());
    }

    /**
     * Remove the specified dentist from storage.
     */
    public function destroy(Dentist $dentist)
    {
        $dentist->delete();
        session()->flash('status', 'Dentist deleted successfully');
        return Inertia::location(back()->getTargetUrl());
    }
}
