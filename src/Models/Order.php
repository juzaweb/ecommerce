<?php

namespace Juzaweb\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Juzaweb\Core\Models\Model;
use Juzaweb\Core\Traits\HasAPI;
use Juzaweb\Core\Traits\HasCodeWithMonth;
use Juzaweb\Modules\Ecommerce\Enums\OrderPaymentStatus;
use Juzaweb\Modules\Payment\Contracts\Paymentable;
use Juzaweb\Modules\Payment\Models\PaymentHistory;
use Juzaweb\Modules\Payment\Models\PaymentMethod;

class Order extends Model implements Paymentable
{
    use HasAPI, HasCodeWithMonth, HasUuids;

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
        'payment_status' => OrderPaymentStatus::class,
    ];

    protected $appends = [
        'payment_status_text',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(
            Product::class,
            OrderItem::class,
            'order_id', // Foreign key on OrderItem table...
            'id', // Foreign key on Product table...
            'id', // Local key on Order table...
            'product_id' // Local key on OrderItem table...
        );
    }

    public function paymentHistories(): MorphMany
    {
        return $this->morphMany(PaymentHistory::class, 'paymentable');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_id');
    }

    public function getPaymentStatusTextAttribute(): string
    {
        return $this->payment_status?->label() ?? '';
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
