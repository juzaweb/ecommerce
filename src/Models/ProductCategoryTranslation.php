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
use Juzaweb\Core\Traits\HasSlug;

class ProductCategoryTranslation extends Model
{
    use HasSlug;

    protected $table = 'product_category_translations';

    protected $fillable = [
        'name',
        'description',
        'slug',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}
