<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/juzacms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce;

use Illuminate\Support\Arr;
use Juzaweb\CMS\Abstracts\Action;
use Juzaweb\CMS\Facades\HookAction;
use Juzaweb\Ecommerce\Contracts\CartManagerContract;
use Juzaweb\Ecommerce\Http\Controllers\Frontend\CartController;
use Juzaweb\Ecommerce\Http\Controllers\Frontend\CheckoutController;
use Juzaweb\Ecommerce\Http\Resources\OrderResource;
use Juzaweb\Ecommerce\Http\Resources\PaymentMethodCollectionResource;
use Juzaweb\Ecommerce\Models\Order;
use Juzaweb\Ecommerce\Models\PaymentMethod;
use Juzaweb\Ecommerce\Models\ProductVariant;

class EcommerceAction extends Action
{
    public function handle()
    {
        $this->addAction(
            Action::INIT_ACTION,
            [$this, 'registerPostTypes']
        );

        $this->addAction(
            Action::BACKEND_INIT,
            [$this, 'addAdminMenu']
        );

        $this->addAction(
            Action::INIT_ACTION,
            [$this, 'registerConfigs']
        );

        $this->addAction(
            "post_type.products.after_save",
            [$this, 'saveDataProduct'],
            20,
            2
        );

        $this->addFilter(
            'theme.get_view_page',
            [$this, 'addCheckoutPage'],
            20,
            2
        );

        $this->addFilter(
            'theme.get_params_page',
            [$this, 'addCheckoutParams'],
            20,
            2
        );

        $this->addAction(
            Action::FRONTEND_CALL_ACTION,
            [$this, 'registerFrontendAjax']
        );
    }

    public function registerPostTypes()
    {
        HookAction::registerPostType(
            'products',
            [
                'label' => trans('ecom::content.products'),
                'menu_icon' => 'fa fa-shopping-cart',
                'menu_position' => 10,
                'supports' => [
                    'category',
                    'tag'
                ],
                'metas' => [
                    'price' => [
                        'label' => trans('ecom::content.price'),
                        'type' => 'text',
                    ],
                    'compare_price' => [
                        'label' => trans('ecom::content.compare_price'),
                        'type' => 'text',
                    ],
                    'sku_code' => [
                        'label' => trans('ecom::content.sku_code'),
                        'type' => 'text',
                    ],
                    'barcode' => [
                        'label' => trans('ecom::content.barcode'),
                        'type' => 'text',
                    ],
                    'images' => [
                        'label' => trans('ecom::content.images'),
                        'type' => 'images',
                    ]/*,
                    'quantity' => [
                        'type' => 'text',
                        'visible' => true,
                    ],
                    'inventory_management' => [
                        'type' => 'text',
                        'visible' => true,
                    ],
                    'disable_out_of_stock' => [
                        'type' => 'text',
                        'visible' => true,
                    ]*/
                ]
            ]
        );

        HookAction::registerTaxonomy(
            'brands',
            'products',
            [
                'label' => trans('ecom::content.brands'),
                'menu_position' => 11,
            ]
        );

        HookAction::registerTaxonomy(
            'vendors',
            'products',
            [
                'label' => trans('ecom::content.vendors'),
                'menu_position' => 12,
            ]
        );
    }

    public function registerConfigs()
    {
        HookAction::registerConfig(
            [
                'ecom_checkout_page',
                'ecom_thanks_page',
            ]
        );
    }

    public function addAdminMenu()
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
            'ecommerce.inventories',
            [
                'title' => trans('ecom::content.inventories'),
                'menu' => [
                    'icon' => 'fa fa-indent',
                    'position' => 15,
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

    public function addFormProduct($model)
    {
        $variant = ProductVariant::findByProduct($model->id);

        echo e(
            view(
                'ecom::backend.product.form',
                compact(
                    'variant',
                    'model'
                )
            )
        );
    }

    public function parseDataForSave($data)
    {
        $metas = (array) $data['meta'];
        if ($metas['price']) {
            $metas['price'] = parse_price_format($metas['price']);
        }

        if ($metas['compare_price']) {
            $metas['compare_price'] = parse_price_format($metas['compare_price']);
        }

        $metas['inventory_management'] = $metas['inventory_management'] ?? 0;
        $metas['disable_out_of_stock'] = $metas['disable_out_of_stock'] ?? 0;
        if ($metas['quantity']) {
            $metas['quantity'] = (int) $metas['quantity'];
            $metas['quantity'] = $metas['quantity'] > 0 ? $metas['quantity'] : 0;
        }

        $data['meta'] = $metas;
        return $data;
    }

    public function saveDataProduct($model, $data): void
    {
        if (Arr::has($data, 'meta')) {
            $variant = ProductVariant::findByProduct($model->id);
            $variantData = $data['meta'];
            $variantData['title'] = 'Default';
            $variantData['names'] = ['Default'];
            $variantData['post_id'] = $model->id;

            $productVariant = ProductVariant::updateOrCreate(
                ['id' => $variant->id ?? 0],
                $variantData
            );

            $model->setMeta('variants', [$productVariant->toArray()]);
        }
    }

    public function addCheckoutPage($view, $page): string
    {
        $checkoutPage = get_config('ecom_checkout_page');
        $thanksPage = get_config('ecom_thanks_page');

        if ($checkoutPage == $page->id) {
            $cart = app(CartManagerContract::class)->find();
            if ($cart->isEmpty()) {
                return redirect('/')->send();
            }

            return 'ecom::frontend.checkout.index';
        }

        if ($thanksPage == $page->id) {
            return 'ecom::frontend.checkout.thankyou';
        }

        return $view;
    }

    public function addCheckoutParams($params, $page)
    {
        $checkoutPage = get_config('ecom_checkout_page');
        $thanksPage = get_config('ecom_thanks_page');

        if ($checkoutPage == $page->id) {
            $methods = PaymentMethod::active()->get();

            $params['payment_methods'] = (new PaymentMethodCollectionResource($methods))->toArray(request());
        }

        if ($thanksPage == $page->id) {
            $orderToken = request()->segment(2);

            $order = Order::findByToken($orderToken);

            $params['order'] = (new OrderResource($order))->toArray(request());
        }

        return $params;
    }

    public function registerFrontendAjax()
    {
        HookAction::registerFrontendAjax(
            'checkout',
            [
                'callback' => [CheckoutController::class, 'checkout'],
                'method' => 'POST',
            ]
        );

        HookAction::registerFrontendAjax(
            'cart.add-to-cart',
            [
                'callback' => [CartController::class, 'addToCart'],
                'method' => 'POST',
            ]
        );

        HookAction::registerFrontendAjax(
            'cart.get-items',
            [
                'callback' => [CartController::class, 'getCartItems'],
            ]
        );

        HookAction::registerFrontendAjax(
            'payment.cancel',
            [
                'callback' => [CheckoutController::class, 'cancel'],
            ]
        );

        HookAction::registerFrontendAjax(
            'payment.completed',
            [
                'callback' => [CheckoutController::class, 'completed'],
            ]
        );
    }
}
