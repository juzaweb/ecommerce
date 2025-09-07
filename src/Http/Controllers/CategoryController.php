<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Ecommerce\Http\Controllers;

use Juzaweb\Core\Facades\Breadcrumb;
use Juzaweb\Core\Http\Controllers\AdminController;
use Juzaweb\Core\Http\Requests\BulkActionsRequest;
use Juzaweb\Modules\Ecommerce\Http\DataTables\CategoriesDataTable;
use Juzaweb\Modules\Ecommerce\Http\Requests\ProductCategoryRequest;
use Juzaweb\Modules\Ecommerce\Models\ProductCategory;

class CategoryController extends AdminController
{
    public function index(CategoriesDataTable $dataTable)
    {
        Breadcrumb::add(__('Categories'));

        return $dataTable->render(
            'ecommerce::category.index',
            []
        );
    }

    public function create()
    {
        Breadcrumb::add(__('Categories'), action([static::class, 'index']));

        Breadcrumb::add(__('Create Category'));

        $locale = $this->getFormLanguage();

        return view(
            'ecommerce::category.form',
            [
                'locale' => $locale,
                'model' => new ProductCategory(),
                'action' => action([static::class, 'store']),
            ]
        );
    }

    public function edit(string $id)
    {
        Breadcrumb::add(__('Categories'), action([static::class, 'index']));

        Breadcrumb::add(__('Create Category'));

        $model = ProductCategory::findOrFail($id);
        $locale = $this->getFormLanguage();

        $model->setDefaultLocale($locale);

        return view(
            'ecommerce::category.form',
            [
                'locale' => $locale,
                'model' => $model,
                'action' => action([static::class, 'update'], ['id' => $id]),
            ]
        );
    }

    public function store(ProductCategoryRequest $request)
    {
        $category = ProductCategory::create($request->validated());

        return $this->success(
            [
                'message' => __('Category created successfully'),
                'redirect' => action([static::class, 'index']),
            ]
        );
    }

    public function update(ProductCategoryRequest $request, string $id)
    {
        $category = ProductCategory::findOrFail($id);

        $category->update($request->validated());

        return $this->success(
            [
                'message' => __('Category updated successfully'),
                'redirect' => action([static::class, 'index']),
            ]
        );
    }

    public function bulk(BulkActionsRequest $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if ($action == 'delete') {
            ProductCategory::destroy($ids);
        }

        return $this->success(
            [
                'message' => __('Bulk action performed successfully'),
            ]
        );
    }
}
