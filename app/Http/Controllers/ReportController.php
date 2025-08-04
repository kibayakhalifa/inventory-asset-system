<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Lab;
use App\Models\Item;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Validate request parameters
        $validator = Validator::make($request->all(), [
            'report_type' => ['nullable', Rule::in(['items', 'users', 'transactions', 'labs', 'damaged', 'low_stock'])],
            'lab_id' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $labs = Lab::orderBy('name')->get();
        $reportType = $request->get('report_type');
        $hasData = false;
        $results = null;

        if ($reportType) {
            $results = $this->getReportData($reportType, $request);
            $hasData = $results && (is_array($results) ? count($results) > 0 : $results->isNotEmpty());
        }

        return view('reports.index', [
            'results' => $results,
            'reportType' => $reportType,
            'labs' => $labs,
            'hasData' => $hasData,
            'request' => $request
        ]);
    }

    protected function getReportData($type, $request)
    {
        $perPage = 25;
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;
        $labId = $request->lab_id;

        switch ($type) {
            case 'items':
                return Item::with(['lab', 'latestTransaction'])
                    ->when($labId === 'general', fn($q) => $q->whereNull('lab_id'))
                    ->when($labId && $labId !== 'general', fn($q) => $q->where('lab_id', $labId))
                    ->when($startDate && $endDate, fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                    ->orderBy('name')
                    ->paginate($perPage);

            case 'users':
                return User::query()
                    ->when($startDate && $endDate, fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                    ->orderBy('name')
                    ->paginate($perPage);

            case 'transactions':
                return Transaction::with(['item.lab', 'user', 'student'])
                    ->when($startDate && $endDate, fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                    ->latest()
                    ->paginate($perPage);

            case 'labs':
                return Lab::withCount('items')
                    ->when($startDate && $endDate, fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                    ->orderBy('name')
                    ->paginate($perPage);

            case 'damaged':
                return Transaction::with(['item.lab', 'user'])
                    ->where('condition', 'damaged')
                    ->when($startDate && $endDate, fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                    ->latest()
                    ->paginate($perPage);

            case 'low_stock':
                return Item::with('lab')
                    ->whereColumn('quantity_available', '<', 'reorder_threshold')
                    ->when($labId === 'general', fn($q) => $q->whereNull('lab_id'))
                    ->when($labId && $labId !== 'general', fn($q) => $q->where('lab_id', $labId))
                    ->when($startDate && $endDate, fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                    ->orderBy('quantity_available')
                    ->paginate($perPage);

            default:
                return null;
        }
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:items,users,transactions,labs,damaged,low_stock',
            'lab_id' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $type = $validated['report_type'];
        $data = $this->getExportData($type, $request);

        $fileName = $type . '_report_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($data, $type) {
            $file = fopen('php://output', 'w');

            // Write BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");

            // Write headers
            fputcsv($file, $this->getExportHeaders($type));

            // Write data rows
            foreach ($data as $item) {
                fputcsv($file, $this->formatExportRow($type, $item));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function getExportData($type, $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;
        $labId = $request->lab_id;

        switch ($type) {
            case 'items':
                return Item::with(['lab', 'latestTransaction'])
                    ->when($labId === 'general', fn($q) => $q->whereNull('lab_id'))
                    ->when($labId && $labId !== 'general', fn($q) => $q->where('lab_id', $labId))
                    ->when($startDate && $endDate, fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                    ->orderBy('name')
                    ->get();

            case 'users':
                return User::query()
                    ->when($startDate && $endDate, fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                    ->orderBy('name')
                    ->get();

            case 'transactions':
                return Transaction::with(['item.lab', 'user', 'student'])
                    ->when($startDate && $endDate, fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                    ->latest()
                    ->get();

            case 'labs':
                return Lab::withCount('items')
                    ->when($startDate && $endDate, fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                    ->orderBy('name')
                    ->get();

            case 'damaged':
                return Transaction::with(['item.lab', 'user'])
                    ->where('condition', 'damaged')
                    ->when($startDate && $endDate, fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                    ->latest()
                    ->get();

            case 'low_stock':
                return Item::with('lab')
                    ->whereColumn('quantity_available', '<', 'reorder_threshold')
                    ->when($labId === 'general', fn($q) => $q->whereNull('lab_id'))
                    ->when($labId && $labId !== 'general', fn($q) => $q->where('lab_id', $labId))
                    ->when($startDate && $endDate, fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                    ->orderBy('quantity_available')
                    ->get();

            default:
                return collect();
        }
    }

    protected function getExportHeaders($type)
    {
        switch ($type) {
            case 'items':
                return ['Name', 'Type', 'Lab', 'Total', 'Available', 'Borrowed', 'In Use', 'Reorder Level', 'Condition', 'Last Updated'];
            case 'users':
                return ['Name', 'Email', 'Role', 'Status', 'Joined Date', 'Last Active'];
            case 'transactions':
                return ['Item', 'Student', 'Handled By', 'Department', 'Type', 'Quantity', 'Condition', 'Date'];
            case 'labs':
                return ['Lab Name', 'Description', 'Total Items', 'Created On'];
            case 'damaged':
                return ['Item', 'Lab', 'Reported By', 'Quantity', 'Date Reported'];
            case 'low_stock':
                return ['Item Name', 'Lab', 'Available', 'Threshold', 'Difference', 'Last Updated'];
            default:
                return [];
        }
    }

    protected function formatExportRow($type, $item)
    {
        switch ($type) {
            case 'items':
                return [
                    $item->name,
                    $item->type ?? 'N/A',
                    $item->lab ? $item->lab->name : 'General',
                    $item->quantity_total,
                    $item->quantity_available,
                    $item->total_borrowed,
                    $item->in_use,
                    $item->reorder_threshold,
                    $item->latestTransaction ? $item->latestTransaction->condition : 'Good',
                    $item->updated_at ? $item->updated_at->format('d M Y, H:i') : 'N/A'
                ];

            case 'users':
                return [
                    $item->name,
                    $item->email,
                    $item->getRoleNames()->first() ?? 'User',
                    ucfirst($item->status),
                    $item->created_at->format('d M Y'),
                    $item->last_login_at ? $item->last_login_at->format('d M Y H:i') : 'Never'
                ];

            case 'transactions':
                return [
                    $item->item->name ?? 'N/A',
                    $item->student->name ?? 'N/A',
                    $item->user->name ?? 'System',
                    $item->lab ? $item->lab->name : 'General',
                    ucfirst($item->action),
                    $item->quantity,
                    $item->condition ? ucfirst($item->condition) : 'N/A',
                    $item->created_at->format('M d, Y H:i')
                ];

            case 'labs':
                return [
                    $item->name,
                    $item->description ?? 'N/A',
                    $item->items_count ?? $item->items()->count(),
                    $item->created_at->format('d M Y')
                ];

            case 'damaged':
                return [
                    $item->item->name ?? 'N/A',
                    $item->item->lab->name ?? 'General',
                    $item->user->name ?? 'System',
                    $item->quantity,
                    $item->created_at->format('d M Y H:i')
                ];

            case 'low_stock':
                return [
                    $item->name,
                    $item->lab ? $item->lab->name : 'General',
                    $item->quantity_available,
                    $item->reorder_threshold,
                    $item->quantity_available - $item->reorder_threshold,
                    $item->updated_at->format('d M Y')
                ];

            default:
                return [];
        }
    }
}