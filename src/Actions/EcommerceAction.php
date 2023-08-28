<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Actions;

use Illuminate\Database\Eloquent\Model;
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

        $this->addAction(
            'post_type.products.form.left',
            [$this, 'addFormProduct']
        );

        $this->addFilter(
            'post_type.products.parseDataForSave',
            [$this, 'parseDataForSave']
        );

        $this->addAction(
            Action::INIT_ACTION,
            [$this, 'registerEmailHooks']
        );

        $this->addFilter(
            'frontend.post_type.products.detail.data',
            [$this, 'addVariantsProductDetail']
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

    public function addFormProduct($model)
    {
        $variant = ProductVariant::findByProduct($model->id);
        if (empty($variant)) {
            $variant = new ProductVariant();
        }

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
            $metas['quantity'] = max($metas['quantity'], 0);
        }

        $data['meta'] = $metas;
        return $data;
    }

    public function saveDataProduct($model, $data): void
    {
        if (Arr::has($data, 'meta')) {
            $variant = ProductVariant::findByProduct($model->id);
            $variantData = $data['meta'];
            $variantData['thumbnail'] = $data['thumbnail'];
            $variantData['description'] = seo_string(strip_tags($data['content']), 320);

            if ($variant) {
                $variant->update($variantData);
            } else {
                $variantData['title'] = 'Default';
                $variantData['names'] = ['Default'];
                $variantData['post_id'] = $model->id;

                $variant = ProductVariant::updateOrCreate(
                    ['id' => $variant->id ?? 0],
                    $variantData
                );
            }
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
            'cart.remove-item',
            [
                'callback' => [CartController::class, 'removeItem'],
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

    public function registerEmailHooks()
    {
        $this->hookAction->registerEmailHook(
            'checkout_success',
            [
                'label' => 'Checkout success',
                'params' => [
                    'name' => 'User order name',
                    'email' => 'User order email',
                    'order_code' => 'Order code',
                ],
            ]
        );

        $this->hookAction->registerEmailHook(
            'payment_success',
            [
                'label' => 'Payment success',
                'params' => [
                    'name' => 'User order name',
                    'email' => 'User order email',
                    'order_code' => 'Order code',
                ],
            ]
        );
    }

    public function addVariantsProductDetail(array $data): array
    {
        $data['post']['metas']['variants'] = ProductVariant::cacheFor(
            config('juzaweb.performance.query_cache.lifetime')
        )
            ->wherePostId($data['post']['id'])
            ->get()
            ->toArray();
        return $data;
    }
}
