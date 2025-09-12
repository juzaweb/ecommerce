<?php

namespace Juzaweb\Modules\Ecommerce\Models;

use Juzaweb\Core\Models\Model;
use Juzaweb\Core\Traits\HasAPI;

class ProductTag extends Model
{
    use HasAPI;

    protected $table = 'product_tags';

    protected $fillable = [];
}
