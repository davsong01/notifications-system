# Package Notes

## Parent App

The parent app for extraction is EcomX.

## Package Name

- Composer: `david-oghi/notifications-system`
- Config key: `david-oghi-notifications-system`
- View namespace: `david-oghi-notifications-system`
- Default route name prefix: `david-oghi-notifications-system.`

## Design Decisions

- Channels are bundled but config-gated.
- Client apps can disable any channel without changing package code.
- Client apps can replace any channel driver with their own implementation.
- Admin views are bundled but auth-neutral.
- Client apps decide route gating through dashboard middleware.
- Email provider admin is optional.
- Existing apps with their own provider table should use an `EmailProviderResolver`.
- Pending email logs can be processed by the bundled cPanel-friendly cron route.
- EcomX business notification messages should live in the parent app through `MessageResolver`.

## Publish Tags

```bash
php artisan vendor:publish --tag=david-oghi-notifications-system-config
php artisan vendor:publish --tag=david-oghi-notifications-system-views
php artisan vendor:publish --tag=david-oghi-notifications-system-migrations
```

## GitHub Install

The package is installed from GitHub until it is published to Packagist.

Add this to the client app `composer.json`:

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

Then run:

```bash
composer require david-oghi/notifications-system:dev-main
```

Use `type: git` so Composer clones the HTTPS URL directly. With `type: vcs`, Composer may resolve GitHub metadata and try to clone the repo through SSH, which can fail on new servers with host-key verification errors.

## Local Path Install

For parent-app development before or alongside GitHub install:

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

## Git Remote

```bash
git remote add origin https://github.com/davsong01/notifications-system.git
```

## EcomX/Oakland Install Notes

- EcomX keeps its business notification enums in the parent app.
- EcomX should configure `message_resolver` to an app resolver, for example `App\Notifications\EcomxNotificationMessageResolver`.
- Existing notification call sites can keep using an app compatibility wrapper if desired.
- The package admin routes are auth-neutral; gate them with `dashboard.middleware`.
- The bundled email provider admin can be disabled when the host app already owns provider management.
- Apps without Supervisor should enable `cron.enabled` and call `/notifications-system/cron/{token}` every minute from cPanel.
- For cron email delivery, send email notifications with `mail.log_only_priority` and keep `logging.mask_recipients` disabled.
