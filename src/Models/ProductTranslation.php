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

class ProductTranslation extends Model
{
    use HasSlug;

    protected $table = 'product_translations';

    protected $fillable = [
        'name',
        'content',
        'slug',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
