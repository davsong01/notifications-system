<?php

namespace DavidOghi\NotificationsSystem\Channels;

use DavidOghi\NotificationsSystem\Contracts\NotificationChannel;
use DavidOghi\NotificationsSystem\DTOs\NotificationPayload;
use DavidOghi\NotificationsSystem\DTOs\NotificationResult;
use DavidOghi\NotificationsSystem\Models\DatabaseNotification;
use Illuminate\Support\Str;

class DatabaseChannel implements NotificationChannel
{
    public function send(NotificationPayload $payload): NotificationResult
    {
        if (! $payload->notifiable) {
            return NotificationResult::skipped('db', 'No notifiable model provided.');
        }

        $model = config('david-oghi-notifications-system.models.database_notification', DatabaseNotification::class);

        $notification = $model::query()->create([
            'id' => (string) Str::uuid(),
            'school_id' => $payload->meta['school_id'] ?? null,
            'type' => $payload->type,
            'notifiable_type' => $payload->notifiable::class,
            'notifiable_id' => $payload->notifiable->getKey(),
            'priority' => $payload->priority,
            'data' => [
                'subject' => $payload->subject,
                'title' => $payload->subject,
                'content' => $payload->content,
                'meta' => $payload->meta,
            ],
        ]);

        return NotificationResult::sent('db', 'Database notification stored.', [
            'notification_id' => $notification->getKey(),
        ]);
    }
}
