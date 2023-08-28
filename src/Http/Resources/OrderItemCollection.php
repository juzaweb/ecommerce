<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderItemCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return $this->collection->map(
            function ($item) {
                return [
                    'title' => $item->title,
                    'price' => ecom_price_with_unit($item->price),
                    'quantity' => $item->quantity,
                    'line_price' => ecom_price_with_unit($item->line_price),
                    'compare_price' => ecom_price_with_unit($item->compare_price),
                    'thumbnail' => upload_url($item->thumbnail),
                    'sku_code' => $item->sku_code,
                    'barcode' => $item->barcode,
                    'created_at' => jw_date_format($item->created_at),
                    'updated_at' => jw_date_format($item->updated_at),
                ];
            }
        )->toArray();
    }
}
