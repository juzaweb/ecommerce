<?php

namespace Juzaweb\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Juzaweb\Core\Models\Model;
use Juzaweb\Core\Traits\HasAPI;
use Juzaweb\Core\Traits\Translatable;

class ProductCategory extends Model
{
    use HasAPI, HasUuids, Translatable;

    protected $table = 'product_categories';

    protected $fillable = [];

    public $translatedAttributes = [
        'name',
        'description',
        'slug',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category', 'category_id', 'product_id');
    }
}
