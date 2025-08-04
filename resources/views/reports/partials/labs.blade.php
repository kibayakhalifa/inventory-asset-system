@if ($data->isEmpty())
    <div class="table-empty">
        <i class="fas fa-flask fa-2x"></i>
        <h4>No Labs Found</h4>
        <p>No labs match the selected criteria</p>
    </div>
@else
    <div class="table-container">
        <table class="report-table striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Lab Name</th>
                    <th>Description</th>
                    <th class="numeric-cell">Total Items</th>
                    <th>Created On</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $lab)
                    <tr>
                        <td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
                        <td>{{ $lab->name }}</td>
                        <td>{{ $lab->description ?? 'N/A' }}</td>
                        <td class="numeric-cell">
                            @if(isset($lab->items_count))
                                {{ $lab->items_count }}
                            @else
                                {{ $lab->items()->count() }}
                            @endif
                        </td>
                        <td>{{ $lab->created_at->format('d M Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="table-pagination mt-3">
        {{ $data->appends($request->query())->links() }}
    </div>
@endif