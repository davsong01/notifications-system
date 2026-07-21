<?php

namespace DavidOghi\NotificationsSystem\Resolvers;

use DavidOghi\NotificationsSystem\Contracts\EmailProviderResolver;
use DavidOghi\NotificationsSystem\DTOs\EmailProviderConfig;

class ConfigEmailProviderResolver implements EmailProviderResolver
{
    public function find(?int $providerId = null): ?EmailProviderConfig
    {
        $mailer = config('david-oghi-notifications-system.mail.fallback_mailer') ?: config('mail.default', 'smtp');
        $mailConfig = config("mail.mailers.{$mailer}", []);

        return new EmailProviderConfig(
            id: null,
            mailer: $mailConfig['transport'] ?? $mailer,
            host: $mailConfig['host'] ?? 'localhost',
            port: (int) ($mailConfig['port'] ?? 587),
            encryption: $mailConfig['encryption'] ?? null,
            username: $mailConfig['username'] ?? null,
            password: $mailConfig['password'] ?? null,
            fromAddress: config('david-oghi-notifications-system.mail.from_address') ?: config('mail.from.address'),
            fromName: config('david-oghi-notifications-system.mail.from_name') ?: config('mail.from.name', 'System'),
            replyToAddress: config('david-oghi-notifications-system.mail.reply_to_address'),
            replyToName: config('david-oghi-notifications-system.mail.reply_to_name'),
        );
    }
}
