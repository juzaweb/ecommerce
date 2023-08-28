<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Ecommerce\Repositories\Criterias\Product;

use Illuminate\Database\Eloquent\Builder;
use Juzaweb\Backend\Models\Post;
use Juzaweb\CMS\Models\User;
use Juzaweb\CMS\Repositories\Abstracts\Criteria;
use Juzaweb\CMS\Repositories\Contracts\CriteriaInterface;
use Juzaweb\CMS\Repositories\Contracts\RepositoryInterface;

class PurchasedProductsCriteria extends Criteria implements CriteriaInterface
{
    public function __construct(protected User $user)
    {
    }

    /**
     * Apply criteria in query repository
     *
     * @param  Builder|Post  $model
     * @param  RepositoryInterface  $repository
     *
     * @return Builder
     */
    public function apply($model, RepositoryInterface $repository): Builder
    {
        return $model->whereHas('orders', fn($q) => $q->where(['user_id' => $this->user->id]));
    }
}
