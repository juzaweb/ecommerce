<?php

namespace Juzaweb\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Juzaweb\Core\Models\Model;
use Juzaweb\Core\Traits\HasAPI;
use Juzaweb\Core\Traits\HasSeoMeta;
use Juzaweb\Core\Traits\Translatable;
use Juzaweb\FileManager\Traits\HasMedia;
use Juzaweb\Modules\Ecommerce\Enums\ProductStatus;
use Juzaweb\Translations\Contracts\Translatable as TranslatableContract;

class Product extends Model implements TranslatableContract
{
    use HasAPI, HasUuids, Translatable, HasSeoMeta, HasMedia;

    protected $table = 'products';

    protected $fillable = [
        'inventory',
        'status',
    ];

    public $translatedAttributes = [
        'name',
        'content',
        'slug',
    ];

    protected $casts = [
        'status' => ProductStatus::class,
        'inventory' => 'boolean',
    ];

    public $mediaChannels = ['thumbnail'];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function variant()
    {
        return $this->hasOne(ProductVariant::class, 'product_id');
    }

    public function categories()
    {
        return $this->belongsToMany(
            ProductCategory::class,
            'product_category_product',
            'product_id',
            'category_id'
        );
    }

    public function tags()
    {
        return $this->belongsToMany(
            ProductTag::class,
            'product_product_tag',
            'product_id',
            'tag_id'
        );
    }

    public function seoMetaFill(): array
    {
        return [
            'title' => $this->name,
            'description' => seo_string($this->content, 160),
        ];
    }
}
