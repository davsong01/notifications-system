<?php

namespace DavidOghi\NotificationsSystem\DTOs;

final class NotificationPayload
{
    public function __construct(
        public readonly string $channel,
        public readonly string $type,
        public readonly ?string $subject = null,
        public readonly ?string $preamble = null,
        public readonly ?string $content = null,
        public readonly array $attachments = [],
        public readonly array $meta = [],
        public readonly int $priority = 2,
        public readonly ?string $recipient = null,
        public readonly ?string $recipientName = null,
        public readonly ?int $providerId = null,
        public readonly mixed $notifiable = null,
    ) {
    }

    public static function fromMessage(string $channel, NotificationRequest $request, array $message): self
    {
        return new self(
            channel: $channel,
            type: (string) ($message['type'] ?? $request->type),
            subject: $message['subject'] ?? $message['title'] ?? $request->subject,
            preamble: $message['preamble'] ?? $request->preamble ?? $request->subject,
            content: $message['content'] ?? $message['body'] ?? $request->content,
            attachments: $message['attachments'] ?? $request->attachments,
            meta: array_merge($request->meta, $message['meta'] ?? []),
            priority: $request->priority,
            recipient: $message['recipient'] ?? $request->recipientEmail(),
            recipientName: $message['recipient_name'] ?? $request->resolvedRecipientName(),
            providerId: $message['provider_id'] ?? $request->providerId,
            notifiable: $request->user,
        );
    }
}
