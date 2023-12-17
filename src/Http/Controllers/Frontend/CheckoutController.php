<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Http\Controllers\Frontend;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Juzaweb\Backend\Events\Users\RegisterSuccessful;
use Juzaweb\CMS\Events\EmailHook;
use Juzaweb\CMS\Http\Controllers\FrontendController;
use Juzaweb\CMS\Models\User;
use Juzaweb\Ecommerce\Contracts\CartManagerContract;
use Juzaweb\Ecommerce\Contracts\OrderManagerContract;
use Juzaweb\Ecommerce\Events\OrderSuccess;
use Juzaweb\Ecommerce\Events\PaymentSuccess;
use Juzaweb\Ecommerce\Http\Requests\CheckoutRequest;
use Juzaweb\Ecommerce\Models\Order;

class CheckoutController extends FrontendController
{
    protected CartManagerContract $cartManager;

    protected OrderManagerContract $orderManager;

    public function __construct(
        CartManagerContract $cartManager,
        OrderManagerContract $orderManager
    ) {
        $this->cartManager = $cartManager;
        $this->orderManager = $orderManager;
    }

    /**
     * @throws \Throwable
     */
    public function checkout(CheckoutRequest $request): JsonResponse|RedirectResponse
    {
        $cart = $this->cartManager->find();

        if ($cart->isEmpty()) {
            return $this->error(
                [
                    'message' => __('Cart is empty.'),
                ]
            );
        }

        DB::beginTransaction();
        try {
            $user = $this->getOrderUser($request);

            $newOrder = $this->orderManager->createByCart(
                $cart,
                $request->all(),
                $user
            );

            $cart->remove();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        event(new OrderSuccess($newOrder, $user));

        $params = apply_filters(
            'ecom_checkout_success_email_params',
            [
                'name' => $user->name,
                'email' => $user->email,
                'order_code' => $newOrder->getOrder()->code,
            ],
            $user,
            $newOrder->getOrder()
        );

        event(
            new EmailHook(
                'checkout_success',
                [
                    'to' => $user->email,
                    'params' => $params,
                ]
            )
        );

        try {
            $purchase = $newOrder->purchase();

            $redirect = $purchase->isRedirect() ?
                $purchase->getRedirectURL() :
                    $this->getThanksPageURL($newOrder->getOrder());

            return $this->success(
                [
                    'redirect' => $redirect,
                    'message' => trans('ecom::content.order_thanks'),
                ]
            );
        } catch (\Exception $e) {
            report($e);

            return $this->error(
                [
                    'redirect' => $this->getThanksPageURL($newOrder->getOrder()),
                    'message' => 'Cannot get payment url.',
                ]
            );
        }
    }

    public function cancel(Request $request): RedirectResponse
    {
        $order = Order::findByCode($request->input('order'));

        return redirect()->to($this->getThanksPageURL($order));
    }

    public function completed(Request $request): RedirectResponse
    {
        $helper = $this->orderManager->find($request->input('order'));
        $order = $helper->getOrder();

        if ($order->isPaymentCompleted()) {
            return redirect()->to($this->getThanksPageURL($order));
        }

        if ($helper?->completed($request->all())) {
            $params = apply_filters(
                'ecom_payment_success_email_params',
                [
                    'name' => $helper->getOrder()?->user->name,
                    'email' => $helper->getOrder()?->user->email,
                    'order_code' => $helper->getOrder()->code,
                ],
                $order?->user,
                $order
            );

            if ($order?->user->email) {
                event(
                    new EmailHook(
                        'payment_success',
                        [
                            'to' => $order->user->email,
                            'params' => $params,
                        ]
                    )
                );
            }

            event(new PaymentSuccess($order));
        }

        return redirect()->to($this->getThanksPageURL($order));
    }

    protected function getOrderUser(Request $request): User
    {
        global $jw_user;

        if ($jw_user) {
            return $jw_user;
        }

        $email = $request->input('email');
        if ($user = User::whereEmail($email)->first()) {
            return $user;
        }

        $password = Hash::make(Str::random());
        $user = new User();
        $user->fill(
            [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
            ]
        );

        $user->setAttribute('password', $password);
        $user->save();

        event(new RegisterSuccessful($user));

        return $user;
    }

    protected function getThanksPageURL(Order $order): string
    {
        if (!$thanksPage = get_config('ecom_thanks_page')) {
            return '/';
        }

        $thanksPage = get_page_url($thanksPage);

        if (empty($thanksPage)) {
            return '/';
        }

        return "{$thanksPage}/{$order->token}";
    }
}
