<?php

namespace Juzaweb\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Juzaweb\Core\Models\Model;
use Juzaweb\Core\Traits\HasAPI;
use Juzaweb\Core\Traits\Translatable;
use Juzaweb\Modules\Ecommerce\Enums\VariantType;
use Juzaweb\Translations\Contracts\Translatable as TranslatableContract;

class ProductVariant extends Model implements TranslatableContract
{
    use HasAPI, HasUuids, Translatable;

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
    ];
}
