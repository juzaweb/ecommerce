<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/juzacms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Providers;

use Illuminate\Support\Facades\Route;
use Juzaweb\CMS\Facades\ActionRegister;
use Juzaweb\CMS\Support\ServiceProvider;
use Juzaweb\Ecommerce\Actions\EcommerceAction;
use Juzaweb\Ecommerce\Actions\MenuAction;
use Juzaweb\Ecommerce\Actions\ResourceAction;
use Juzaweb\Ecommerce\Contracts\CartContract;
use Juzaweb\Ecommerce\Contracts\CartManagerContract;
use Juzaweb\Ecommerce\Contracts\OrderCreaterContract;
use Juzaweb\Ecommerce\Contracts\OrderManagerContract;
use Juzaweb\Ecommerce\Http\Middleware\EcommerceTheme;
use Juzaweb\Ecommerce\Repositories\CartRepository;
use Juzaweb\Ecommerce\Repositories\CartRepositoryEloquent;
use Juzaweb\Ecommerce\Repositories\VariantRepository;
use Juzaweb\Ecommerce\Repositories\VariantRepositoryEloquent;
use Juzaweb\Ecommerce\Supports\Creaters\OrderCreater;
use Juzaweb\Ecommerce\Supports\Manager\CartManager;
use Juzaweb\Ecommerce\Supports\Manager\OrderManager;
use Juzaweb\Ecommerce\Supports\Payment;

class EcommerceServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CartRepository::class => CartRepositoryEloquent::class,
        VariantRepository::class => VariantRepositoryEloquent::class,
    ];

    public function boot()
    {
        Route::pushMiddlewareToGroup('theme', EcommerceTheme::class);

        ActionRegister::register([EcommerceAction::class, MenuAction::class, ResourceAction::class]);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/ecommerce.php',
            'ecommerce'
        );

        $this->app->register(RouteServiceProvider::class);

        $this->app->singleton(
            CartManagerContract::class,
            function () {
                return new CartManager();
            }
        );

        $this->app->bind(
            CartContract::class,
            config('ecommerce.cart')
        );

        $this->app->singleton(
            OrderCreaterContract::class,
            OrderCreater::class
        );

        $this->app->singleton(
            OrderManagerContract::class,
            function ($app) {
                return new OrderManager(
                    $app[OrderCreaterContract::class],
                    app(Payment::class)
                );
            }
        );
    }
}
