<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Ecommerce\Http\Controllers;

use Illuminate\Http\Request;
use Juzaweb\Core\Http\Controllers\ThemeController;
use Juzaweb\Modules\Ecommerce\Http\Requests\CheckoutRequest;
use Juzaweb\Modules\Ecommerce\Models\Cart;

class CheckoutController extends ThemeController
{
    public function index(Request $request, string $cartId)
    {
        if ($cartId != $request->cookie('cart_id')) {
            abort(404, __('Cart not found'));
        }

        $cart = Cart::where('id', $cartId)
            ->where('user_id', $request->user()->id)
            ->first();

        abort_if($cart === null, 404, __('Cart not found'));

        $cart->load(['items.variant.product' => fn ($q) => $q->withTranslation()]);
        $user = $request->user();

        return view(
            'ecommerce::checkout.index',
            compact('cart', 'user')
        );
    }

    public function checkout(CheckoutRequest $request)
    {
        $cartId = $request->cookie('cart_id');
        if ($cartId != $request->cookie('cart_id')) {
            abort(404, __('Cart not found'));
        }

        $cart = Cart::where('id', $cartId)
            ->where('user_id', $request->user()->id)
            ->first();


    }
}
