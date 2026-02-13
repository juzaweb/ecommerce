<?php

namespace Juzaweb\Modules\Ecommerce\Models;

use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\HasAPI;

class ProductVariantDownload extends Model
{
    use HasAPI;

    protected $table = 'product_variant_downloads';

    protected $fillable = [
        'product_variant_id',
        'file_type',
        'file_path',
    ];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
