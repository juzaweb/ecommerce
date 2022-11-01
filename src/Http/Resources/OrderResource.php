<?php

namespace Juzaweb\Ecommerce\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
        $items = OrderItemCollection::make($this->orderItems)->resolve(
            $request
        );

        return [
            'code' => $this->code,
            'token' => $this->token,
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'total_price' => ecom_price_with_unit($this->total_price),
            'total' => ecom_price_with_unit($this->total),
            'notes' => $this->notes,
            'other_address' => $this->other_address,
            'payment_status' => $this->payment_status_text,
            'delivery_status' => $this->delivery_status,
            'quantity' => $this->quantity,
            'customer' => [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
            ],
            'payment_method' => [
                'name' => $this->payment_method_name,
                'description' => $this->paymentMethod?->description,
            ],
            'items' => $items,
        ];
    }
}
