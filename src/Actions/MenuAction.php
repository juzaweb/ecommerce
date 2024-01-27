<?php

namespace Juzaweb\Ecommerce\Actions;

use Juzaweb\CMS\Abstracts\Action;
use Juzaweb\CMS\Facades\HookAction;

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
                'contents' => 'ecom::frontend.profile.orders',
                'icon' => 'shopping-cart',
                'data' => [
                    //
                ]
            ]
        );
    }
}
