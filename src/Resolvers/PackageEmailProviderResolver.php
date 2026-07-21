<?php

namespace DavidOghi\NotificationsSystem\Resolvers;

use DavidOghi\NotificationsSystem\Contracts\EmailProviderResolver;
use DavidOghi\NotificationsSystem\DTOs\EmailProviderConfig;
use DavidOghi\NotificationsSystem\Models\EmailProvider;

class PackageEmailProviderResolver implements EmailProviderResolver
{
    public function find(?int $providerId = null): ?EmailProviderConfig
    {
        $model = config('david-oghi-notifications-system.models.email_provider', EmailProvider::class);

        $provider = $model::query()
            ->when($providerId !== null, fn ($query) => $query->whereKey($providerId))
            ->where('status', 'active')
            ->first();

        if (! $provider) {
            return null;
        }

        return new EmailProviderConfig(
            id: $provider->getKey(),
            mailer: $provider->MAIL_MAILER ?: $provider->MAIL_DRIVER ?: 'smtp',
            host: $provider->MAIL_HOST,
            port: (int) ($provider->MAIL_PORT ?: 587),
            encryption: $provider->MAIL_ENCRYPTION ?: null,
            username: $provider->MAIL_USERNAME,
            password: $provider->MAIL_PASSWORD,
            fromAddress: $provider->MAIL_FROM_ADDRESS ?: config('mail.from.address'),
            fromName: $provider->MAIL_FROM_NAME ?: config('mail.from.name', 'System'),
            replyToAddress: $provider->MAIL_REPLY_TO_ADDRESS,
            replyToName: $provider->MAIL_REPLY_TO_NAME,
        );
    }
}
