<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Supports;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Juzaweb\Ecommerce\Contracts\CartContract;
use Juzaweb\Ecommerce\Models\Cart;
use Juzaweb\Ecommerce\Models\ProductVariant;
use Illuminate\Support\Facades\Cookie;
use Juzaweb\Ecommerce\Repositories\CartRepository;

class DBCart implements CartContract
{
    protected CartRepository $cartRepository;

    protected Cart $cart;

    protected float $totalPrice;

    public function __construct(
        CartRepository $cartRepository
    ) {
        $this->cartRepository = $cartRepository;
    }

    public function make(string|Cart $cart): static
    {
        global $jw_user;

        if ($cart instanceof Cart) {
            $this->cart = $cart;
        } else {
            $this->cart = $this->cartRepository->firstOrNew(['code' => $cart]);
        }

        if ($jw_user) {
            $this->cart->user_id = $jw_user->id;
        }

        return $this;
    }

    public function add(int|ProductVariant $variant, int $quantity): bool
    {
        return $this->addOrUpdate($variant, $quantity);
    }

    public function update(int|ProductVariant $variant, int $quantity): bool
    {
        return $this->addOrUpdate($variant, $quantity);
    }

    public function addOrUpdate(int|ProductVariant $variant, int $quantity) : bool
    {
        $variant = is_numeric($variant) ? ProductVariant::find($variant) : $variant;

        if (empty($variant)) {
            throw new \Exception(
                __(
                    'Product Variant ID :variant not found.',
                    ['variant' => $variant]
                )
            );
        }

        $items = $this->cart->items;

        $items[$variant->id] = [
            'variant_id' => $variant->id,
            'quantity' => $quantity,
        ];

        $this->cart->items = $items;
        $this->cart->save();

        return true;
    }

    public function bulkUpdate(array $items) : bool
    {
        $variantIds = collect($items)->pluck('variant_id')->toArray();
        $variants = ProductVariant::whereIn('id', $variantIds)
            ->get()
            ->keyBy('id');

        $items = $this->cart->items;
        foreach ($items as $item) {
            $variant = $variants->get($item['variant_id']);
            if (empty($variant)) {
                continue;
            }

            $items[$variant->id] = Arr::only(
                $item,
                [
                    'variant_id',
                    'quantity',
                ]
            );
        }

        $this->cart->items = $items;
        $this->cart->save();
        return true;
    }

    public function removeItem(int $variantId) : bool
    {
        $items = $this->cart->items;
        unset($items[$variantId]);
        $this->cart->items = $items;
        $this->cart->save();
        return true;
    }

    public function remove(): bool
    {
        Cookie::queue(Cookie::forget('jw_cart'));
        $this->cart->delete();
        return true;
    }

    public function getItems() : array
    {
        return $this->cart->items ?? [];
    }

    public function isEmpty(): bool
    {
        return empty($this->getItems());
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    public function getCollectionItems(): Collection
    {
        $variantIds = collect($this->cart->items)
            ->pluck('variant_id')
            ->toArray();

        $variants = ProductVariant::with(['product'])
            ->whereIn('id', $variantIds)
            ->get()
            ->map(
                function ($item) {
                    $item->quantity = $this->cart->items[$item->id]['quantity'];
                    $item->line_price = $item->price * $item->quantity;
                    return $item;
                }
            );

        if (empty($variants)) {
            throw new \Exception('Product items is empty.');
        }

        $this->totalPrice = $variants->sum('line_price');

        return $variants;
    }

    public function getCode(): string
    {
        return $this->cart->code;
    }

    public function totalPrice(): float
    {
        if (isset($this->totalPrice)) {
            return $this->totalPrice;
        }

        $this->getCollectionItems();

        return $this->totalPrice;
    }

    public function totalItems() : int
    {
        if ($this->cart->items) {
            return count($this->cart->items);
        }

        return 0;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'items' => $this->getItems(),
        ];
    }
}
