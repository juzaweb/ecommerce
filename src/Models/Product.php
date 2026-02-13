<?php

namespace Juzaweb\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Juzaweb\Modules\Core\FileManager\Traits\HasMedia;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Support\Traits\HasComments;
use Juzaweb\Modules\Core\Traits\HasAPI;
use Juzaweb\Modules\Core\Traits\HasSeoMeta;
use Juzaweb\Modules\Core\Traits\HasThumbnail;
use Juzaweb\Modules\Core\Traits\Translatable;
use Juzaweb\Modules\Core\Traits\UsedInFrontend;
use Juzaweb\Modules\Core\Translations\Contracts\Translatable as TranslatableContract;
use Juzaweb\Modules\Ecommerce\Enums\ProductStatus;

class Product extends Model implements TranslatableContract
{
    use HasAPI,
        HasComments,
        HasMedia,
        HasSeoMeta,
        HasThumbnail,
        HasUuids,
        Translatable,
        UsedInFrontend;

    protected $table = 'products';

    protected $fillable = [
        'inventory',
        'status',
        'preview_url',
        'video_url',
        'views',
    ];

    public $translatedAttributes = [
        'name',
        'content',
        'slug',
        'locale',
    ];

    public $translatedAttributeFormats = [
        'content' => 'html',
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

    public function seoMetaFill(): array
    {
        return [
            'title' => $this->name,
            'description' => seo_string($this->content, 160),
        ];
    }
}
