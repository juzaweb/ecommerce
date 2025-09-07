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

class ProductVariantTranslation extends Model
{
    protected $table = 'product_variant_translations';

    protected $fillable = [
        'title',
        'description',
        'product_variant_id',
    ];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
