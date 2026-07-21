<?php

namespace DavidOghi\NotificationsSystem\Models;

use Illuminate\Database\Eloquent\Model;

class EmailProvider extends Model
{
    protected $guarded = [];

    public function getTable(): string
    {
        return config('david-oghi-notifications-system.tables.email_providers', 'email_providers');
    }
}
