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

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Juzaweb\CMS\Models\Model;
use Juzaweb\CMS\Models\User;
use Juzaweb\CMS\Traits\ResourceModel;
use Juzaweb\Network\Traits\Networkable;

/**
 * Juzaweb\Ecommerce\Models\Order
 *
 * @property int $id
 * @property string $key
 * @property string $code
 * @property string $name
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property string|null $country_code
 * @property int $quantity
 * @property string $total_price
 * @property string $total
 * @property string $discount
 * @property string|null $discount_codes
 * @property string|null $discount_target_type
 * @property int|null $payment_method_id
 * @property string $payment_method_name
 * @property string|null $notes
 * @property int $other_address
 * @property string $payment_status pending
 * @property string $delivery_status pending
 * @property int|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read PaymentMethod|null $paymentMethod
 * @property-read User|null $user
 * @method static Builder|Order newModelQuery()
 * @method static Builder|Order newQuery()
 * @method static Builder|Order query()
 * @method static Builder|Order whereAddress($value)
 * @method static Builder|Order whereCode($value)
 * @method static Builder|Order whereCountryCode($value)
 * @method static Builder|Order whereCreatedAt($value)
 * @method static Builder|Order whereDeliveryStatus($value)
 * @method static Builder|Order whereDiscount($value)
 * @method static Builder|Order whereDiscountCodes($value)
 * @method static Builder|Order whereDiscountTargetType($value)
 * @method static Builder|Order whereEmail($value)
 * @method static Builder|Order whereId($value)
 * @method static Builder|Order whereKey($value)
 * @method static Builder|Order whereName($value)
 * @method static Builder|Order whereNotes($value)
 * @method static Builder|Order whereOtherAddress($value)
 * @method static Builder|Order wherePaymentMethodId($value)
 * @method static Builder|Order wherePaymentMethodName($value)
 * @method static Builder|Order wherePaymentStatus($value)
 * @method static Builder|Order wherePhone($value)
 * @method static Builder|Order whereQuantity($value)
 * @method static Builder|Order whereTotal($value)
 * @method static Builder|Order whereTotalPrice($value)
 * @method static Builder|Order whereUpdatedAt($value)
 * @method static Builder|Order whereUserId($value)
 * @method static Builder|Order whereToken($value)
 * @property string $token
 * @property int $site_id
 * @property-read string $payment_status_text
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Juzaweb\Ecommerce\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Juzaweb\Ecommerce\Models\Product> $products
 * @property-read int|null $products_count
 * @method static Builder|Order whereFilter(array $params = [])
 * @method static Builder|Order whereSiteId($value)
 * @mixin Eloquent
 */
class Order extends Model
{
    use ResourceModel, Networkable;

    protected $table = 'orders';

    protected $fillable = [
        'code',
        'token',
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

    protected string $fieldName = 'name';

    protected $appends = [
        'payment_status_text',
    ];

    public const PAYMENT_STATUS_PENDING = 'pending';
    public const PAYMENT_STATUS_COMPLETED = 'completed';

    public static function findByCode(string $code, array $columns = ['*']): null|static
    {
        return Order::whereCode($code)->first($columns);
    }

    public static function findByToken(string $token, array $columns = ['*']): null|static
    {
        return Order::whereToken($token)->first($columns);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'order_items',
            'order_id',
            'product_id',
            'id',
            'id'
        );
    }

    public function downloadableProducts()
    {
        return $this->products()->whereMeta('downloadable', 1);
    }

    public function isPaymentCompleted(): bool
    {
        return $this->payment_status == static::PAYMENT_STATUS_COMPLETED;
    }

    public function getPaymentStatusTextAttribute(): string
    {
        return match ($this->payment_status) {
            self::PAYMENT_STATUS_COMPLETED => trans('ecom::content.completed'),
            default => trans('ecom::content.pending'),
        };
    }
}
