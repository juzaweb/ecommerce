<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Supports\Manager;

use Juzaweb\CMS\Models\User;
use Juzaweb\Ecommerce\Contracts\CartContract;
use Juzaweb\Ecommerce\Contracts\OrderManagerContract;
use Juzaweb\Ecommerce\Models\Order;
use Juzaweb\Ecommerce\Supports\Creaters\OrderCreater;
use Juzaweb\Ecommerce\Supports\OrderInterface;
use Juzaweb\Ecommerce\Supports\Payment;

class OrderManager implements OrderManagerContract
{
    protected OrderCreater $orderCreater;

    protected Payment $payment;

    public function __construct(
        OrderCreater $orderCreater,
        Payment $payment
    ) {
        $this->orderCreater = $orderCreater;
        $this->payment = $payment;
    }

    public function find(Order|string|int $order): null|OrderInterface
    {
        if ($order instanceof Order) {
            return $this->createOrder($order);
        }

        $model = Order::findByCode($order);

        return $model ? $this->createOrder($model) : null;
    }

    public function createByCart(
        CartContract $cart,
        array $data,
        User $user
    ): OrderInterface {
        return $this->createByItems(
            $data,
            $cart->getItems(),
            $user
        );
    }

    public function createByItems(array $data, array $items, User $user): OrderInterface
    {
        $order = $this->orderCreater->create($data, $items, $user);

        return $this->createOrder($order);
    }

    protected function createOrder(Order $order): OrderInterface
    {
        return new \Juzaweb\Ecommerce\Supports\Order(
            $order,
            $this->payment
        );
    }
}
