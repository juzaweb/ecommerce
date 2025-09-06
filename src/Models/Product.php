<?php

namespace Juzaweb\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Juzaweb\Core\Models\Model;
use Juzaweb\Core\Traits\HasAPI;

class Product extends Model
{
    use HasAPI, HasUuids;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'content',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];
}
