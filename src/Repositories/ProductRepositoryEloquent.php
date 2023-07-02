<?php

namespace Juzaweb\Ecommerce\Repositories;

use Juzaweb\Backend\Repositories\PostRepositoryEloquent;
use Juzaweb\Ecommerce\Models\Product;

/**
 * Class TaxonomyRepositoryEloquentEloquent.
 *
 * @package namespace Juzaweb\Backend\Repositories;
 */
class ProductRepositoryEloquent extends PostRepositoryEloquent implements ProductRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Product::class;
    }
}
