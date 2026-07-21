<?php

namespace DavidOghi\NotificationsSystem\Resolvers;

use DavidOghi\NotificationsSystem\Contracts\MessageResolver;
use DavidOghi\NotificationsSystem\DTOs\NotificationRequest;

class DefaultMessageResolver implements MessageResolver
{
    public function resolve(NotificationRequest $request): array
    {
        $subject = $request->subject ?: config('david-oghi-notifications-system.payload.default_subject', 'Notification');
        $content = $request->content ?: config('david-oghi-notifications-system.payload.default_content', 'You have a new notification.');
        $preamble = $request->preamble ?: config('david-oghi-notifications-system.payload.default_preamble', 'You have a new notification.');

        $messages = [
            'email' => [
                'type' => $request->type,
                'subject' => $subject,
                'preamble' => $preamble,
                'content' => view('david-oghi-notifications-system::email.generic', [
                    'content' => $content,
                    'recipient_name' => $request->resolvedRecipientName(),
                    ...$request->meta,
                ])->render(),
                'meta' => $request->meta,
            ],
            'sms' => [
                'type' => $request->type,
                'content' => strip_tags($content),
                'meta' => $request->meta,
            ],
            'whatsapp' => [
                'type' => $request->type,
                'content' => strip_tags($content),
                'meta' => $request->meta,
            ],
            'push' => [
                'type' => $request->type,
                'title' => $subject,
                'body' => strip_tags($content),
                'meta' => $request->meta,
            ],
            'db' => [
                'type' => $request->type,
                'title' => $subject,
                'content' => strip_tags($content),
                'meta' => $request->meta,
            ],
        ];

        return collect($messages)->only($request->channels)->toArray();
    }
}
