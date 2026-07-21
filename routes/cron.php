<?php

use DavidOghi\NotificationsSystem\Http\Controllers\CronController;
use Illuminate\Support\Facades\Route;

Route::get('cron/{token?}', CronController::class)->name('cron');
