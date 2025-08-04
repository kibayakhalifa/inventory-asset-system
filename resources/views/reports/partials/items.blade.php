@if($data->isEmpty())
    <div class="table-empty">
        <i class="fas fa-box-open fa-2x"></i>
        <h4>No Items Found</h4>
        <p>No items match the selected criteria</p>
    </div>
@else
    <div class="table-container">
        <table class="report-table striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Lab</th>
                    <th class="numeric-cell">Total</th>
                    <th class="numeric-cell">Available</th>
                    <th class="numeric-cell">Borrowed</th>
                    <th class="numeric-cell">In Use</th>
                    <th class="numeric-cell">Reorder Level</th>
                    <th>Condition</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->type ?? '—' }}</td>
                        <td>{{ $item->lab?->name ?? 'General' }}</td>
                        <td class="numeric-cell">{{ $item->quantity_total }}</td>
                        <td class="numeric-cell {{ $item->quantity_available < $item->reorder_threshold ? 'text-danger fw-bold' : '' }}">
                            {{ $item->quantity_available }}
                        </td>
                        <td class="numeric-cell">{{ $item->total_borrowed }}</td>
                        <td class="numeric-cell">{{ $item->in_use }}</td>
                        <td class="numeric-cell">{{ $item->reorder_threshold }}</td>
                        <td>
                            @php
                                $condition = $item->latestTransaction?->condition ?? 'Good';
                                $badgeClass = [
                                    'New' => 'badge-success',
                                    'Good' => 'badge-info',
                                    'Worn' => 'badge-warning',
                                    'Damaged' => 'badge-danger'
                                ][$condition] ?? 'badge-info';
                            @endphp
                            <span class="table-badge {{ $badgeClass }}">{{ $condition }}</span>
                        </td>
                        <td>{{ $item->updated_at?->format('d M Y, H:i') ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="table-pagination mt-3">
        {{ $data->appends($request->query())->links() }}
    </div>
@endif