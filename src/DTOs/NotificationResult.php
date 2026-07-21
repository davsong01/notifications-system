<?php

namespace DavidOghi\NotificationsSystem\DTOs;

final class NotificationResult
{
    public const STATUS_SENT = 'sent';
    public const STATUS_QUEUED = 'queued';
    public const STATUS_LOGGED = 'logged';
    public const STATUS_SKIPPED = 'skipped';
    public const STATUS_FAILED = 'failed';

    public function __construct(
        public readonly string $channel,
        public readonly string $status,
        public readonly ?string $message = null,
        public readonly array $context = [],
    ) {
    }

    public static function sent(string $channel, ?string $message = null, array $context = []): self
    {
        return new self($channel, self::STATUS_SENT, $message, $context);
    }

    public static function logged(string $channel, ?string $message = null, array $context = []): self
    {
        return new self($channel, self::STATUS_LOGGED, $message, $context);
    }

    public static function skipped(string $channel, ?string $message = null, array $context = []): self
    {
        return new self($channel, self::STATUS_SKIPPED, $message, $context);
    }

    public static function failed(string $channel, ?string $message = null, array $context = []): self
    {
        return new self($channel, self::STATUS_FAILED, $message, $context);
    }

    public function toArray(): array
    {
        return [
            'channel' => $this->channel,
            'status' => $this->status,
            'message' => $this->message,
            'context' => $this->context,
        ];
    }
}
