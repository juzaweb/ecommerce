<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Ecommerce\Services;

use Juzaweb\Modules\Ecommerce\Models\Order;
use Juzaweb\Modules\Payment\Contracts\ModuleHandlerInterface;
use Juzaweb\Modules\Payment\Contracts\Paymentable;
use Juzaweb\Modules\Payment\Services\PaymentResult;

class EcommercePaymentHandler implements ModuleHandlerInterface
{
    /**
     * Process a purchase request.
     *
     * @param array $params
     * @return Paymentable
     */
    public function createOrder(array $params): Paymentable
    {
        // Implement the logic to handle the purchase request
        // For example, you might interact with a payment gateway here

        // Return a PurchaseResult instance with the result of the purchase
        return new Order();
    }

    public function success(Paymentable $paymentable, array $params): void
    {
        // Implement the logic to handle a successful payment

    }

    /**
     * Handle a failed payment.
     *
     * @param  Paymentable  $paymentable
     * @param  array  $params
     * @return void
     */
    public function fail(Paymentable $paymentable, array $params): void
    {
        // Implement the logic to handle a failed payment
        // This might involve logging the failure, notifying the user, etc.

        // Example: Log the failure
        \Log::error('Payment failed', ['params' => $params]);
    }

    public function cancel(Paymentable $paymentable, array $params): void
    {
        // TODO: Implement cancel() method.
    }
}
