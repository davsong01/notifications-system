<?php

namespace DavidOghi\NotificationsSystem\Channels\Concerns;

use DavidOghi\NotificationsSystem\DTOs\NotificationPayload;
use DavidOghi\NotificationsSystem\Models\NotificationLog;

trait WritesNotificationLogs
{
    protected function createLog(NotificationPayload $payload, int $status = 0, ?string $error = null): ?NotificationLog
    {
        if (! config('david-oghi-notifications-system.logging.enabled', true)) {
            return null;
        }

        $model = config('david-oghi-notifications-system.models.notification_log', NotificationLog::class);

        return $model::query()->create([
            'school_id' => $payload->meta['school_id'] ?? null,
            'sender' => config('mail.from.name') ?: 'System',
            'provider_id' => $payload->providerId,
            'priority' => $payload->priority,
            'preamble' => $payload->preamble,
            'channel' => $payload->channel,
            'recipient' => $this->maskRecipient($payload->recipient),
            'type' => $payload->type,
            'subject' => $payload->subject,
            'content' => config('david-oghi-notifications-system.logging.store_content', true) ? $payload->content : null,
            'attachments' => config('david-oghi-notifications-system.logging.store_attachments', true) ? $payload->attachments : [],
            'meta' => $payload->meta,
            'status' => $status,
            'sent_at' => $status === 1 ? now() : null,
            'errors' => $error,
        ]);
    }

    protected function markLogSent(?NotificationLog $log): void
    {
        $log?->forceFill([
            'status' => 1,
            'sent_at' => now(),
            'errors' => null,
        ])->save();
    }

    protected function markLogFailed(?NotificationLog $log, string $error): void
    {
        $log?->forceFill([
            'errors' => $error,
        ])->save();
    }

    protected function maskRecipient(?string $recipient): ?string
    {
        if (! $recipient || ! config('david-oghi-notifications-system.logging.mask_recipients', false)) {
            return $recipient;
        }

        if (str_contains($recipient, '@')) {
            [$name, $domain] = explode('@', $recipient, 2);

            return substr($name, 0, 2) . '***@' . $domain;
        }

        return substr($recipient, 0, 4) . '***' . substr($recipient, -2);
    }
}
