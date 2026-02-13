<?php

namespace Juzaweb\Modules\Membership\Providers;

use Juzaweb\Modules\Core\Providers\ServiceProvider;

class MembershipServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        //

        $this->registerMenus();
    }

    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(__DIR__ . '/../../Database/migrations');
        $this->app->register(RouteServiceProvider::class);
    }

    protected function registerMenus(): void
    {
        //
    }

    protected function registerConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('membership.php'),
        ], 'membership-config');
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'membership');
    }

    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'membership');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../resources/lang');
    }

    protected function registerViews(): void
    {
        $viewPath = resource_path('views/modules/membership');

        $sourcePath = __DIR__ . '/../resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', 'membership-module-views']);

        $this->loadViewsFrom($sourcePath, 'membership');
    }
}
