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
- EcomX business notification messages should live in the parent app through `MessageResolver`.

## Publish Tags

```bash
php artisan vendor:publish --tag=david-oghi-notifications-system-config
php artisan vendor:publish --tag=david-oghi-notifications-system-views
php artisan vendor:publish --tag=david-oghi-notifications-system-migrations
```

## Git Remote

```bash
git remote add origin https://github.com/davsong01/notifications-system.git
```
