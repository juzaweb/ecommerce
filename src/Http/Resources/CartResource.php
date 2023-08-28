<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request): array
    {
        $cartItems = (new CartItemCollectionResource($this->resource->getCollectionItems()))->toArray($request);

        return [
            'code' => $this->resource->getCode(),
            'total_items' => $this->resource->totalItems(),
            'total_price' => ecom_price_with_unit($this->resource->totalPrice()),
            'total_price_without_unit' => $this->resource->totalPrice(),
            'items' => $cartItems,
        ];
    }
}
