<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Ecommerce\Models;

use Juzaweb\Core\Models\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'title',
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

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
