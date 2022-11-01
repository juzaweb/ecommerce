<?php

namespace Juzaweb\Ecommerce\Repositories;

use Juzaweb\CMS\Repositories\BaseRepositoryEloquent;
use Juzaweb\Ecommerce\Models\Cart;

/**
 * Class TaxonomyRepositoryEloquentEloquent.
 *
 * @package namespace Juzaweb\Backend\Repositories;
 */
class CartRepositoryEloquent extends BaseRepositoryEloquent implements CartRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Cart::class;
    }
}
