# David Oghi Notifications System

A configurable Laravel notification package for database, email, SMS, WhatsApp, push/log-only delivery, notification logs, and optional email provider administration.

## Install

The package currently lives on GitHub, so client apps should register the repository before requiring it.

```json
{
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/davsong01/notifications-system.git"
        }
    ]
}
```

Then install from `main`:

```bash
composer require david-oghi/notifications-system:dev-main
```

Use `type: git` instead of `type: vcs` if Composer tries to clone the repository over SSH. The HTTPS git repository avoids SSH host-key prompts on fresh servers.

For local development from a parent app, a path repository can be used instead:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "packages/david-oghi/notifications-system",
            "options": {
                "symlink": true,
                "versions": {
                    "david-oghi/notifications-system": "dev-main"
                }
            }
        }
    ]
}
```

Then run:

```bash
composer require david-oghi/notifications-system:dev-main
```

Publish config, views, and migrations when the host app wants to customize them:

```bash
php artisan vendor:publish --tag=david-oghi-notifications-system-config
php artisan vendor:publish --tag=david-oghi-notifications-system-views
php artisan vendor:publish --tag=david-oghi-notifications-system-migrations
php artisan migrate
```

If the host app already has `notifications`, `notification_logs`, or `email_providers` tables, the package migration will avoid recreating them and will add only missing package columns where needed. When a payload includes `meta.school_id`, the package stores it on both `notifications.school_id` and `notification_logs.school_id` for tenant-aware filtering.

## Channels

Channels are configured in `config/david-oghi-notifications-system.php`.

```php
'default_channels' => ['db', 'email'],

'channels' => [
    'db' => [
        'enabled' => true,
        'driver' => DatabaseChannel::class,
    ],
    'email' => [
        'enabled' => true,
        'driver' => EmailChannel::class,
    ],
    'sms' => [
        'enabled' => false,
        'driver' => SmsChannel::class,
    ],
    'whatsapp' => [
        'enabled' => false,
        'driver' => WhatsAppChannel::class,
    ],
],
```

Client apps can disable bundled channels or replace a driver with their own class. Custom drivers must implement `DavidOghi\NotificationsSystem\Contracts\NotificationChannel`.

## Sending

```php
use DavidOghi\NotificationsSystem\NotificationService;

app(NotificationService::class)->send(
    user: $user,
    channels: ['db', 'email'],
    priority: 1,
    type: 'welcome',
    subject: 'Welcome',
    content: 'Your account is ready.'
);
```

Priority `1` sends email immediately. Priority `2` logs email for later delivery.

## cPanel Cron Delivery

Apps without Supervisor can process pending email notification logs through an HTTP cron route. Enable it in config:

```php
'cron' => [
    'enabled' => true,
    'route_prefix' => 'notifications-system',
    'token' => env('DAVID_OGHI_NOTIFICATIONS_CRON_TOKEN'),
    'batch_size' => 25,
    'hourly_rate' => 150,
],
```

Then point cPanel cron at:

```text
https://your-domain.test/notifications-system/cron/YOUR_TOKEN
```

The token is required; if it is not configured, the route returns `403`. The route sends pending `notification_logs` where `channel = email`, `status = 0`, `sent_at` is empty, and `errors` is empty. Failed rows keep `status = 0` and store the error so an admin can inspect or resend them. Keep `logging.mask_recipients` disabled when using cron delivery, because the stored recipient must remain sendable.

## Email Providers

The package can own email provider records, use Laravel mail config, or adapt an existing client app provider system.

```php
'email_providers' => [
    'source' => 'package',
    'admin' => [
        'enabled' => true,
    ],
],
```

Use an existing app provider system:

```php
'email_providers' => [
    'source' => 'app',
    'app_resolver' => App\Notifications\AppEmailProviderResolver::class,
    'admin' => [
        'enabled' => false,
    ],
],
```

The app resolver must implement `DavidOghi\NotificationsSystem\Contracts\EmailProviderResolver`.

Use Laravel config only:

```php
'email_providers' => [
    'source' => 'config',
    'admin' => [
        'enabled' => false,
    ],
],
```

## Admin Routes

Admin views are bundled and intentionally auth-neutral. The host app gates them through middleware config.

```php
'dashboard' => [
    'enabled' => true,
    'route_prefix' => 'notifications-system',
    'route_name' => 'david-oghi-notifications-system.',
    'middleware' => ['web', 'auth', 'can:manage-notifications'],
],
```

Default route examples:

- `/notifications-system/logs`
- `/notifications-system/email-providers`

## Message Resolver

The package ships with a generic resolver. Apps with business-specific notification templates should implement `MessageResolver`.

```php
'message_resolver' => App\Notifications\EcomxNotificationMessageResolver::class,
```

That keeps business events like escrow releases, subscription purchases, and payout approvals inside the host app.
