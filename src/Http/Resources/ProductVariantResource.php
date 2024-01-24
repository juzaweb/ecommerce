<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Ecommerce\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'sku_code' => $this->resource->sku_code,
            'barcode' => $this->resource->barcode,
            'title' => $this->resource->title,
            'thumbnail' => $this->resource->thumbnail,
            'description' => $this->resource->description,
            'names' => $this->resource->names,
            'images' => $this->resource->images,
            'price' => $this->resource->price,
            'compare_price' => $this->resource->compare_price,
            'type' => $this->resource->type,
        ];
    }
}
