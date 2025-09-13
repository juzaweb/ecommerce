<?php

namespace Juzaweb\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Juzaweb\Core\Models\Model;
use Juzaweb\Core\Traits\HasAPI;

class Cart extends Model
{
    use HasAPI, HasUuids;

    protected $table = 'carts';

    protected $fillable = [
        'user_id',
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id', 'id');
    }

    public function getTotalAmount()
    {
        return $this->items->sum(fn ($item) => $item->variant->price * $item->quantity);
    }
}
