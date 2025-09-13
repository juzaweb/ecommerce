<?php

namespace Juzaweb\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Juzaweb\Core\Models\Model;
use Juzaweb\Core\Traits\HasAPI;
use Juzaweb\Core\Traits\HasCodeWithMonth;
use Juzaweb\Modules\Payment\Contracts\Paymentable;
use Juzaweb\Modules\Payment\Models\PaymentHistory;

class Order extends Model implements Paymentable
{
    use HasAPI, HasCodeWithMonth;

    protected $table = 'orders';

    protected $fillable = [
        'code',
        'name',
        'phone',
        'email',
        'address',
        'country_code',
        'quantity',
        'total_price',
        'total',
        'discount',
        'discount_codes',
        'discount_target_type',
        'payment_method_id',
        'payment_method_name',
        'notes',
        'other_address',
        'payment_status',
        'delivery_status',
        'user_id',
    ];

    protected $casts = [
        'other_address' => 'boolean',
        'total_price' => 'float',
        'total' => 'float',
        'discount' => 'float',
        'quantity' => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function paymentHistories(): MorphMany
    {
        return $this->morphMany(PaymentHistory::class, 'paymentable');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_id');
    }

    public function getTotalAmount(): float
    {
        return $this->total;
    }

    public function getCurrency(): string
    {
        return 'USD';
    }

    public function getPaymentDescription(): string
    {
        return __('Order payment for order #:code', ['code' => $this->code]);
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
