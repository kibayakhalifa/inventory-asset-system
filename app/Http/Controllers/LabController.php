<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use Illuminate\Http\Request;

class LabController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $labs = Lab::withCount('items')->get(); // gets labs with item counts
        return view('labs.index', compact('labs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('labs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|in:'.implode(',', Lab::ALLOWED_LAB_NAMES),
            'description' => 'nullable|string',
            'location' => 'required|string',
            'status' => 'required|in:Active,Maintenance,Closed'
        ]);

        Lab::create($validated);

        return redirect()->route('labs.index')
            ->with('success', 'Lab created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lab $lab)
    {
        $lab->loadCount('items');
        return view('labs.show', compact('lab'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lab $lab)
    {
        return view('labs.edit', compact('lab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lab $lab)
    {
        $validated = $request->validate([
            'name' => 'required|string|in:'.implode(',', Lab::ALLOWED_LAB_NAMES),
            'description' => 'nullable|string',
            'location' => 'required|string',
            'status' => 'required|in:Active,Maintenance,Closed'
        ]);

        $lab->update($validated);

        return redirect()->route('labs.index')
            ->with('success', 'Lab updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lab $lab)
    {
        $lab->delete();
        return redirect()->route('labs.index')
            ->with('success', 'Lab deleted successfully.');
    }
}
