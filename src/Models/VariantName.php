<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Juzaweb\CMS\Models\Model;
use Juzaweb\Network\Traits\Networkable;

/**
 * Juzaweb\Ecommerce\Models\VariantName
 *
 * @property-read Collection|VariantNameItem[] $items
 * @property-read int|null $items_count
 * @method static Builder|VariantName newModelQuery()
 * @method static Builder|VariantName newQuery()
 * @method static Builder|VariantName query()
 * @mixin Eloquent
 */
class VariantName extends Model
{
    use Networkable;

    protected $table = 'variant_names';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function items(): HasMany
    {
        return $this->hasMany(VariantNameItem::class, 'variant_name_id', 'id');
    }
}
