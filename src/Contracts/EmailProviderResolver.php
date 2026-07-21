<?php

namespace DavidOghi\NotificationsSystem\Contracts;

use DavidOghi\NotificationsSystem\DTOs\EmailProviderConfig;

interface EmailProviderResolver
{
    public function find(?int $providerId = null): ?EmailProviderConfig;
}
