<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/juzacms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use Juzaweb\Ecommerce\Contracts\CartManagerContract;
use Juzaweb\Ecommerce\Http\Resources\CartResource;

class EcommerceTheme
{
    public function handle($request, Closure $next)
    {
        $cart = app(CartManagerContract::class)->find();

        View::share(
            [
                'cart' => (new CartResource($cart))
                    ->toArray($request),
            ]
        );

        return $next($request);
    }
}
