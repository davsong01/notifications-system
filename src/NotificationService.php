<?php

namespace DavidOghi\NotificationsSystem;

use BackedEnum;
use DavidOghi\NotificationsSystem\Contracts\MessageResolver;
use DavidOghi\NotificationsSystem\Contracts\NotificationChannel;
use DavidOghi\NotificationsSystem\DTOs\NotificationPayload;
use DavidOghi\NotificationsSystem\DTOs\NotificationRequest;
use DavidOghi\NotificationsSystem\DTOs\NotificationResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Throwable;

class NotificationService
{
    public function __construct(
        protected MessageResolver $messageResolver
    ) {
    }

    public function send(
        ?Model $user = null,
        mixed $transaction = null,
        array $channels = [],
        ?int $priority = null,
        BackedEnum|string|null $type = null,
        ?string $subject = null,
        ?string $content = null,
        array $attachments = [],
        array $meta = [],
        ?string $preamble = null,
        ?string $recipient = null,
        ?string $recipient_name = null,
        ?int $provider_id = null,
    ): array {
        if (! config('david-oghi-notifications-system.enabled', true)) {
            return ['success' => true, 'message' => 'Notification system is disabled.', 'results' => []];
        }

        $requestedChannels = $channels ?: config('david-oghi-notifications-system.default_channels', ['db', 'email']);
        $typeValue = $type instanceof BackedEnum
            ? (string) $type->value
            : (string) ($type ?: config('david-oghi-notifications-system.payload.default_type', 'generic'));

        $request = new NotificationRequest(
            user: $user,
            transaction: $transaction,
            channels: $requestedChannels,
            priority: $priority ?? (int) config('david-oghi-notifications-system.mail.log_only_priority', 2),
            type: $typeValue,
            subject: $subject,
            content: $content,
            attachments: $attachments,
            meta: $meta,
            preamble: $preamble,
            recipient: $recipient,
            recipientName: $recipient_name,
            providerId: $provider_id,
        );

        $messages = $this->messageResolver->resolve($request);
        $results = [];

        foreach ($requestedChannels as $channelName) {
            if (! $this->channelEnabled($channelName) || ! isset($messages[$channelName])) {
                $results[$channelName] = NotificationResult::skipped($channelName, 'Channel disabled or no message resolved.')->toArray();
                continue;
            }

            try {
                $handler = $this->resolveChannel($channelName);
                $payload = NotificationPayload::fromMessage(
                    channel: $channelName,
                    request: $request,
                    message: $messages[$channelName],
                );

                $results[$channelName] = $handler->send($payload)->toArray();
            } catch (Throwable $e) {
                if (config('david-oghi-notifications-system.failures.report_exceptions', true)) {
                    report($e);
                }

                Log::error('Notification channel failed.', [
                    'channel' => $channelName,
                    'type' => $typeValue,
                    'recipient' => $recipient ?? $user?->getAttribute('email'),
                    'error' => $e->getMessage(),
                ]);

                $results[$channelName] = NotificationResult::failed($channelName, $e->getMessage())->toArray();

                if (config('david-oghi-notifications-system.failures.throw_exceptions', false)) {
                    throw $e;
                }

                if (! config('david-oghi-notifications-system.failures.continue_on_channel_failure', true)) {
                    break;
                }
            }
        }

        return [
            'success' => ! collect($results)->contains(fn (array $result) => $result['status'] === NotificationResult::STATUS_FAILED),
            'message' => 'Notification processed.',
            'results' => $results,
        ];
    }

    protected function channelEnabled(string $channel): bool
    {
        return (bool) config("david-oghi-notifications-system.channels.{$channel}.enabled", false);
    }

    protected function resolveChannel(string $channel): NotificationChannel
    {
        $driver = config("david-oghi-notifications-system.channels.{$channel}.driver");

        if (! $driver) {
            throw new \RuntimeException("No notification driver configured for [{$channel}].");
        }

        $handler = app($driver);

        if (! $handler instanceof NotificationChannel) {
            throw new \RuntimeException("Notification driver [{$driver}] must implement NotificationChannel.");
        }

        return $handler;
    }
}
