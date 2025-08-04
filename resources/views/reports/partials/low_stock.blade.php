@if($data->isEmpty())
    <div class="table-empty">
        <i class="fas fa-box-open fa-2x"></i>
        <h4>No Low Stock Items</h4>
        <p>No items are below their reorder threshold</p>
    </div>
@else
    <div class="table-container">
        <table class="report-table striped">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Lab</th>
                    <th class="numeric-cell">Available</th>
                    <th class="numeric-cell">Threshold</th>
                    <th class="numeric-cell">Difference</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                    <tr class="{{ $item->quantity_available <= 0 ? 'table-danger-row' : '' }}">
                        <td>{{ $item->name }}</td>
                        <td>{{ optional($item->lab)->name ?? 'General' }}</td>
                        <td class="numeric-cell">{{ $item->quantity_available }}</td>
                        <td class="numeric-cell">{{ $item->reorder_threshold }}</td>
                        <td class="numeric-cell">{{ $item->quantity_available - $item->reorder_threshold }}</td>
                        <td>{{ $item->updated_at->format('d M Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="table-pagination mt-3">
        {{ $data->appends($request->query())->links() }}
    </div>
@endif