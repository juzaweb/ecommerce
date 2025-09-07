<?php

namespace Juzaweb\Modules\Ecommerce\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Juzaweb\Core\Facades\Breadcrumb;
use Juzaweb\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Ecommerce\Http\DataTables\ProductsDataTable;
use Juzaweb\Modules\Ecommerce\Http\Requests\ProductRequest;
use Juzaweb\Modules\Ecommerce\Models\Product;
use Juzaweb\Modules\Ecommerce\Models\ProductVariant;

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
        $variant = new ProductVariant();

        return view(
            'ecommerce::product.form',
            [
                'locale' => $locale,
                'model' => new Product(),
                'variant' => $variant,
                'action' => action([static::class, 'store']),
            ]
        );
    }

    public function edit(string $id)
    {
        /** @var Product $model */
        $model = Product::withTranslation()->findOrFail($id);

        Breadcrumb::add(__('Products'), admin_url('products'));

        Breadcrumb::add(__('Edit Product :name', ['name' => $model->name]));

        $locale = $this->getFormLanguage();
        $model->setDefaultLocale($locale);
        $variant = $model->variants()->first();
        $variant->setDefaultLocale($locale);

        return view(
            'ecommerce::product.form',
            [
                'locale' => $locale,
                'model' => $model,
                'variant' => $variant,
                'action' => action([static::class, 'update'], [$id]),
            ]
        );
    }

    public function store(ProductRequest $request)
    {
        $locale = $this->getFormLanguage();

        DB::transaction(
            function () use ($request, $locale) {
                $data = $request->validated();

                $model = Product::create($data);

                if ($thumbnail = $request->input('thumbnail')) {
                    $model->attachOrUpdateMedia($thumbnail, 'thumbnail');
                }

                $data[$locale]['title'] = 'default';
                $model->variants()->create($data);

                do_action('ecommerce_product_save', $model, $request);

                return $model;
            }
        );

        return $this->success(
            [
                'message' => __('Create product successfully!'),
                'redirect' => action([static::class, 'index']),
            ]
        );
    }

    public function update(ProductRequest $request, string $id)
    {
        /** @var Product $model */
        $model = Product::findOrFail($id);
        $locale = $this->getFormLanguage();

        DB::transaction(
            function () use ($model, $request, $locale) {
                $data = $request->validated();
                $model->update($data);

                if ($thumbnail = $request->input('thumbnail')) {
                    $model->attachOrUpdateMedia($thumbnail, 'thumbnail');
                }

                $data[$locale]['title'] = 'default';
                $variant = $model->variants()->updateOrCreate([], $data);

                do_action('ecommerce_product_save', $model, $request);

                return $model;
            }
        );

        return $this->success(
            [
                'message' => __('Update product successfully!'),
                'redirect' => action([static::class, 'index']),
            ]
        );
    }
}
