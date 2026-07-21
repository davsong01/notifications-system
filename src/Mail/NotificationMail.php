<?php

namespace DavidOghi\NotificationsSystem\Mail;

use DavidOghi\NotificationsSystem\DTOs\EmailProviderConfig;
use DavidOghi\NotificationsSystem\DTOs\NotificationPayload;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly NotificationPayload $payload,
        public readonly EmailProviderConfig $provider,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->payload->subject ?: config('david-oghi-notifications-system.payload.default_subject', 'Notification'),
            from: new Address($this->provider->fromAddress, $this->provider->fromName),
            replyTo: $this->provider->replyToAddress
                ? [new Address($this->provider->replyToAddress, $this->provider->replyToName ?: $this->provider->fromName)]
                : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'david-oghi-notifications-system::email.layouts.master',
            with: [
                'subject' => $this->payload->subject ?: 'Notification',
                'preamble' => $this->payload->preamble ?: $this->payload->subject ?: 'Notification',
                'content' => $this->payload->content ?: '',
                'meta' => $this->payload->meta,
            ],
        );
    }

    public function attachments(): array
    {
        return collect($this->payload->attachments)
            ->map(function ($file) {
                if (is_array($file) && isset($file['path']) && file_exists($file['path'])) {
                    $attachment = Attachment::fromPath($file['path']);

                    if (! empty($file['options']['as'])) {
                        $attachment->as($file['options']['as']);
                    }

                    if (! empty($file['options']['mime'])) {
                        $attachment->withMime($file['options']['mime']);
                    }

                    return $attachment;
                }

                if (is_string($file) && file_exists($file)) {
                    return Attachment::fromPath($file);
                }

                return null;
            })
            ->filter()
            ->values()
            ->all();
    }
}
