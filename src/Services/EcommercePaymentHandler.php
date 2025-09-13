<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Ecommerce\Services;

use Illuminate\Support\Facades\DB;
use Juzaweb\Core\Models\User;
use Juzaweb\Modules\Ecommerce\Models\Cart;
use Juzaweb\Modules\Ecommerce\Models\Order;
use Juzaweb\Modules\Payment\Contracts\ModuleHandlerInterface;
use Juzaweb\Modules\Payment\Contracts\Paymentable;
use Juzaweb\Modules\Payment\Exceptions\PaymentException;
use Juzaweb\Modules\Payment\Models\PaymentMethod;

class EcommercePaymentHandler implements ModuleHandlerInterface
{
    /**
     * Process a purchase request.
     *
     * @param array $params
     * @return Paymentable
     */
    public function createOrder(array $params): Paymentable
    {
        if (! $cartId = request()->cookie('cart_id')) {
            throw new PaymentException(__('Cart not found'));
        }

        $order = DB::transaction(
            function () use ($params, $cartId) {
                $cart = Cart::where('id', $cartId)->lockForUpdate()->first();
                $cart->load(['items.variant.product' => fn ($q) => $q->withTranslation()]);

                $user = request()->user();

                if (! $user) {
                    $user = User::create(
                        [
                            'name' => $params['name'],
                            'email' => $params['email'],
                        ]
                    );
                }

                $paymentMethod = PaymentMethod::find($params['payment_method_id']);

                throw_if($paymentMethod === null, new PaymentException('Invalid payment method'));

                $order = Order::create(
                    [
                        'name' => $user->name,
                        'phone' => $params['phone'] ?? null,
                        'email' => $user->email,
                        'address' => $params['address'] ?? null,
                        'quantity' => $cart->items->sum('quantity'),
                        'total_price' => $cart->getTotalAmount(),
                        'total' => $cart->getTotalAmount(),
                        'payment_method_id' => $paymentMethod->id,
                        'payment_method_name' => $paymentMethod->driver,
                        'notes' => $params['notes'] ?? null,
                        'user_id' => $user->id,
                    ]
                );

                $order->items()->createMany(
                    $cart->items->map(
                        function ($item) {
                            return [
                                'title' => $item->variant->product->name,
                                'price' => $item->variant->price,
                                'line_price' => $item->variant->price * $item->quantity,
                                'quantity' => $item->quantity,
                                'compare_price' => $item->variant->compare_price,
                                'sku_code' => $item->variant->sku_code,
                                'barcode' => $item->variant->barcode,
                                'product_id' => $item->variant->product_id,
                                'variant_id' => $item->variant_id,
                            ];
                        }
                    )->toArray()
                );

                session()->put('payment_order_id', $order->id);

                return $order;
            }
        );

        return $order;
    }

    public function success(Paymentable $paymentable, array $params): void
    {
        // Implement the logic to handle a successful payment

    }

    /**
     * Handle a failed payment.
     *
     * @param  Paymentable  $paymentable
     * @param  array  $params
     * @return void
     */
    public function fail(Paymentable $paymentable, array $params): void
    {
        // Implement the logic to handle a failed payment
        // This might involve logging the failure, notifying the user, etc.

        // Example: Log the failure
        \Log::error('Payment failed', ['params' => $params]);
    }

    public function cancel(Paymentable $paymentable, array $params): void
    {
        // TODO: Implement cancel() method.
    }

    public function getReturnUrl(): string
    {
        if ($orderId = session()->pull('payment_order_id')) {

            return route('ecommerce.checkout.thank', [$orderId]);
        }

        return route('home');
    }
}
