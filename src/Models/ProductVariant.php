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

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Juzaweb\Backend\Models\Post;
use Juzaweb\CMS\Models\Model;
use Juzaweb\CMS\Traits\QueryCache\QueryCacheable;
use Juzaweb\CMS\Traits\ResourceModel;
use Juzaweb\Network\Traits\Networkable;

/**
 * Juzaweb\Ecommerce\Models\Variant
 *
 * @property int $id
 * @property string $title
 * @property string $price
 * @property string|null $compare_price
 * @property string|null $sku_code
 * @property string|null $barcode
 * @property array|null $images
 * @property int $product_id
 * @property-read Post $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereComparePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereSkuCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereTitle($value)
 * @property string|null $thumbnail
 * @property string|null $summary
 * @property string|null $description
 * @property array|null $names
 * @property string|null $sale_price
 * @property string $type
 * @property int $post_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Attribute[] $attributes
 * @property-read int|null $attributes_count
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereNames($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereUpdatedAt($value)
 * @property int $site_id
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereFilter(array $params = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereSiteId($value)
 * @mixin \Eloquent
 */
class ProductVariant extends Model
{
    use ResourceModel, QueryCacheable, Networkable;

    public string $cachePrefix = 'product_variants_';

    protected $table = 'product_variants';

    protected $fillable = [
        'sku_code',
        'barcode',
        'title',
        'thumbnail',
        'description',
        'names',
        'images',
        'price',
        'compare_price',
        'type',
        'post_id',
    ];

    protected $casts = [
        'images' => 'array',
        'names' => 'array',
    ];

    protected string $fieldName = 'title';

    public static function findByProduct($productId): EloquentModel|ProductVariant|null
    {
        return self::where('post_id', '=', $productId)
            ->orderBy('id', 'ASC')
            ->first();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'variants_attributes', 'variant_id', 'attribute_id');
    }

    public function getThumbnail(): ?string
    {
        if ($this->thumbnail) {
            return $this->thumbnail;
        }

        return $this->product->thumbnail;
    }

    protected function getCacheBaseTags(): array
    {
        return [
            'product_variants',
        ];
    }
}
