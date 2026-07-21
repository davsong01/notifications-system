<?php

namespace DavidOghi\NotificationsSystem\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationLog extends Model
{
    protected $guarded = [];

    protected $casts = [
        'attachments' => 'array',
        'meta' => 'array',
        'sent_at' => 'datetime',
    ];

    public function getTable(): string
    {
        return config('david-oghi-notifications-system.tables.notification_logs', 'notification_logs');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(
            config('david-oghi-notifications-system.models.email_provider', EmailProvider::class),
            'provider_id'
        );
    }
}
