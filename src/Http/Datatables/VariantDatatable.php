<?php

namespace Juzaweb\Ecommerce\Http\Datatables;

use Juzaweb\Backend\Http\Datatables\PostType\ResourceDatatable;
use Juzaweb\Ecommerce\Models\ProductVariant;

class VariantDatatable extends ResourceDatatable
{
    /**
     * Columns datatable
     *
     * @return array
     */
    public function columns(): array
    {
        return [
            'thumbnail' => [
                'label' => trans('cms::app.thumbnail'),
                'width' => '10%',
                'formatter' => function ($value, $row, $index) {
                    return '<img src="'.upload_url($value).'" class="w-100" />';
                }
            ],
            'title' => [
                'label' => trans('cms::app.title'),
                'formatter' => function ($value, $row, $index) {
                    return $this->rowActionsFormatter($value, $row, $index);
                }
            ],
            'price' => [
                'label' => trans('ecom::content.price'),
            ],
            'created_at' => [
                'label' => trans('cms::app.created_at'),
                'width' => '15%',
                'align' => 'center',
                'formatter' => function ($value, $row, $index) {
                    return jw_date_format($row->created_at);
                }
            ]
        ];
    }

    public function bulkActions($action, $ids): void
    {
        switch ($action) {
            case 'delete':
                ProductVariant::destroy($ids);
                break;
        }
    }
}
