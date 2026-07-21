<?php

namespace DavidOghi\NotificationsSystem\Contracts;

use DavidOghi\NotificationsSystem\DTOs\NotificationPayload;
use DavidOghi\NotificationsSystem\DTOs\NotificationResult;

interface NotificationChannel
{
    public function send(NotificationPayload $payload): NotificationResult;
}
