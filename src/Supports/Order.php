<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/juzacms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Supports;

use Illuminate\Support\Collection;
use Juzaweb\Ecommerce\Models\Order as OrderModel;

class Order implements OrderInterface
{
    protected OrderModel $order;

    protected Payment $payment;

    public function __construct(
        OrderModel $order,
        Payment $payment
    ) {
        $this->order = $order;

        $this->payment = $payment;
    }

    public function purchase(): PaymentMethodInterface
    {
        return $this->payment->purchase(
            $this->order->paymentMethod,
            $this->getPurchaseParams()
        );
    }

    public function completed(?array $input): bool
    {
        $params = array_merge($this->getPurchaseParams(), $input);

        $completed = $this->payment->completed(
            $this->order->paymentMethod,
            $params
        );

        if ($completed->isSuccessful()) {
            $this->order->update(
                [
                    'payment_status' => OrderModel::PAYMENT_STATUS_COMPLETED
                ]
            );

            return true;
        }

        return false;
    }

    public function getPaymentRedirectURL(): string
    {
        $response = $this->purchase();

        return $response->getRedirectURL();
    }

    public function getOrder(): OrderModel
    {
        return $this->order;
    }

    public function getItems(): Collection
    {
        return $this->order->orderItems;
    }

    public function getPurchaseParams(): array
    {
        return [
            'amount' => $this->order->total,
            'currency' => get_config('ecom_currency', 'USD'),
            'cancelUrl' => $this->getCancelURL(),
            'returnUrl' => $this->getReturnURL(),
        ];
    }

    protected function getReturnURL(): string
    {
        return route('ajax', ['payment/completed'])
            . $this->getOrderUrlQuery();
    }

    protected function getCancelURL(): string
    {
        return route('ajax', ['payment/cancel'])
            . $this->getOrderUrlQuery();
    }

    protected function getOrderUrlQuery(): string
    {
        return '?order=' . $this->order->code . '&method='. $this->order->payment_method_id;
    }
}
