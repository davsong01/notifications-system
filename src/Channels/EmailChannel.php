<?php

namespace DavidOghi\NotificationsSystem\Channels;

use DavidOghi\NotificationsSystem\Channels\Concerns\WritesNotificationLogs;
use DavidOghi\NotificationsSystem\Contracts\EmailProviderResolver;
use DavidOghi\NotificationsSystem\Contracts\NotificationChannel;
use DavidOghi\NotificationsSystem\DTOs\EmailProviderConfig;
use DavidOghi\NotificationsSystem\DTOs\NotificationPayload;
use DavidOghi\NotificationsSystem\DTOs\NotificationResult;
use DavidOghi\NotificationsSystem\Mail\NotificationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailChannel implements NotificationChannel
{
    use WritesNotificationLogs;

    public function __construct(
        protected EmailProviderResolver $providers
    ) {
    }

    public function send(NotificationPayload $payload): NotificationResult
    {
        $provider = $this->providers->find($payload->providerId);
        $log = $this->createLog($payload);

        if ($payload->priority !== (int) config('david-oghi-notifications-system.mail.send_immediately_priority', 1)) {
            return NotificationResult::logged('email', 'Email notification logged for later delivery.', [
                'log_id' => $log?->getKey(),
            ]);
        }

        if (! $payload->recipient) {
            $this->markLogFailed($log, 'No recipient email address provided.');

            return NotificationResult::failed('email', 'No recipient email address provided.');
        }

        if (! $provider) {
            Log::warning('No email provider resolved for notification.');
            $this->markLogFailed($log, 'No email provider resolved.');

            return NotificationResult::failed('email', 'No email provider resolved.');
        }

        try {
            $this->applyProvider($provider);

            Mail::purge();

            Mail::to($this->recipient($payload->recipient))
                ->send(new NotificationMail($payload, $provider));

            $this->markLogSent($log);

            return NotificationResult::sent('email', 'Email notification sent.', [
                'log_id' => $log?->getKey(),
            ]);
        } catch (\Throwable $e) {
            $this->markLogFailed($log, $e->getMessage());

            return NotificationResult::failed('email', $e->getMessage(), [
                'log_id' => $log?->getKey(),
            ]);
        }
    }

    protected function recipient(string $recipient): string
    {
        return config('david-oghi-notifications-system.mail.local_recipient_override') ?: $recipient;
    }

    protected function applyProvider(EmailProviderConfig $provider): void
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
}
