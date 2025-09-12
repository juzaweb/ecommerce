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
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Juzaweb\Core\Http\Controllers\ThemeController;
use Juzaweb\Modules\Ecommerce\Http\Requests\CartAddRequest;
use Juzaweb\Modules\Ecommerce\Models\Cart;

class CartController extends ThemeController
{
    public function add(CartAddRequest $request)
    {
        $cart = DB::transaction(
            function () use ($request) {
                if ($cartId = $request->cookie('cart_id')) {
                    $cart = Cart::where('id', $cartId)
                        ->where('user_id', auth()->id())
                        ->first();
                } else {
                    $cart = Cart::create([
                        'user_id' => auth()->id(),
                    ]);
                }

                $cart->items()->updateOrCreate(
                    [
                        'variant_id' => $request->input('variant_id'),
                    ],
                    $request->only(['quantity'])
                );

                return $cart;
            }
        );

        Cookie::queue('cart_id', $cart->id, 60 * 24 * 30); // 30 days

        return $this->success(
            [
                'message' => __('Product added to cart successfully'),
                'cart_id' => $cart->id,
            ]
        );
    }

    public function remove(Request $request, string $itemId)
    {
        $cartId = $request->cookie('cart_id');

        $cart = Cart::where('id', $cartId)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$cart) {
            return $this->error(__('Cart not found'));
        }

        $item = $cart->items()->where('id', $itemId)->first();

        if (!$item) {
            return $this->error(__('Item not found in cart'));
        }

        $item->delete();

        return $this->success(
            [
                'message' => __('Item removed from cart successfully'),
            ]
        );
    }
}
