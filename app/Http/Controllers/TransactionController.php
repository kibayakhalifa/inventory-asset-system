<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Lab;
use App\Models\Item;
use App\Models\Student;
use Illuminate\Support\Facades\DB;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['item', 'student', 'user', 'lab']);

        if ($request->filled('item')) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->item . '%');
            });
        }

        if ($request->filled('student')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->student . '%');
            });
        }

        if ($request->filled('staff')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->staff . '%');
            });
        }

        if ($request->filled('department')) {
            if ($request->department === 'general') {
                $query->whereNull('lab_id');
            } else {
                $query->where('lab_id', $request->department);
            }
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $transactions = $query->latest()->paginate(10);
        $labs = Lab::all();

        return view('transactions.index', compact('transactions', 'labs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Item::all();
        $students = Student::all();
        $labs = Lab::all();
        return view('transactions.create', compact('items', 'students', 'labs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'student_id' => 'required|exists:students,id',
            'quantity' => 'required|integer|min:1',
            'action' => 'required|in:issue,return',
            'lab_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value === 'general') {
                        return; // Accept 'general' as valid
                    }
                    if ($value && !Lab::where('id', $value)->exists()) {
                        $fail('The selected department is invalid.');
                    }
                }
            ],
        ]);

        // Start database transaction
        DB::beginTransaction();

        try {
            // Get the item with lock for update
            $item = Item::lockForUpdate()->findOrFail($request->item_id);

            // Validate quantity for issue action
            if ($request->action === 'issue' && $item->quantity_available < $request->quantity) {
                throw new \Exception("Not enough items in stock. Available: {$item->quantity_available}");
            }

            // Handle lab_id - convert 'general' to null
            $lab_id = $request->lab_id === 'general' ? null : $request->lab_id;

            // Create the transaction
            $transaction = Transaction::create([
                'item_id' => $request->item_id,
                'student_id' => $request->student_id,
                'user_id' => auth()->id(),
                'action' => $request->action,
                'quantity' => $request->quantity,
                'lab_id' => $lab_id
            ]);

            // Update item quantity
            if ($request->action === 'issue') {
                $item->decrement('quantity_available', $request->quantity);
            } else {
                $item->increment('quantity_available', $request->quantity);
            }

            DB::commit();

            return redirect()
                ->route('transactions.index')
                ->with('success', 'Transaction recorded successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Transaction failed: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
                'user' => auth()->id()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = Transaction::with(['item', 'student', 'user', 'lab'])
            ->findOrFail($id);

        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transaction = Transaction::findOrFail($id);
        $items = Item::all();
        $students = Student::all();
        $labs = Lab::all();

        return view('transactions.edit', compact('transaction', 'items', 'students', 'labs'));
    }

    public function update(Request $request, string $id)
    {
        $transaction = Transaction::findOrFail($id);

        // Same validation as store method
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'student_id' => 'required|exists:students,id',
            'quantity' => 'required|integer|min:1',
            'action' => 'required|in:issue,return',
            'lab_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value === 'general')
                        return;
                    if ($value && !Lab::where('id', $value)->exists()) {
                        $fail('The selected department is invalid.');
                    }
                }
            ],
        ]);

        DB::beginTransaction();
        try {
            $item = Item::lockForUpdate()->findOrFail($request->item_id);
            $lab_id = $request->lab_id === 'general' ? null : $request->lab_id;

            // Calculate quantity difference
            $quantityChange = $request->quantity - $transaction->quantity;
            $actionChanged = $request->action !== $transaction->action;

            // Reverse the original transaction's effect
            if ($transaction->action === 'issue') {
                $item->increment('quantity_available', $transaction->quantity);
            } else {
                $item->decrement('quantity_available', $transaction->quantity);
            }

            // Apply the new transaction's effect
            if ($request->action === 'issue') {
                // For issue action, we need to check stock
                if ($item->quantity_available < $request->quantity) {
                    throw new \Exception("Not enough items in stock. Available: {$item->quantity_available}");
                }
                $item->decrement('quantity_available', $request->quantity);
            } else {
                $item->increment('quantity_available', $request->quantity);
            }

            // Update the transaction
            $transaction->update([
                'item_id' => $request->item_id,
                'student_id' => $request->student_id,
                'action' => $request->action,
                'quantity' => $request->quantity,
                'lab_id' => $lab_id
            ]);

            DB::commit();

            return redirect()->route('transactions.index')
                ->with('success', 'Transaction updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Transaction update failed: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $transaction = Transaction::findOrFail($id);
            $item = $transaction->item;

            // Reverse the transaction's effect on inventory
            if ($transaction->action === 'issue') {
                $item->increment('quantity_available', $transaction->quantity);
            } else {
                // For returns, ensure we don't go negative
                if ($item->quantity_available < $transaction->quantity) {
                    throw new \Exception("Cannot delete return transaction - would result in negative inventory");
                }
                $item->decrement('quantity_available', $transaction->quantity);
            }

            $transaction->delete();

            DB::commit();

            return redirect()
                ->route('transactions.index')
                ->with('success', 'Transaction deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Transaction deletion failed: ' . $e->getMessage());

            return redirect()
                ->route('transactions.show', $id)
                ->with('error', 'Failed to delete transaction: ' . $e->getMessage());
        }
    }
}
