<?php

namespace Juzaweb\Ecommerce\Http\Controllers\Backend;

use Juzaweb\CMS\Traits\ResourceController;
use Illuminate\Support\Facades\Validator;
use Juzaweb\CMS\Http\Controllers\BackendController;
use Juzaweb\Ecommerce\Http\Datatables\OrderDatatable;
use Juzaweb\Ecommerce\Models\Order;
use Juzaweb\Ecommerce\Models\PaymentMethod;

class OrderController extends BackendController
{
    use ResourceController {
        getDataForForm as DataForForm;
    }

    protected string $viewPrefix = 'ecom::backend.order';

    protected function getDataTable(...$params): OrderDatatable
    {
        return new OrderDatatable();
    }

    protected function validator(array $attributes, ...$params): \Illuminate\Validation\Validator
    {
        return Validator::make(
            $attributes,
            [
                // Rules
            ]
        );
    }

    protected function getDataForForm($model, ...$params): array
    {
        $data = $this->DataForForm($model, $params);
        $data['paymentMethods'] = PaymentMethod::get(['id', 'name'])->mapWithKeys(
            function ($item) {
                return [$item->id => $item->name];
            }
        )->toArray();
        $data['statuses'] = [
            Order::PAYMENT_STATUS_COMPLETED => trans('ecom::content.completed'),
            Order::PAYMENT_STATUS_PENDING => trans('ecom::content.pending')
        ];
        return $data;
    }

    protected function getModel(...$params): string
    {
        return Order::class;
    }

    protected function getTitle(...$params): string
    {
        return trans('ecom::content.orders');
    }
}
