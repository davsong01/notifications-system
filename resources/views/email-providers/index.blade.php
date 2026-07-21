@extends($layout ?? 'david-oghi-notifications-system::layouts.app')

@section('content')
    <div class="panel">
        <div class="panel-body">
            <div class="topbar">
                <h2>Email Providers</h2>
                <a class="btn btn-primary" href="{{ route(config('david-oghi-notifications-system.dashboard.route_name', 'david-oghi-notifications-system.') . 'email-providers.create') }}">New Provider</a>
            </div>

            <form method="GET" class="filters">
                <div>
                    <label for="name">Name</label>
                    <input id="name" name="name" value="{{ request('name') }}">
                </div>
                <div>
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">All</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    </select>
                </div>
                <div>
                    <button class="btn btn-primary" type="submit">Filter</button>
                    <a class="btn" href="{{ route(config('david-oghi-notifications-system.dashboard.route_name', 'david-oghi-notifications-system.') . 'email-providers.index') }}">Reset</a>
                </div>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Host</th>
                        <th>From</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($providers as $provider)
                        <tr>
                            <td>{{ $provider->name }}</td>
                            <td>{{ $provider->MAIL_HOST }}:{{ $provider->MAIL_PORT }}</td>
                            <td>{{ $provider->MAIL_FROM_NAME }} &lt;{{ $provider->MAIL_FROM_ADDRESS }}&gt;</td>
                            <td>
                                <span class="badge {{ $provider->status === 'active' ? 'badge-ok' : '' }}">{{ ucfirst($provider->status) }}</span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a class="btn" href="{{ route(config('david-oghi-notifications-system.dashboard.route_name', 'david-oghi-notifications-system.') . 'email-providers.edit', $provider->id) }}">Edit</a>
                                    <form method="POST" action="{{ route(config('david-oghi-notifications-system.dashboard.route_name', 'david-oghi-notifications-system.') . 'email-providers.destroy', $provider->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="muted">No email providers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">{{ $providers->links() }}</div>
        </div>
    </div>
@endsection
