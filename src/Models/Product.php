<?php

namespace Juzaweb\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Juzaweb\Core\Models\Model;
use Juzaweb\Core\Traits\HasAPI;
use Juzaweb\Core\Traits\Translatable;
use Juzaweb\Translations\Contracts\Translatable as TranslatableContract;

class Product extends Model implements TranslatableContract
{
    use HasAPI, HasUuids, Translatable;

    protected $table = 'products';

    protected $fillable = [
        'status',
    ];

    public $translatedAttributes = [
        'name',
        'content',
        'slug',
    ];

    protected $casts = [
        'status' => 'decimal:2',
    ];
}
