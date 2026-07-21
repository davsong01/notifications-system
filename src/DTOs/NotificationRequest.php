<?php

namespace DavidOghi\NotificationsSystem\DTOs;

use Illuminate\Database\Eloquent\Model;

final class NotificationRequest
{
    public function __construct(
        public readonly ?Model $user = null,
        public readonly mixed $transaction = null,
        public readonly array $channels = [],
        public readonly int $priority = 2,
        public readonly string $type = 'generic',
        public readonly ?string $subject = null,
        public readonly ?string $content = null,
        public readonly array $attachments = [],
        public readonly array $meta = [],
        public readonly ?string $preamble = null,
        public readonly ?string $recipient = null,
        public readonly ?string $recipientName = null,
        public readonly ?int $providerId = null,
    ) {
    }

    public function recipientEmail(): ?string
    {
        return $this->recipient ?: $this->user?->getAttribute('email');
    }

    public function recipientPhone(): ?string
    {
        return $this->recipient ?: $this->user?->getAttribute('phone');
    }

    public function resolvedRecipientName(): ?string
    {
        return $this->recipientName
            ?: $this->user?->getAttribute('name')
            ?: $this->user?->getAttribute('first_name');
    }
}
