<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Supports\Payments;

use Juzaweb\Ecommerce\Abstracts\PaymentMethodAbstract;
use Juzaweb\Ecommerce\Supports\PaymentMethodInterface;
use Omnipay\Common\GatewayInterface;
use Omnipay\Omnipay;

class Paypal extends PaymentMethodAbstract implements PaymentMethodInterface
{
    public function purchase(array $params): PaymentMethodInterface
    {
        $gateway = $this->getGateway();

        $response = $gateway->purchase($params)->send();

        $this->setRedirect($response->isRedirect());

        $this->setRedirectURL($response->getRedirectUrl());

        return $this;
    }

    public function completed(array $params): PaymentMethodInterface
    {
        $gateway = $this->getGateway();

        $params['payerId'] = $params['PayerID'];

        $params['transactionReference'] = $params['paymentId'];

        unset($params['token']);

        $response = $gateway->completePurchase($params)->send();

        $this->successful = $response->isSuccessful();

        return $this;
    }

    public function isSuccessful(): bool
    {
        return $this->successful;
    }

    private function getGateway(): GatewayInterface
    {
        $gateway = Omnipay::create('PayPal_Rest');

        $gateway->initialize($this->createGetwayData());

        return $gateway;
    }

    private function createGetwayData(): array
    {
        if ($this->paymentMethod->data['mode'] == 'live') {
            $clientId = $this->paymentMethod->data['live_client_id'];
            $secret = $this->paymentMethod->data['live_secret'];
            $testMode = false;
        } else {
            $clientId = $this->paymentMethod->data['sandbox_client_id'];
            $secret = $this->paymentMethod->data['sandbox_secret'];
            $testMode = true;
        }

        return [
            'clientId' => $clientId,
            'secret'   => $secret,
            'testMode' => $testMode,
        ];
    }
}
