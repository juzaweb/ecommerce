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
use Juzaweb\Modules\Ecommerce\Models\Cart;
use Juzaweb\Modules\Payment\Models\PaymentMethod;

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

        $paymentMethods = PaymentMethod::withTranslation()->whereActive()->get();

        return view(
            'ecommerce::checkout.index',
            compact('cart', 'user', 'paymentMethods')
        );
    }

    public function thankyou(Request $request, string $orderId)
    {
        return view('ecommerce::checkout.thankyou');
    }
}
