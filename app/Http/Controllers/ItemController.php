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
    public function index(Request $request)
    {
        // base query
        $query = Item::with('lab')->latest();

        //aply search filter 
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        //apply type filter
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }
        //apply lab filter
        if ($request->has('lab')) {
            if ($request->lab === 'general') {
                $query->whereNull('lab_id');
            } elseif ($request->lab != '') {
                $query->where('lab_id', $request->lab);
            }
        }
        //paginate results 
        $items = $query->paginate(10)->appends($request->query());

        // Get labs - ensuring no duplicates
    $labs = Lab::select('id', 'name')
              ->groupBy('id', 'name')
              ->orderBy('name', 'asc')
              ->get();
    
    
    
    return view('items.index', [
        'items' => $items,
        'labs' => $labs
    ]);
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
        'quantity_to_add' => 'required|integer|min:1',
        'reorder_threshold' => 'nullable|integer|min:0',
        'issued_once' => 'nullable|boolean',
    ]);

    $quantity = $validated['quantity_to_add'];

    $item = new Item();
    $item->name = $validated['name'];
    $item->type = $validated['type'] ?? null;
    $item->lab_id = $validated['lab_id'] ?? null;
    $item->quantity_total = $quantity;
    $item->quantity_available = $quantity;
    $item->reorder_threshold = $validated['reorder_threshold'] ?? 0;
    $item->issued_once = $request->has('issued_once') ? true : false;

    $item->save();

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
