<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Juzaweb\CMS\Models\Model;
use Juzaweb\CMS\Traits\ResourceModel;

/**
 * Juzaweb\Ecommerce\Models\PaymentMethod
 *
 * @property int $id
 * @property string $type
 * @property string $name
 * @property array|null $data
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Juzaweb\Ecommerce\Models\Order[] $order
 * @property-read int|null $order_count
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereFilter($params = [])
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder active()
 * @property int|null $site_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Juzaweb\Ecommerce\Models\Order> $orders
 * @property-read int|null $orders_count
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereSiteId($value)
 * @mixin \Eloquent
 */
class PaymentMethod extends Model
{
    use ResourceModel;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 1;

    protected $table = 'payment_methods';
    protected string $fieldName = 'name';
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(
            Order::class,
            'payment_method_id',
            'id'
        );
    }

    public function scopeActive($builder)
    {
        return $builder->where('active', '=', self::STATUS_ACTIVE);
    }
}
