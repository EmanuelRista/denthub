<?php

namespace App\Http\Controllers;

use App\Models\Procedure;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProcedureController extends Controller
{
    /**
     * Display a listing of the procedures.
     */
    public function index()
    {
        $procedures = Procedure::all();
        return Inertia::render('GestioneProcedure', ['procedures' => $procedures]);
    }

    /**
     * Store a newly created procedure in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
        ]);
        Procedure::create($validatedData);
        return back()->with('status', 'Procedure created successfully.');
    }

    /**
     * Update the specified procedure in storage.
     */
    public function update(Request $request, Procedure $procedure)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
        ]);
        $procedure->update($validatedData);
        return back()->with('status', 'Procedure updated successfully.');
    }

    /**
     * Remove the specified procedure from storage.
     */
    public function destroy(Procedure $procedure)
    {
        $procedure->delete();
        return back()->with('status', 'Procedure deleted successfully.');
    }
}
