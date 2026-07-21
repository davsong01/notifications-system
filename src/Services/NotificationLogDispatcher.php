<?php

namespace DavidOghi\NotificationsSystem\Services;

use DavidOghi\NotificationsSystem\Contracts\EmailProviderResolver;
use DavidOghi\NotificationsSystem\DTOs\EmailProviderConfig;
use DavidOghi\NotificationsSystem\DTOs\NotificationPayload;
use DavidOghi\NotificationsSystem\Mail\NotificationMail;
use DavidOghi\NotificationsSystem\Models\NotificationLog;
use Illuminate\Support\Facades\Mail;

class NotificationLogDispatcher
{
    public function __construct(
        private EmailProviderResolver $providers
    ) {
    }

    public function process(?int $pick = null): array
    {
        $limit = $this->availableBatchSize($pick);

        if ($limit <= 0) {
            return [
                'picked' => 0,
                'sent' => 0,
                'failed' => 0,
                'message' => 'Hourly email notification limit reached.',
            ];
        }

        $logs = $this->pendingEmailLogs()
            ->limit($limit)
            ->get();

        $sent = 0;
        $failed = 0;

        foreach ($logs as $log) {
            if ($this->sendEmailLog($log)) {
                $sent++;
            } else {
                $failed++;
            }
        }

        return [
            'picked' => $logs->count(),
            'sent' => $sent,
            'failed' => $failed,
            'message' => "{$sent} email notification(s) sent successfully.",
        ];
    }

    private function pendingEmailLogs()
    {
        $model = config('david-oghi-notifications-system.models.notification_log', NotificationLog::class);

        return $model::query()
            ->where('channel', 'email')
            ->where('status', 0)
            ->whereNull('sent_at')
            ->whereNull('errors')
            ->oldest('id');
    }

    private function availableBatchSize(?int $pick): int
    {
        $batchSize = $pick ?: (int) config('david-oghi-notifications-system.cron.batch_size', 25);
        $hourlyRate = (int) config('david-oghi-notifications-system.cron.hourly_rate', 150);

        if ($hourlyRate <= 0) {
            return max(0, $batchSize);
        }

        $model = config('david-oghi-notifications-system.models.notification_log', NotificationLog::class);
        $sentThisHour = $model::query()
            ->where('channel', 'email')
            ->where('status', 1)
            ->where('sent_at', '>=', now()->subHour())
            ->count();

        return max(0, min($batchSize, $hourlyRate - $sentThisHour));
    }

    private function sendEmailLog(NotificationLog $log): bool
    {
        $provider = $this->providers->find($log->provider_id);

        if (! $log->recipient) {
            $this->markFailed($log, 'No recipient email address provided.');

            return false;
        }

        if (! $provider) {
            $this->markFailed($log, 'No email provider resolved.');

            return false;
        }

        try {
            $this->applyProvider($provider);

            Mail::purge();

            Mail::to($this->recipient($log->recipient))
                ->send(new NotificationMail($this->payloadFromLog($log), $provider));

            $log->forceFill([
                'status' => 1,
                'sent_at' => now(),
                'errors' => null,
            ])->save();

            return true;
        } catch (\Throwable $e) {
            $this->markFailed($log, $e->getMessage());

            return false;
        }
    }

    private function payloadFromLog(NotificationLog $log): NotificationPayload
    {
        return new NotificationPayload(
            channel: 'email',
            type: $log->type ?: config('david-oghi-notifications-system.payload.default_type', 'generic'),
            subject: $log->subject,
            preamble: $log->preamble,
            content: $log->content,
            attachments: $log->attachments ?: [],
            meta: $log->meta ?: [],
            priority: (int) $log->priority,
            recipient: $log->recipient,
            providerId: $log->provider_id,
        );
    }

    private function recipient(string $recipient): string
    {
        return config('david-oghi-notifications-system.mail.local_recipient_override') ?: $recipient;
    }

    private function applyProvider(EmailProviderConfig $provider): void
    {
        $mailerName = sprintf('david_oghi_notifications_%s', $provider->id ?? 'config');

        config([
            'mail.default' => $mailerName,
            "mail.mailers.{$mailerName}" => $provider->toMailerConfig(),
            'mail.from' => [
                'address' => $provider->fromAddress,
                'name' => $provider->fromName,
            ],
        ]);
    }

    private function markFailed(NotificationLog $log, string $error): void
    {
        $log->forceFill([
            'errors' => $error,
        ])->save();
    }
}
