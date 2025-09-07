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
use Juzaweb\Core\DataTables\Action;
use Juzaweb\Core\DataTables\BulkAction;
use Juzaweb\Core\DataTables\Column;
use Juzaweb\Core\DataTables\DataTable;
use Juzaweb\Modules\Ecommerce\Models\ProductCategory;
use Yajra\DataTables\EloquentDataTable;

class CategoriesDataTable extends DataTable
{
    protected string $actionUrl = 'product-categories/bulk';

    public function query(ProductCategory $model): Builder
    {
        return $model->newQuery()->withTranslation()->filter(request()->all());
    }

    public function getColumns(): array
    {
        return [
            Column::checkbox(),
            Column::id(),
            Column::editLink('name', admin_url('product-categories/{id}/edit'), __('Name')),
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
            BulkAction::delete()->can('product-categories.delete'),
        ];
    }

    public function actions(Model $model): array
    {
        return [
            Action::edit(admin_url("product-categories/{$model->id}/edit"))
                ->can('product-categories.edit'),
            Action::delete()
                ->can('product-categories.delete'),
        ];
    }
}
