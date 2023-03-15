<?php

namespace Juzaweb\Ecommerce\Repositories;

use Juzaweb\CMS\Repositories\BaseRepositoryEloquent;
use Juzaweb\CMS\Traits\Criterias\UseFilterCriteria;
use Juzaweb\CMS\Traits\Criterias\UseSearchCriteria;
use Juzaweb\CMS\Traits\Criterias\UseSortableCriteria;
use Juzaweb\CMS\Traits\ResourceRepositoryEloquent;
use Juzaweb\Ecommerce\Models\ProductVariant;

class VariantRepositoryEloquent extends BaseRepositoryEloquent implements VariantRepository
{
    use ResourceRepositoryEloquent, UseSearchCriteria, UseFilterCriteria, UseSortableCriteria;

    protected array $searchableFields = ['title'];
    protected array $filterableFields = ['post_id'];

    public function model(): string
    {
        return ProductVariant::class;
    }
}
