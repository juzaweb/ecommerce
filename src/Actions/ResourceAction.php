<?php

namespace Juzaweb\Ecommerce\Actions;

use Illuminate\Database\Eloquent\Model;
use Juzaweb\CMS\Abstracts\Action;
use Juzaweb\Ecommerce\Http\Datatables\InventoryDatatable;
use Juzaweb\Ecommerce\Http\Datatables\VariantDatatable;
use Juzaweb\Ecommerce\Repositories\VariantRepository;

class ResourceAction extends Action
{
    public function handle()
    {
        $this->addAction(Action::INIT_ACTION, [$this, 'registerResources']);
    }

    public function registerResources()
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
                    'row' => [
                        'type' => 'row',
                        'fields' => [
                            'col' => [
                                'type' => 'col',
                                'col' => 6,
                                'fields' => [
                                    'price' => [
                                        'label' => trans('ecom::content.price'),
                                        'data' => [
                                            'prefix' => '$',
                                        ]
                                    ],
                                ]
                            ],
                            'col2' => [
                                'type' => 'col',
                                'col' => 6,
                                'fields' => [
                                    'compare_price' => [
                                        'label' => trans('ecom::content.compare_price'),
                                        'data' => [
                                            'prefix' => '$',
                                            'class' => 'is-number'
                                        ]
                                    ],
                                ]
                            ],
                        ]
                    ],
                    'thumbnail' => [
                        'type' => 'image',
                        'sidebar' => true,
                    ],
                    'images' => [
                        'type' => 'images'
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
}
