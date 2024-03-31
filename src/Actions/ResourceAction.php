<?php

namespace Juzaweb\Ecommerce\Actions;

use Illuminate\Support\Arr;
use Juzaweb\CMS\Abstracts\Action;
use Juzaweb\Ecommerce\Http\Datatables\InventoryDatatable;
use Juzaweb\Ecommerce\Http\Datatables\VariantDatatable;
use Juzaweb\Ecommerce\Repositories\VariantRepository;

class ResourceAction extends Action
{
    public function handle(): void
    {
        $this->addAction(Action::INIT_ACTION, [$this, 'registerResources']);
        $this->addAction('resource.variants.form_left', [$this, 'addVariantInfo']);
        $this->addFilter('resource.variants.parseDataForSave', [$this, 'parseDataForSave']);
    }

    public function registerResources(): void
    {
        $this->hookAction->registerResource(
            'variants',
            'products',
            [
                'label' => trans('ecom::content.variants'),
                'repository' => VariantRepository::class,
                'datatable' => VariantDatatable::class,
                'validator' => [
                    'title' => ['string', 'required', 'max:150']
                ],
                'fields' => [
                    'title',
                    'description' => [
                        'type' => 'textarea',
                    ],
                    'thumbnail' => [
                        'type' => 'image',
                        'sidebar' => true,
                    ],
                    'sku_code' => [
                        'label' => trans('ecom::content.sku_code'),
                        'sidebar' => true,
                    ],
                    'barcode' => [
                        'label' => trans('ecom::content.barcode'),
                        'sidebar' => true,
                    ],
                ]
            ]
        );

        $this->hookAction->registerResource(
            'inventories',
            null,
            [
                'label' => trans('ecom::content.inventories'),
                'datatable' => InventoryDatatable::class,
                'menu' => [
                    'parent' => 'ecommerce',
                ],
                'create_button' => false,
            ]
        );
    }

    public function addVariantInfo($model): void
    {
        echo e(view('ecom::backend.product.components.variant-info', ['variant' => $model, 'model' => $model->product]));
    }

    public function parseDataForSave($attributes)
    {
        $attributes['price'] = Arr::get($attributes, 'meta.price');
        $attributes['compare_price'] = Arr::get($attributes, 'meta.compare_price');
        $attributes['sku_code'] = Arr::get($attributes, 'meta.sku_code');
        $attributes['barcode'] = Arr::get($attributes, 'meta.barcode');
        $attributes['quantity'] = Arr::get($attributes, 'meta.quantity');
        $attributes['images'] = Arr::get($attributes, 'meta.images');
        return $attributes;
    }
}
