<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Notifications System' }}</title>
    <style>
        body { margin: 0; background: #f6f8fb; color: #1f2937; font-family: Arial, sans-serif; }
        a { color: #2563eb; text-decoration: none; }
        .page { max-width: 1180px; margin: 0 auto; padding: 28px 18px 48px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 22px; }
        .brand { font-size: 20px; font-weight: 700; }
        .nav { display: flex; gap: 10px; flex-wrap: wrap; }
        .nav a, .btn { display: inline-block; border: 1px solid #d1d5db; border-radius: 6px; padding: 8px 12px; background: #fff; color: #111827; font-size: 14px; }
        .btn-primary { background: #2563eb; color: #fff; border-color: #2563eb; }
        .btn-danger { background: #dc2626; color: #fff; border-color: #dc2626; }
        .panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; }
        .panel-body { padding: 18px; }
        .filters { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; margin-bottom: 18px; }
        label { display: block; font-size: 13px; color: #4b5563; margin-bottom: 6px; }
        input, select, textarea { width: 100%; box-sizing: border-box; border: 1px solid #d1d5db; border-radius: 6px; padding: 9px 10px; font: inherit; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 12px; text-align: left; vertical-align: top; font-size: 14px; }
        th { background: #f9fafb; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: .04em; }
        .badge { display: inline-block; border-radius: 999px; padding: 3px 8px; font-size: 12px; background: #e5e7eb; }
        .badge-ok { background: #dcfce7; color: #166534; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-error { background: #fee2e2; color: #991b1b; }
        .alert { border-radius: 6px; padding: 10px 12px; margin-bottom: 14px; background: #eff6ff; color: #1d4ed8; }
        .alert-error { background: #fee2e2; color: #991b1b; }
        .actions { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
        .actions form { margin: 0; }
        .muted { color: #6b7280; }
        .content-preview { max-width: 360px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .pagination { margin-top: 16px; }
        @media (max-width: 780px) {
            .topbar { display: block; }
            .nav { margin-top: 12px; }
            .filters { grid-template-columns: 1fr; }
            table { display: block; overflow-x: auto; white-space: nowrap; }
        }
    </style>
</head>
<body>
    <main class="page">
        <div class="topbar">
            <div class="brand">Notifications System</div>
            <nav class="nav">
                <a href="{{ route(config('david-oghi-notifications-system.dashboard.route_name', 'david-oghi-notifications-system.') . 'logs.index') }}">Logs</a>
                @if(config('david-oghi-notifications-system.email_providers.enabled', true) && config('david-oghi-notifications-system.email_providers.source', 'package') === 'package' && config('david-oghi-notifications-system.email_providers.admin.enabled', true))
                    <a href="{{ route(config('david-oghi-notifications-system.dashboard.route_name', 'david-oghi-notifications-system.') . 'email-providers.index') }}">Email Providers</a>
                @endif
            </nav>
        </div>

        @if(session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>
