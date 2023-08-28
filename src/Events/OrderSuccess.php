<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Events;

use Juzaweb\CMS\Models\User;
use Juzaweb\Ecommerce\Models\Order;
use Juzaweb\Ecommerce\Supports\OrderInterface;

class OrderSuccess
{
    public Order $order;
    public User $user;

    public function __construct(Order|OrderInterface $order, User $user)
    {
        if ($order instanceof OrderInterface) {
            $this->order = $order->getOrder();
        } else {
            $this->order = $order;
        }

        $this->user = $user;
    }
}
