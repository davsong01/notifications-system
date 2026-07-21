<?php

namespace DavidOghi\NotificationsSystem\Http\Controllers;

use DavidOghi\NotificationsSystem\Models\NotificationLog;
use DavidOghi\NotificationsSystem\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NotificationLogController extends Controller
{
    public function index(Request $request)
    {
        $model = config('david-oghi-notifications-system.models.notification_log', NotificationLog::class);

        $logs = $model::query()
            ->with('provider')
            ->when($request->filled('channel'), fn ($query) => $query->where('channel', $request->channel))
            ->when($request->filled('recipient'), fn ($query) => $query->where('recipient', 'like', '%' . $request->recipient . '%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->type))
            ->latest()
            ->paginate((int) config('david-oghi-notifications-system.dashboard.pagination', 25))
            ->withQueryString();

        return view('david-oghi-notifications-system::logs.index', [
            'logs' => $logs,
            'layout' => config('david-oghi-notifications-system.dashboard.layout'),
        ]);
    }

    public function show(int $id)
    {
        $model = config('david-oghi-notifications-system.models.notification_log', NotificationLog::class);
        $log = $model::query()->with('provider')->findOrFail($id);

        return view('david-oghi-notifications-system::logs.show', [
            'log' => $log,
            'layout' => config('david-oghi-notifications-system.dashboard.layout'),
        ]);
    }

    public function resend(int $id, NotificationService $notifications)
    {
        $model = config('david-oghi-notifications-system.models.notification_log', NotificationLog::class);
        $log = $model::query()->findOrFail($id);

        $result = $notifications->send(
            channels: [$log->channel],
            priority: (int) config('david-oghi-notifications-system.mail.send_immediately_priority', 1),
            type: $log->type,
            subject: $log->subject,
            content: $log->content,
            attachments: $log->attachments ?? [],
            meta: $log->meta ?? [],
            preamble: $log->preamble,
            recipient: $log->recipient,
            provider_id: $log->provider_id,
        );

        return back()->with(($result['success'] ?? false) ? 'success' : 'error', ($result['message'] ?? 'Notification resend processed.'));
    }

    public function destroy(int $id)
    {
        $model = config('david-oghi-notifications-system.models.notification_log', NotificationLog::class);
        $model::query()->findOrFail($id)->delete();

        return back()->with('success', 'Notification log deleted.');
    }
}
