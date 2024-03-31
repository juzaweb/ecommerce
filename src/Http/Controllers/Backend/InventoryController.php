<?php

namespace Juzaweb\Ecommerce\Http\Controllers\Backend;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Facades\Validator;
use Juzaweb\CMS\Abstracts\DataTable;
use Juzaweb\CMS\Traits\ResourceController;
use Juzaweb\CMS\Http\Controllers\BackendController;
use Juzaweb\Ecommerce\Http\Datatables\InventoryDatatable;
use Juzaweb\Ecommerce\Models\Inventory;

class InventoryController extends BackendController
{
    use ResourceController;

    protected string $viewPrefix = 'ecom::backend.inventory';

    protected function getDataTable(...$params): DataTable
    {
        return new InventoryDatatable();
    }

    protected function validator(array $attributes, ...$params): ValidatorContract|array
    {
        $validator = Validator::make($attributes, [
            // Rules
        ]);

        return $validator;
    }

    protected function getModel(...$params): string
    {
        return Inventory::class;
    }

    protected function getTitle(...$params): string
    {
        return trans('ecom::content.inventories');
    }
}
