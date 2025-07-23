<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Lab;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a list of items.
     */
    public function index()
    {
        $items = Item::with('lab')->latest()->paginate(10);
        return view('items.index', compact('items'));
    }

    /**
     * Show the form to create a new item.
     */
    public function create()
    {
        $labs = Lab::all();
        return view('items.create', compact('labs'));
    }

    /**
     * Store a new item in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:100',
            'lab_id' => 'nullable|exists:labs,id',
            'quantity_total' => 'required|integer|min:0',
            'quantity_available' => 'required|integer|min:0',
            'issued_once' => 'nullable|boolean',
            'reorder_threshold' => 'nullable|integer|min:0',
        ]);

        Item::create($validated);

        return redirect()->route('items.index')->with('success', 'Item added successfully.');
    }

    /**
     * Display a single item.
     */
    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing an item.
     */
    public function edit(Item $item)
    {
        $labs = Lab::all();
        return view('items.edit', compact('item', 'labs'));
    }

    /**
     * Update an existing item.
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:100',
            'lab_id' => 'nullable|exists:labs,id',
            'quantity_total' => 'required|integer|min:0',
            'quantity_available' => 'required|integer|min:0',
            'issued_once' => 'nullable|boolean',
            'reorder_threshold' => 'nullable|integer|min:0',
        ]);

        $item->update($validated);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Delete an item.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
