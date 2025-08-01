<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use App\Models\Item;
use App\Models\User;
use App\Models\Student;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $totalItems = Item::count();
        $totalQuantity = Item::sum('quantity_available');
        $lowStockCount = Item::whereColumn('quantity_available', '<', 'reorder_threshold')->count();
        $systemUsers = User::count();
        $studentsCount = Student::count();
        $labCount = Lab::count();

        // Items per Lab
        $labs = Lab::select('id', 'name')->get();
        $labNames = [];
        $itemCounts = [];
        $lowStockNames = [];
        $lowStockCounts = [];

        foreach ($labs as $lab) {
            $labNames[] = $lab->name;
            $itemCounts[] = Item::where('lab_id', $lab->id)->count();

            $lowStockCountInLab = Item::where('lab_id', $lab->id)
                ->whereColumn('quantity_available', '<', 'reorder_threshold')
                ->count();

            $lowStockNames[] = $lab->name;
            $lowStockCounts[] = $lowStockCountInLab;
        }

        // General category (null lab_id)
        $labNames[] = 'General';
        $itemCounts[] = Item::whereNull('lab_id')->count();
        $lowStockNames[] = 'General';
        $lowStockCounts[] = Item::whereNull('lab_id')
            ->whereColumn('quantity_available', '<', 'reorder_threshold')
            ->count();

        // Transaction chart
        $issuedCount = Transaction::where('action', 'issue')->count();
        $returnedCount = Transaction::where('action', 'return')->count();

        // Low Stock Items per Lab
        $lowStockItems = Item::whereColumn('quantity_available', '<', 'reorder_threshold')->get();
        $groupedLowStock = $lowStockItems->groupBy(function ($item) {
            return optional($item->lab)->name ?? 'General';
        });

        $lowStockNames = [];
        $lowStockCounts = [];

        foreach ($groupedLowStock as $labName => $items) {
            $lowStockNames[] = $labName;
            $lowStockCounts[] = $items->count();
        }


        return view('dashboard', compact(
            'totalItems',
            'totalQuantity',
            'lowStockCount',
            'systemUsers',
            'studentsCount',
            'labCount',
            'labNames',
            'itemCounts',
            'issuedCount',
            'returnedCount',
            'lowStockNames',
            'lowStockCounts'
        ));

    }
}
