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
        return Inertia::render('Dentists/Index', ['dentists' => $dentists]);
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
        return back()->with('status', 'Dentist created successfully.');
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
        return back()->with('status', 'Dentist updated successfully.');
    }

    /**
     * Remove the specified dentist from storage.
     */
    public function destroy(Dentist $dentist)
    {
        $dentist->delete();
        return back()->with('status', 'Dentist deleted successfully.');
    }
}
