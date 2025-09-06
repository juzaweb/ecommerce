<?php

namespace Juzaweb\Modules\Ecommerce\Http\Controllers;

use Juzaweb\Core\Facades\Breadcrumb;
use Juzaweb\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Ecommerce\Http\DataTables\ProductsDataTable;
use Juzaweb\Modules\Ecommerce\Models\Product;

class ProductController extends AdminController
{
    public function index(ProductsDataTable $dataTable)
    {
        Breadcrumb::add(__('Products'));

        return $dataTable->render(
            'ecommerce::product.index',
            ['title' => __('Products')]
        );
    }

    public function create()
    {
        Breadcrumb::add(__('Products'), admin_url('products'));

        Breadcrumb::add(__('Create Product'));

        $locale = $this->getFormLanguage();

        return view(
            'ecommerce::product.form',
            [
                'locale' => $locale,
                'model' => new Product(),
                'action' => action([static::class, 'store']),
            ]
        );
    }

    public function edit(string $id)
    {
        return view('ecommerce::product.form');
    }
}
