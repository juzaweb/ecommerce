<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/juzacms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Http\Controllers\Frontend;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Juzaweb\CMS\Http\Controllers\FrontendController;
use Juzaweb\Ecommerce\Contracts\CartContract;
use Juzaweb\Ecommerce\Contracts\CartManagerContract;
use Juzaweb\Ecommerce\Http\Requests\AddToCartRequest;
use Juzaweb\Ecommerce\Http\Requests\BulkUpdateCartRequest;
use Juzaweb\Ecommerce\Http\Requests\RemoveItemCartRequest;

class CartController extends FrontendController
{
    protected CartManagerContract $cartManager;

    public function __construct(CartManagerContract $cartManager)
    {
        $this->cartManager = $cartManager;
    }

    public function addToCart(AddToCartRequest $request): HttpResponse|JsonResponse|RedirectResponse
    {
        $variantId = $request->input('variant_id');
        $quantity = $request->input('quantity');

        $cart = $this->cartManager->find();

        DB::beginTransaction();
        try {
            $cart->addOrUpdate($variantId, $quantity);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return $this->error(
                [
                    'message' => $e->getMessage(),
                ]
            );
        }

        return $this->responseCartWithCookie(
            $cart,
            'Add to cart successfully.'
        );
    }

    public function removeItem(RemoveItemCartRequest $request)
    {
        $variantId = $request->input('variant_id');

        $cart = $this->cartManager->find();

        DB::beginTransaction();
        try {
            $cart->removeItem($variantId);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return $this->error(
                [
                    'message' => $e->getMessage(),
                ]
            );
        }

        return $this->responseCartWithCookie(
            $cart,
            'Remove item cart successfully.'
        );
    }

    public function bulkUpdate(
        BulkUpdateCartRequest $request,
        CartContract $cart
    ): HttpResponse|JsonResponse|RedirectResponse {
        $items = (array) $request->input('items');

        DB::beginTransaction();
        try {
            $cart = $cart->bulkUpdate($items);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return $this->error(
                [
                    'message' => $e->getMessage(),
                ]
            );
        }

        return $this->responseCartWithCookie(
            $cart,
            'Add to cart successfully.'
        );
    }

    public function remove()
    {
        $cart = $this->cartManager->find();

        DB::beginTransaction();
        try {
            $cart->remove();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return $this->error(
                [
                    'message' => $e->getMessage(),
                ]
            );
        }

        return $this->success(
            [
                'message' => __('Add to cart successfully.'),
                'cart' => $cart,
            ]
        );
    }

    public function getCartItems(): JsonResponse
    {
        $cart = $this->cartManager->find();

        $items = $cart->getCollectionItems()
            ->map(
                function ($item) {
                    return Arr::only(
                        $item->toArray(),
                        [
                            'sku_code',
                            'barcode',
                            'title',
                            'thumbnail',
                            'description',
                            'names',
                            'images',
                            'price',
                            'compare_price',
                            'stock',
                            'type',
                        ]
                    );
                }
            );

        return response()->json(
            [
                'code' => $cart->getCode(),
                'items' => $items
            ]
        );
    }

    protected function responseCartWithCookie(CartContract $cart, string $message): JsonResponse|RedirectResponse
    {
        $cookie = Cookie::make('jw_cart', $cart->getCode(), 43200);

        return $this->success(
            [
                'cart' => $cart->toArray(),
                'message' => __($message),
            ]
        )->withCookie($cookie);
    }
}
