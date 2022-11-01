<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/juzacms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Juzaweb\Backend\Models\Post;
use Juzaweb\CMS\Models\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $fillable = [
        'title',
        'thumbnail',
        'price',
        'line_price',
        'quantity',
        'compare_price',
        'sku_code',
        'barcode',
        'order_id',
        'product_id',
        'variant_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'product_id', 'id');
    }
}
