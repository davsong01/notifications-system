<?php

namespace DavidOghi\NotificationsSystem;

use DavidOghi\NotificationsSystem\Contracts\EmailProviderResolver;
use DavidOghi\NotificationsSystem\Contracts\MessageResolver;
use DavidOghi\NotificationsSystem\Resolvers\ConfigEmailProviderResolver;
use DavidOghi\NotificationsSystem\Resolvers\DefaultMessageResolver;
use DavidOghi\NotificationsSystem\Resolvers\PackageEmailProviderResolver;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class NotificationsSystemServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/david-oghi-notifications-system.php',
            'david-oghi-notifications-system'
        );

        $this->app->singleton(NotificationService::class);

        $this->app->bind(MessageResolver::class, function ($app) {
            $resolver = config('david-oghi-notifications-system.message_resolver') ?: DefaultMessageResolver::class;

            return $app->make($resolver);
        });

        $this->app->bind(EmailProviderResolver::class, function ($app) {
            $source = config('david-oghi-notifications-system.email_providers.source', 'package');

            $resolver = match ($source) {
                'app' => config('david-oghi-notifications-system.email_providers.app_resolver'),
                'config' => config('david-oghi-notifications-system.email_providers.config_resolver', ConfigEmailProviderResolver::class),
                default => config('david-oghi-notifications-system.email_providers.resolver', PackageEmailProviderResolver::class),
            };

            return $app->make($resolver ?: PackageEmailProviderResolver::class);
        });
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'david-oghi-notifications-system');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->registerRoutes();

        $this->publishes([
            __DIR__ . '/../config/david-oghi-notifications-system.php' => config_path('david-oghi-notifications-system.php'),
        ], 'david-oghi-notifications-system-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/david-oghi-notifications-system'),
        ], 'david-oghi-notifications-system-views');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'david-oghi-notifications-system-migrations');
    }

    protected function registerRoutes(): void
    {
        if (! config('david-oghi-notifications-system.dashboard.enabled', true)) {
            return;
        }

        Route::middleware(config('david-oghi-notifications-system.dashboard.middleware', ['web']))
            ->prefix(config('david-oghi-notifications-system.dashboard.route_prefix', 'notifications-system'))
            ->name(config('david-oghi-notifications-system.dashboard.route_name', 'david-oghi-notifications-system.'))
            ->group(__DIR__ . '/../routes/web.php');
    }
}
