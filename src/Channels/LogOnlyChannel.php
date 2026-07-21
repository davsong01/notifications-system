<?php

namespace DavidOghi\NotificationsSystem\Channels;

use DavidOghi\NotificationsSystem\Channels\Concerns\WritesNotificationLogs;
use DavidOghi\NotificationsSystem\Contracts\NotificationChannel;
use DavidOghi\NotificationsSystem\DTOs\NotificationPayload;
use DavidOghi\NotificationsSystem\DTOs\NotificationResult;

class LogOnlyChannel implements NotificationChannel
{
    use WritesNotificationLogs;

    public function send(NotificationPayload $payload): NotificationResult
    {
        $log = $this->createLog($payload);

        return NotificationResult::logged($payload->channel, ucfirst($payload->channel) . ' notification logged.', [
            'log_id' => $log?->getKey(),
        ]);
    }
}
