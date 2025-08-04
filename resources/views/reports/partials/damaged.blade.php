@if ($data->isEmpty())
    <div class="table-empty">
        <i class="fas fa-exclamation-triangle fa-2x"></i>
        <h4>No Damaged Items Found</h4>
        <p>No damaged items match the selected criteria</p>
    </div>
@else
    <div class="table-container">
        <table class="report-table striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Lab</th>
                    <th>Reported By</th>
                    <th class="numeric-cell">Quantity</th>
                    <th>Date Reported</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $record)
                    <tr>
                        <td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
                        <td>{{ $record->item->name ?? 'N/A' }}</td>
                        <td>{{ $record->item->lab->name ?? 'General' }}</td>
                        <td>{{ $record->user->name ?? 'System' }}</td>
                        <td class="numeric-cell">{{ $record->quantity }}</td>
                        <td>{{ $record->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="table-pagination mt-3">
        {{ $data->appends($request->query())->links() }}
    </div>
@endif