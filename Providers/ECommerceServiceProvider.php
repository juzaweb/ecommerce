<?php

namespace Juzaweb\Modules\Ecommerce\Providers;

use Juzaweb\Core\Providers\ServiceProvider;

class EcommerceServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('ecommerce.php'),
        ], 'config');
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'ecommerce');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'ecommerce');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../resources/lang', 'ecommerce');
    }

    /**
     * Register views.
     *
     * @return void
     */
    protected function registerViews(): void
    {
        $viewPath = resource_path('views/modules/ecommerce');

        $sourcePath = __DIR__ . '/../resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', 'ecommerce-module-views']);

        $this->loadViewsFrom($sourcePath, 'ecommerce');
    }
}
