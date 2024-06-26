<?php

namespace Juzaweb\Ecommerce\Actions;

use Juzaweb\CMS\Abstracts\Action;
use Juzaweb\CMS\Facades\HookAction;
use Juzaweb\Ecommerce\Http\Resources\OrderResource;
use Juzaweb\Ecommerce\Models\Order;
use Juzaweb\Ecommerce\Models\Product;

class MenuAction extends Action
{
    public function handle(): void
    {
        $this->addAction(
            Action::BACKEND_INIT,
            [$this, 'addAdminMenus']
        );

        $this->addAction(
            Action::FRONTEND_INIT,
            [$this, 'addProfilePages']
        );
    }

    public function addAdminMenus(): void
    {
        HookAction::registerAdminPage(
            'ecommerce',
            [
                'title' => trans('ecom::content.ecommerce'),
                'menu' => [
                    'icon' => 'fa fa-shopping-cart',
                    'position' => 50,
                ]
            ]
        );

        HookAction::registerAdminPage(
            'ecommerce.orders',
            [
                'title' => trans('ecom::content.orders'),
                'menu' => [
                    'icon' => 'fa fa-shopping-cart',
                    'position' => 5,
                    'parent' => 'ecommerce'
                ]
            ]
        );

        HookAction::registerAdminPage(
            'ecommerce.payment-methods',
            [
                'title' => trans('ecom::content.payment_methods'),
                'menu' => [
                    'icon' => 'fa fa-credit-card',
                    'position' => 10,
                    'parent' => 'ecommerce'
                ]
            ]
        );

        HookAction::registerAdminPage(
            'ecommerce.settings',
            [
                'title' => trans('ecom::content.setting'),
                'menu' => [
                    'icon' => 'fa fa-shopping-cart',
                    'position' => 50,
                    'parent' => 'ecommerce'
                ]
            ]
        );
    }

    public function addProfilePages(): void
    {
        $this->registerProfilePage(
            'ecommerce.orders',
            [
                'title' => __('Orders'),
                'contents' => 'ecom::frontend.profile.orders.index',
                'icon' => 'shopping-cart',
                'data' => [
                    'orders' => fn () => OrderResource::collection(
                        Order::with(['paymentMethod'])
                            ->withExists(['downloadableProducts'])
                            ->where('user_id', auth()->id())
                            ->paginate(10)
                    ),
                    'thank_page' => function () {
                        $thanksPage = get_config('ecom_thanks_page');

                        return $thanksPage ? get_page_url($thanksPage) : null;
                    }
                ]
            ]
        );

        // $this->registerProfilePage(
        //     'ecommerce.download',
        //     [
        //         'title' => __('Download'),
        //         'contents' => 'ecom::frontend.profile.download.index',
        //         'icon' => 'download',
        //         'data' => [
        //             'purchased' => fn () => Product::select(['title', 'id'])
        //                 ->whereIn(
        //                     'id',
        //                     Order::select(['order_items.product_id'])
        //                         ->join('order_items', 'order_items.order_id', 'orders.id')
        //                         ->where('orders.user_id', auth()->id())
        //                         ->where('orders.payment_status', Order::PAYMENT_STATUS_COMPLETED)
        //                 )
        //                 ->paginate(10)
        //         ]
        //     ]
        // );
    }
}
