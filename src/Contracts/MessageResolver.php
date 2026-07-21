<?php

namespace DavidOghi\NotificationsSystem\Contracts;

use DavidOghi\NotificationsSystem\DTOs\NotificationRequest;

interface MessageResolver
{
    public function resolve(NotificationRequest $request): array;
}
