<?php

namespace Juzaweb\Modules\Ecommerce\Models;

use Juzaweb\Core\Models\Model;
use Juzaweb\Core\Traits\HasAPI;

class ProductVariant extends Model
{
    use HasAPI;

    protected $table = 'product_variants';

    protected $fillable = [];
}
