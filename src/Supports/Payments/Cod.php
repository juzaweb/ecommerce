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

class Cod extends PaymentMethodAbstract implements PaymentMethodInterface
{
    public function purchase(array $data): PaymentMethodInterface
    {
        return $this;
    }

    public function isRedirect(): bool
    {
        return false;
    }
}
