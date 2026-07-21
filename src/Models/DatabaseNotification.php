<?php

namespace DavidOghi\NotificationsSystem\Models;

use Illuminate\Database\Eloquent\Model;

class DatabaseNotification extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function getTable(): string
    {
        return config('david-oghi-notifications-system.tables.notifications', 'notifications');
    }

    public function notifiable()
    {
        return $this->morphTo();
    }
}
