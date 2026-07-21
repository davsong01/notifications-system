<?php

use DavidOghi\NotificationsSystem\Channels\DatabaseChannel;
use DavidOghi\NotificationsSystem\Channels\EmailChannel;
use DavidOghi\NotificationsSystem\Channels\LogOnlyChannel;
use DavidOghi\NotificationsSystem\Channels\SmsChannel;
use DavidOghi\NotificationsSystem\Channels\WhatsAppChannel;
use DavidOghi\NotificationsSystem\Models\DatabaseNotification;
use DavidOghi\NotificationsSystem\Models\EmailProvider;
use DavidOghi\NotificationsSystem\Models\NotificationLog;
use DavidOghi\NotificationsSystem\Resolvers\ConfigEmailProviderResolver;
use DavidOghi\NotificationsSystem\Resolvers\DefaultMessageResolver;
use DavidOghi\NotificationsSystem\Resolvers\PackageEmailProviderResolver;

return [
    'enabled' => true,

    'default_channels' => ['db', 'email'],

    'message_resolver' => DefaultMessageResolver::class,

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

        'push' => [
            'enabled' => false,
            'driver' => LogOnlyChannel::class,
        ],
    ],

    'models' => [
        'notification_log' => NotificationLog::class,
        'database_notification' => DatabaseNotification::class,
        'email_provider' => EmailProvider::class,
    ],

    'tables' => [
        'notifications' => 'notifications',
        'notification_logs' => 'notification_logs',
        'email_providers' => 'email_providers',
    ],

    'migrations' => [
        'drop_tables_on_rollback' => false,
    ],

    'dashboard' => [
        'enabled' => true,
        'route_prefix' => 'notifications-system',
        'route_name' => 'david-oghi-notifications-system.',
        'middleware' => ['web'],
        'layout' => 'david-oghi-notifications-system::layouts.app',
        'pagination' => 25,
    ],

    'email_providers' => [
        'enabled' => true,
        'source' => 'package', // package, app, config
        'resolver' => PackageEmailProviderResolver::class,
        'config_resolver' => ConfigEmailProviderResolver::class,
        'app_resolver' => null,

        'admin' => [
            'enabled' => true,
            'middleware' => null,
        ],

        'selection' => [
            'strategy' => 'first_active',
        ],
    ],

    'mail' => [
        'from_address' => null,
        'from_name' => null,
        'reply_to_address' => null,
        'reply_to_name' => null,
        'fallback_mailer' => null,
        'local_recipient_override' => env('DAVID_OGHI_NOTIFICATIONS_LOCAL_RECIPIENT'),
        'send_immediately_priority' => 1,
        'log_only_priority' => 2,
    ],

    'branding' => [
        'site_name' => env('APP_NAME', 'Application'),
        'site_title' => null,
        'logo_url' => null,
        'primary_color' => '#0d6efd',
        'secondary_color' => '#0f172a',
        'support_email' => null,
        'support_phone' => null,
        'support_whatsapp' => null,
    ],

    'queue' => [
        'enabled' => false,
        'connection' => null,
        'queue' => 'notifications',
        'tries' => 3,
        'backoff' => [60, 300, 900],
    ],

    'cron' => [
        'enabled' => false,
        'route_prefix' => 'notifications-system',
        'route_name' => 'david-oghi-notifications-system.',
        'middleware' => ['web'],
        'token' => env('DAVID_OGHI_NOTIFICATIONS_CRON_TOKEN'),
        'batch_size' => 25,
        'hourly_rate' => 150,
        'time_limit' => 300,
    ],

    'logging' => [
        'enabled' => true,
        'store_content' => true,
        'store_attachments' => true,
        'prune_after_days' => 90,
        'mask_recipients' => false,
    ],

    'payload' => [
        'default_type' => 'generic',
        'default_subject' => 'Notification',
        'default_content' => 'You have a new notification.',
        'default_preamble' => 'You have a new notification.',
    ],

    'currency' => [
        'symbol' => '$',
    ],

    'failures' => [
        'throw_exceptions' => false,
        'report_exceptions' => true,
        'continue_on_channel_failure' => true,
    ],

    'security' => [
        'allowed_attachment_disks' => ['local'],
        'max_attachment_size_kb' => 10240,
    ],
];
