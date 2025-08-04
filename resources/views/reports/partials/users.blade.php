@if($data->isEmpty())
    <div class="table-empty">
        <i class="fas fa-users fa-2x"></i>
        <h4>No Users Found</h4>
        <p>No users match the selected criteria</p>
    </div>
@else
    <div class="table-container">
        <table class="report-table striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Last Active</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $user)
                    <tr>
                        <td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->getRoleNames()->first() ?? 'User') }}</td>
                        <td>
                            <span class="table-badge {{ $user->status === 'active' ? 'badge-success' : 'badge-secondary' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>{{ $user->last_login_at?->format('d M Y H:i') ?? 'Never' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="table-pagination mt-3">
        {{ $data->appends($request->query())->links() }}
    </div>
@endif