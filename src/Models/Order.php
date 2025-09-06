<?php

namespace Juzaweb\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Juzaweb\Core\Models\Model;
use Juzaweb\Core\Traits\HasAPI;
use Juzaweb\Modules\Payment\Contracts\Paymentable;
use Juzaweb\Modules\Payment\Models\PaymentHistory;

class Order extends Model implements Paymentable
{
    use HasAPI;

    protected $table = 'orders';

    protected $fillable = [
        'code',
    ];

    public function paymentHistories(): MorphMany
    {
        return $this->morphMany(PaymentHistory::class, 'paymentable');
    }

    public function getTotalAmount(): float
    {
        return 10;
    }

    public function getCurrency(): string
    {
        return 'USD';
    }

    public function getPaymentDescription(): string
    {
        return __('Order payment for order #:code', ['code' => $this->code]);
    }
}
