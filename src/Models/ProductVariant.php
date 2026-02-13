<?php

namespace Juzaweb\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\HasAPI;
use Juzaweb\Modules\Core\Traits\Translatable;
use Juzaweb\Modules\Core\Traits\UsedInFrontend;
use Juzaweb\Modules\Core\Translations\Contracts\Translatable as TranslatableContract;
use Juzaweb\Modules\Ecommerce\Enums\VariantType;

class ProductVariant extends Model implements TranslatableContract
{
    use HasAPI, HasUuids, Translatable, UsedInFrontend;

    protected $table = 'product_variants';

    protected $fillable = [
        'sku_code',
        'barcode',
        'price',
        'compare_price',
        'type',
        'downloadable',
        'product_id',
    ];

    protected $casts = [
        'downloadable' => 'boolean',
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'type' => VariantType::class,
    ];

    public $translatedAttributes = [
        'title',
        'description',
        'locale',
    ];

    protected $appends = [
        'thumbnail',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function getThumbnailAttribute()
    {
        if (! $this->relationLoaded('product')) {
            return null;
        }

        return $this->product->thumbnail;
    }

    public function scopeWhereInFrontend(Builder $builder, bool $cache = true): Builder
    {
        return $builder->with(['product'])
            ->withTranslation(null, null, $cache);
    }
}
