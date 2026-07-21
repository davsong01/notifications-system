# David Oghi Notifications System

A configurable Laravel notification package for database, email, SMS, WhatsApp, push/log-only delivery, notification logs, and optional email provider administration.

## Install

```bash
composer require david-oghi/notifications-system
```

For local development from a parent app:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "packages/david-oghi/notifications-system",
            "options": {
                "symlink": true
            }
        }
    ]
}
```

Publish config, views, and migrations when the host app wants to customize them:

```bash
php artisan vendor:publish --tag=david-oghi-notifications-system-config
php artisan vendor:publish --tag=david-oghi-notifications-system-views
php artisan vendor:publish --tag=david-oghi-notifications-system-migrations
php artisan migrate
```

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
