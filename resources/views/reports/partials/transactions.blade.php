@if($data->isEmpty())
    <div class="table-empty">
        <i class="fas fa-exchange-alt fa-2x"></i>
        <h4>No Transactions Found</h4>
        <p>No transactions match the selected criteria</p>
    </div>
@else
    <div class="table-container">
        <table class="report-table striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Student</th>
                    <th>Handled By</th>
                    <th>Department</th>
                    <th>Type</th>
                    <th class="numeric-cell">Quantity</th>
                    <th>Condition</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $transaction)
                    <tr>
                        <td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
                        <td>{{ $transaction->item->name ?? 'N/A' }}</td>
                        <td>{{ $transaction->student->name ?? 'N/A' }}</td>
                        <td>{{ $transaction->user->name ?? 'System' }}</td>
                        <td>{{ $transaction->lab?->name ?? 'General' }}</td>
                        <td>
                            <span class="table-badge {{ $transaction->action === 'issue' ? 'badge-issue' : 'badge-return' }}">
                                {{ ucfirst($transaction->action) }}
                            </span>
                        </td>
                        <td class="numeric-cell">{{ $transaction->quantity }}</td>
                        <td>
                            @if($transaction->condition)
                                <span class="table-badge condition-{{ strtolower($transaction->condition) }}">
                                    {{ ucfirst($transaction->condition) }}
                                </span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="table-pagination mt-3">
        {{ $data->appends($request->query())->links() }}
    </div>
@endif