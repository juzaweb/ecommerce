<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Ecommerce\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Juzaweb\Backend\Models\Post;

class Product extends Post
{
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(
            Order::class,
            OrderItem::getTableName(),
            'product_id',
            'order_id'
        );
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(
            OrderItem::class,
            'product_id',
            'id'
        );
    }

    public function downloadLinks(): HasMany
    {
        return $this->hasMany(DownloadLink::class, 'product_id', 'id');
    }
}
