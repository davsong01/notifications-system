@extends($layout ?? 'david-oghi-notifications-system::layouts.app')

@section('content')
    <div class="panel">
        <div class="panel-body">
            <form method="GET" class="filters">
                <div>
                    <label for="channel">Channel</label>
                    <select name="channel" id="channel">
                        <option value="">All</option>
                        @foreach(['email', 'db', 'sms', 'whatsapp', 'push'] as $channel)
                            <option value="{{ $channel }}" @selected(request('channel') === $channel)>{{ strtoupper($channel) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="recipient">Recipient</label>
                    <input id="recipient" name="recipient" value="{{ request('recipient') }}">
                </div>
                <div>
                    <label for="type">Type</label>
                    <input id="type" name="type" value="{{ request('type') }}">
                </div>
                <div>
                    <label for="status">Status</label>
                    <select name="status" id="status">
                        <option value="">All</option>
                        <option value="0" @selected(request('status') === '0')>Pending</option>
                        <option value="1" @selected(request('status') === '1')>Sent</option>
                    </select>
                </div>
                <div>
                    <button class="btn btn-primary" type="submit">Filter</button>
                    <a class="btn" href="{{ route(config('david-oghi-notifications-system.dashboard.route_name', 'david-oghi-notifications-system.') . 'logs.index') }}">Reset</a>
                </div>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Channel</th>
                        <th>Recipient</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ strtoupper($log->channel) }}</td>
                            <td>{{ $log->recipient ?: 'N/A' }}</td>
                            <td>
                                <strong>{{ $log->subject ?: 'Notification' }}</strong>
                                <div class="muted content-preview">{{ strip_tags($log->content ?? '') }}</div>
                            </td>
                            <td>
                                @if((int) $log->status === 1)
                                    <span class="badge badge-ok">Sent</span>
                                @elseif($log->errors)
                                    <span class="badge badge-error">Failed</span>
                                @else
                                    <span class="badge badge-pending">Pending</span>
                                @endif
                            </td>
                            <td>{{ $log->created_at?->format('d M Y H:i') }}</td>
                            <td>
                                <div class="actions">
                                    <a class="btn" href="{{ route(config('david-oghi-notifications-system.dashboard.route_name', 'david-oghi-notifications-system.') . 'logs.show', $log->id) }}">View</a>
                                    <form method="POST" action="{{ route(config('david-oghi-notifications-system.dashboard.route_name', 'david-oghi-notifications-system.') . 'logs.resend', $log->id) }}">
                                        @csrf
                                        <button class="btn" type="submit">Resend</button>
                                    </form>
                                    <form method="POST" action="{{ route(config('david-oghi-notifications-system.dashboard.route_name', 'david-oghi-notifications-system.') . 'logs.destroy', $log->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="muted">No notification logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">{{ $logs->links() }}</div>
        </div>
    </div>
@endsection
