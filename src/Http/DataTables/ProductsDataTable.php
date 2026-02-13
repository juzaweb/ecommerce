<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Ecommerce\Http\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Core\DataTables\Action;
use Juzaweb\Modules\Core\DataTables\BulkAction;
use Juzaweb\Modules\Core\DataTables\Column;
use Juzaweb\Modules\Core\DataTables\DataTable;
use Juzaweb\Modules\Ecommerce\Models\Product;
use Yajra\DataTables\EloquentDataTable;

class ProductsDataTable extends DataTable
{
    protected string $actionUrl = 'products/bulk';

    public function query(Product $model): Builder
    {
        return $model->newQuery()->withTranslation()->filter(request()->all());
    }

    public function getColumns(): array
    {
        return [
            Column::checkbox(),
            Column::id(),
            Column::editLink('name', admin_url('products/{id}/edit'), __('Name')),
            Column::createdAt(),
            Column::actions(),
        ];
    }

    public function renderColumns(EloquentDataTable $builder): EloquentDataTable
    {
        return $builder;
    }

    public function bulkActions(): array
    {
        return [
            BulkAction::delete()->can('products.delete'),
            BulkAction::make(__('core::translation.translate'), null, 'fas fa-language')
                ->type('url')
                ->action('translate')
                ->can('products.edit'),
        ];
    }

    public function actions(Model $model): array
    {
        return [
            Action::edit(admin_url("products/{$model->id}/edit"))
                ->can('products.edit'),
            Action::delete()
                ->can('products.delete'),
        ];
    }
}
