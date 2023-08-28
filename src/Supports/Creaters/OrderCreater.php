<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Supports\Creaters;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Juzaweb\CMS\Models\User;
use Juzaweb\Ecommerce\Models\PaymentMethod;
use Juzaweb\Ecommerce\Models\ProductVariant;
use Juzaweb\Ecommerce\Models\Order;

class OrderCreater
{
    public function create(array $data, array $items, User $user): Order
    {
        $items = $this->collectionItems($items);

        if ($items->isEmpty()) {
            throw new \Exception('Product items is empty.');
        }

        $paymentMethod = $this->getPaymentMethod($data);

        $filldata = array_except(
            $data,
            [
                'code',
                'payment_status',
                'delivery_status',
                'user_id',
                'total_price',
                'total',
                'quantity',
            ]
        );

        $order = new Order();
        $order->fill($filldata);
        $order->code = $this->generateOrderCode();
        $order->token = $this->generateOrderToken();
        $order->user_id = $user->id;
        $order->total_price = $items->sum('line_price');
        $order->total = $order->total_price;
        $order->quantity = $items->sum('quantity');
        $order->name = $user->name;
        $order->phone = $user->phone;
        $order->email = $user->email;
        $order->payment_method_name = $paymentMethod->name;
        $order->save();

        foreach ($items as $item) {
            /**
             * @var ProductVariant $item
             */
            $order->orderItems()->create(
                [
                    'title' => $item->product->title,
                    'variant_title' => $item->title,
                    'thumbnail' => $item->getThumbnail(),
                    'quantity' => (int) $item->quantity,
                    'line_price' => $item->line_price,
                    'price' => $item->price,
                    'compare_price' => $item->compare_price,
                    'sku_code' => $item->sku_code,
                    'barcode' => $item->barcode,
                    'product_id' => $item->post_id,
                    'variant_id' => $item->id,
                ]
            );
        }

        return $order;
    }

    public function collectionItems(array $items): Collection
    {
        $variantIds = collect($items)
            ->pluck('variant_id')
            ->toArray();

        return ProductVariant::with(['product'])
            ->whereIn('id', $variantIds)
            ->get()
            ->map(
                function ($item) use ($items) {
                    $item->quantity = $items[$item->id]['quantity'];
                    $item->line_price = $item->price * $item->quantity;
                    return $item;
                }
            );
    }

    public function generateOrderCode(): string
    {
        $i=1;
        do {
            $code = date('YmdHis').$i;
            $i++;
        } while (Order::where('code', '=', $code)->exists());

        return $code;
    }

    public function generateOrderToken(): string
    {
        do {
            $token = Str::uuid()->toString();
        } while (Order::where('token', '=', $token)->exists());

        return $token;
    }

    protected function getPaymentMethod(array $data): PaymentMethod
    {
        $paymentMethod = PaymentMethod::find(
            Arr::get($data, 'payment_method_id')
        );

        if (empty($paymentMethod)) {
            throw new \Exception('Payment method does not exist');
        }

        return $paymentMethod;
    }
}
