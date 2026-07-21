@extends($layout ?? 'david-oghi-notifications-system::layouts.app')

@section('content')
    <div class="panel">
        <div class="panel-body">
            <p><a href="{{ route(config('david-oghi-notifications-system.dashboard.route_name', 'david-oghi-notifications-system.') . 'email-providers.index') }}">Back to providers</a></p>

            <h2>{{ $provider ? 'Edit Email Provider' : 'New Email Provider' }}</h2>

            @if($errors->any())
                <div class="alert alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ $provider ? route(config('david-oghi-notifications-system.dashboard.route_name', 'david-oghi-notifications-system.') . 'email-providers.update', $provider->id) : route(config('david-oghi-notifications-system.dashboard.route_name', 'david-oghi-notifications-system.') . 'email-providers.store') }}">
                @csrf
                @if($provider)
                    @method('PUT')
                @endif

                <div class="filters">
                    <div>
                        <label for="name">Name</label>
                        <input id="name" name="name" value="{{ old('name', $provider?->name) }}" required>
                    </div>
                    <div>
                        <label for="MAIL_MAILER">Mailer</label>
                        <input id="MAIL_MAILER" name="MAIL_MAILER" value="{{ old('MAIL_MAILER', $provider?->MAIL_MAILER ?: 'smtp') }}">
                    </div>
                    <div>
                        <label for="MAIL_HOST">Host</label>
                        <input id="MAIL_HOST" name="MAIL_HOST" value="{{ old('MAIL_HOST', $provider?->MAIL_HOST) }}" required>
                    </div>
                    <div>
                        <label for="MAIL_PORT">Port</label>
                        <input id="MAIL_PORT" name="MAIL_PORT" value="{{ old('MAIL_PORT', $provider?->MAIL_PORT ?: '587') }}" required>
                    </div>
                    <div>
                        <label for="MAIL_USERNAME">Username</label>
                        <input id="MAIL_USERNAME" name="MAIL_USERNAME" value="{{ old('MAIL_USERNAME', $provider?->MAIL_USERNAME) }}">
                    </div>
                    <div>
                        <label for="MAIL_PASSWORD">Password</label>
                        <input id="MAIL_PASSWORD" name="MAIL_PASSWORD" value="{{ old('MAIL_PASSWORD', $provider?->MAIL_PASSWORD) }}">
                    </div>
                    <div>
                        <label for="MAIL_ENCRYPTION">Encryption</label>
                        <input id="MAIL_ENCRYPTION" name="MAIL_ENCRYPTION" value="{{ old('MAIL_ENCRYPTION', $provider?->MAIL_ENCRYPTION) }}" placeholder="tls">
                    </div>
                    <div>
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="active" @selected(old('status', $provider?->status ?? 'active') === 'active')>Active</option>
                            <option value="inactive" @selected(old('status', $provider?->status) === 'inactive')>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label for="MAIL_FROM_ADDRESS">From Address</label>
                        <input id="MAIL_FROM_ADDRESS" name="MAIL_FROM_ADDRESS" value="{{ old('MAIL_FROM_ADDRESS', $provider?->MAIL_FROM_ADDRESS) }}" required>
                    </div>
                    <div>
                        <label for="MAIL_FROM_NAME">From Name</label>
                        <input id="MAIL_FROM_NAME" name="MAIL_FROM_NAME" value="{{ old('MAIL_FROM_NAME', $provider?->MAIL_FROM_NAME) }}" required>
                    </div>
                    <div>
                        <label for="MAIL_REPLY_TO_ADDRESS">Reply-To Address</label>
                        <input id="MAIL_REPLY_TO_ADDRESS" name="MAIL_REPLY_TO_ADDRESS" value="{{ old('MAIL_REPLY_TO_ADDRESS', $provider?->MAIL_REPLY_TO_ADDRESS) }}">
                    </div>
                    <div>
                        <label for="MAIL_REPLY_TO_NAME">Reply-To Name</label>
                        <input id="MAIL_REPLY_TO_NAME" name="MAIL_REPLY_TO_NAME" value="{{ old('MAIL_REPLY_TO_NAME', $provider?->MAIL_REPLY_TO_NAME) }}">
                    </div>
                </div>

                <button class="btn btn-primary" type="submit">Save Provider</button>
            </form>
        </div>
    </div>
@endsection
