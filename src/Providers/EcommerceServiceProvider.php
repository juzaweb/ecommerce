<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Providers;

use Illuminate\Support\Facades\Route;
use Juzaweb\Backend\Models\Post;
use Juzaweb\CMS\Facades\ActionRegister;
use Juzaweb\CMS\Facades\MacroableModel;
use Juzaweb\CMS\Support\ServiceProvider;
use Juzaweb\Ecommerce\Actions\ConfigAction;
use Juzaweb\Ecommerce\Actions\EcommerceAction;
use Juzaweb\Ecommerce\Actions\MenuAction;
use Juzaweb\Ecommerce\Actions\ResourceAction;
use Juzaweb\Ecommerce\Contracts\CartContract;
use Juzaweb\Ecommerce\Contracts\CartManagerContract;
use Juzaweb\Ecommerce\Contracts\OrderCreaterContract;
use Juzaweb\Ecommerce\Contracts\OrderManagerContract;
use Juzaweb\Ecommerce\Http\Middleware\EcommerceTheme;
use Juzaweb\Ecommerce\Models\Order;
use Juzaweb\Ecommerce\Models\OrderItem;
use Juzaweb\Ecommerce\Models\ProductVariant;
use Juzaweb\Ecommerce\Observes\ProductObserve;
use Juzaweb\Ecommerce\Repositories\CartRepository;
use Juzaweb\Ecommerce\Repositories\CartRepositoryEloquent;
use Juzaweb\Ecommerce\Repositories\ProductRepository;
use Juzaweb\Ecommerce\Repositories\ProductRepositoryEloquent;
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
        ProductRepository::class => ProductRepositoryEloquent::class,
    ];

    public function boot(): void
    {
        Route::pushMiddlewareToGroup('theme', EcommerceTheme::class);

        ActionRegister::register([
            EcommerceAction::class,
            MenuAction::class,
            ResourceAction::class,
            ConfigAction::class,
        ]);

        MacroableModel::addMacro(
            Post::class,
            'orderItems',
            function () {
                /**
                 * @var Post $this
                 */
                return $this->hasMany(
                    OrderItem::class,
                    'product_id',
                    'id'
                );
            }
        );

        MacroableModel::addMacro(
            Post::class,
            'orders',
            function () {
                /**
                 * @var Post $this
                 */
                return $this->belongsToMany(
                    Order::class,
                    OrderItem::getTableName(),
                    'product_id',
                    'order_id'
                );
            }
        );

        MacroableModel::addMacro(
            Post::class,
            'variants',
            function () {
                /**
                 * @var Post $this
                 */
                return $this->hasMany(
                    ProductVariant::class,
                    'post_id',
                    'id'
                );
            }
        );
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/ecommerce.php',
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

        //Post::observe(ProductObserve::class);
    }
}
