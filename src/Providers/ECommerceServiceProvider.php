<?php

namespace Juzaweb\Modules\Ecommerce\Providers;

use Juzaweb\Core\Facades\Menu;
use Juzaweb\Core\Providers\ServiceProvider;
use Juzaweb\Modules\Ecommerce\Services\EcommercePaymentHandler;
use Juzaweb\Modules\Payment\Contracts\PaymentManager;

class ECommerceServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app[PaymentManager::class]->registerModule(
            'ecommerce',
            new EcommercePaymentHandler()
        );

        $this->booted(
            function () {
                $this->registerMenus();
            }
        );
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
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->app->register(RouteServiceProvider::class);
    }

    protected function registerMenus(): void
    {
        Menu::make('e-commerce', __('E-Commerce'))
            ->icon('fas fa-shopping-cart');
        Menu::make('products', __('Products'))
            ->parent('e-commerce');

        Menu::make('product-categories', __('Categories'))
            ->parent('e-commerce');
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/ecommerce.php' => config_path('ecommerce.php'),
        ], 'ecommerce-module-config');
        $this->mergeConfigFrom(__DIR__ . '/../../config/ecommerce.php', 'ecommerce');
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
