<?php

use DavidOghi\NotificationsSystem\Http\Controllers\EmailProviderController;
use DavidOghi\NotificationsSystem\Http\Controllers\NotificationLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route(config('david-oghi-notifications-system.dashboard.route_name', 'david-oghi-notifications-system.') . 'logs.index'))
    ->name('dashboard');

Route::get('logs', [NotificationLogController::class, 'index'])->name('logs.index');
Route::get('logs/{id}', [NotificationLogController::class, 'show'])->name('logs.show');
Route::post('logs/{id}/resend', [NotificationLogController::class, 'resend'])->name('logs.resend');
Route::delete('logs/{id}', [NotificationLogController::class, 'destroy'])->name('logs.destroy');

if (
    config('david-oghi-notifications-system.email_providers.enabled', true) &&
    config('david-oghi-notifications-system.email_providers.source', 'package') === 'package' &&
    config('david-oghi-notifications-system.email_providers.admin.enabled', true)
) {
    $middleware = config('david-oghi-notifications-system.email_providers.admin.middleware');

    Route::middleware($middleware ?: [])
        ->group(function (): void {
            Route::resource('email-providers', EmailProviderController::class)->except(['show']);
        });
}
