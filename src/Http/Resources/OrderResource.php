<?php

namespace Juzaweb\Ecommerce\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Juzaweb\Ecommerce\Models\Order;

/**
 * @property-read Order $resource
 */
class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $resource = [
            'code' => $this->resource->code,
            'token' => $this->resource->token,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'address' => $this->resource->address,
            'total_price' => ecom_price_with_unit($this->resource->total_price),
            'total' => ecom_price_with_unit($this->resource->total),
            'notes' => $this->resource->notes,
            'other_address' => $this->resource->other_address,
            'payment_status' => $this->resource->payment_status,
            'payment_status_text' => $this->resource->payment_status_text,
            'delivery_status' => $this->resource->delivery_status,
            'quantity' => $this->resource->quantity,
            'customer' => [
                'name' => $this->resource->name,
                'email' => $this->resource->email,
                'phone' => $this->resource->phone,
            ],
            'created_at' => jw_date_format($this->resource->created_at),
        ];

        if ($this->resource->relationLoaded('paymentMethod')) {
            $resource['payment_method'] = [
                'name' => $this->resource->payment_method_name,
                'description' => $this->resource->paymentMethod?->description,
            ];
        }

        if ($this->resource->relationLoaded('orderItems')) {
            $resource['items'] = OrderItemCollection::make($this->resource->orderItems)->resolve();
        }

        if (isset($this->resource->downloadable_products_exists)) {
            $resource['downloadable_products_exists'] = $this->resource->downloadable_products_exists;
        }

        if ($this->resource->relationLoaded('downloadableProducts')) {
            $resource['downloadable_products'] = DownloadableProductCollection::make($this->resource->downloadableProducts)
                ->withOrder($this->resource)
                ->resolve();
        }

        return $resource;
    }
}
