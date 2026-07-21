@extends($layout ?? 'david-oghi-notifications-system::layouts.app')

@section('content')
    <div class="panel">
        <div class="panel-body">
            <p><a href="{{ route(config('david-oghi-notifications-system.dashboard.route_name', 'david-oghi-notifications-system.') . 'logs.index') }}">Back to logs</a></p>

            <h2>{{ $log->subject ?: 'Notification' }}</h2>
            <p class="muted">{{ strtoupper($log->channel) }} to {{ $log->recipient ?: 'N/A' }}</p>

            <table>
                <tr><th>Type</th><td>{{ $log->type }}</td></tr>
                <tr><th>Status</th><td>{{ (int) $log->status === 1 ? 'Sent' : ($log->errors ? 'Failed' : 'Pending') }}</td></tr>
                <tr><th>Provider</th><td>{{ $log->provider?->name ?: 'N/A' }}</td></tr>
                <tr><th>Priority</th><td>{{ $log->priority }}</td></tr>
                <tr><th>Sent At</th><td>{{ $log->sent_at?->format('d M Y H:i') ?: 'N/A' }}</td></tr>
                <tr><th>Error</th><td>{{ $log->errors ?: 'N/A' }}</td></tr>
            </table>

            <h3>Content</h3>
            <div class="panel">
                <div class="panel-body">
                    {!! $log->content !!}
                </div>
            </div>
        </div>
    </div>
@endsection
