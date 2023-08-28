<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Supports;

use Illuminate\Support\Collection;
use Juzaweb\Ecommerce\Models\Order;

interface OrderInterface
{
    public function purchase(): PaymentMethodInterface;

    public function completed(?array $input): bool;

    public function getItems(): Collection;

    public function getOrder(): Order;

    public function getPaymentRedirectURL(): string;
}
