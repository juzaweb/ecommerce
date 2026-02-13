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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Juzaweb\Modules\Admin\Models\User;
use Juzaweb\Modules\Payment\Contracts\ModuleHandlerInterface;
use Juzaweb\Modules\Payment\Contracts\Paymentable;
use Juzaweb\Modules\Payment\Enums\OrderPaymentStatus;
use Juzaweb\Modules\Payment\Exceptions\PaymentException;
use Juzaweb\Modules\Payment\Models\Cart;
use Juzaweb\Modules\Payment\Models\Order;
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

        $user = request()->user();
        $validator = Validator::make(
            $params,
            [
                'name' => [
                    Rule::requiredIf(! $user),
                    'string',
                    'max:150',
                ],
                'phone' => [
                    'nullable',
                    'string',
                    'max:50',
                ],
                'email' => [
                    Rule::requiredIf(! $user),
                    'string',
                    'email',
                    'max:150',
                ],
                'address' => [
                    'nullable',
                    'string',
                ],
                'country_code' => [
                    'nullable',
                    'string',
                    'max:15',
                ],
                'notes' => [
                    'nullable',
                    'string',
                ],
            ]
        );

        if ($validator->fails()) {
            throw new PaymentException($validator->errors()->first());
        }

        $cart = Cart::where('id', $cartId)->lockForUpdate()->first();
        $cart->load(['items.orderable.product' => fn ($q) => $q->withTranslation()]);

        if (! $user) {
            $user = User::firstOrCreate(
                [
                    'email' => $params['email'],
                ],
                [
                    'name' => $params['name'],
                ]
            );
        }

        $paymentMethod = PaymentMethod::where(['driver' => $params['method']])->first();

        throw_if($paymentMethod === null, new PaymentException('Invalid payment method'));

        $order = new Order();
        $order->fill(
            [
                'address' => $params['address'] ?? null,
                'quantity' => $cart->items->sum('quantity'),
                'total_price' => $cart->getTotalAmount(),
                'total' => $cart->getTotalAmount(),
                'payment_method_id' => $paymentMethod->id,
                'payment_method_name' => $paymentMethod->driver,
                'note' => $params['notes'] ?? null,
                'payment_status' => OrderPaymentStatus::PENDING,
            ]
        );

        $order->save();

        $order->creator()->associate($user);
        $order->saveQuietly();

        $order->items()->createMany(
            $cart->items->map(
                function ($item) {
                    return [
                        'title' => $item->orderable->product->name,
                        'price' => $item->orderable->price,
                        'line_price' => $item->orderable->price * $item->quantity,
                        'quantity' => $item->quantity,
                        'compare_price' => $item->orderable->compare_price,
                        'sku_code' => $item->orderable->sku_code,
                        'barcode' => $item->orderable->barcode,
                        'orderable_id' => $item->orderable_id,
                        'orderable_type' => $item->orderable_type,
                    ];
                }
            )->toArray()
        );

        session()->put('payment_order_id', $order->id);

        $cart->delete();
        cookie()->queue(cookie()->forget('cart_id'));

        return $order;
    }

    public function success(Paymentable $paymentable, array $params): void
    {
        DB::transaction(function () use ($paymentable, $params) {
            $paymentable->update([
                'payment_status' => OrderPaymentStatus::COMPLETED,
            ]);
        });
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
