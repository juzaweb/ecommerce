<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/juzacms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CartItemCollectionResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return $this->collection->map(
            function ($item) {
                return [
                    'id' => $item->id,
                    'sku_code' => $item->sku_code,
                    'barcode' => $item->barcode,
                    'title' => $item->product->title,
                    'thumbnail' => upload_url($item->product->thumbnail),
                    'description' => $item->product->description,
                    'names' => $item->names,
                    'price' => ecom_price_with_unit($item->price),
                    'compare_price' => ecom_price_with_unit($item->compare_price),
                    'stock' => $item->stock,
                    'type' => $item->type,
                    'url' => $item->product->getLink(),
                    'line_price' => ecom_price_with_unit($item->line_price),
                    'quantity' => (int) $item->quantity,
                ];
            }
        )->toArray();
    }
}
