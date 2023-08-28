<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Abstracts;

use Juzaweb\Backend\Models\Post;
use Juzaweb\Ecommerce\Models\Order;
use Juzaweb\Ecommerce\Models\PaymentMethod;
use Juzaweb\Ecommerce\Supports\PaymentMethodInterface;

abstract class PaymentMethodAbstract
{
    protected PaymentMethod $paymentMethod;

    protected bool $redirect = false;

    protected bool $successful = false;

    protected string $redirectURL = '';

    public function __construct(PaymentMethod $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    abstract public function purchase(array $params): PaymentMethodInterface;

    abstract public function completed(array $params): PaymentMethodInterface;

    public function isRedirect(): bool
    {
        return $this->redirect;
    }

    public function getRedirectURL(): null|string
    {
        if ($this->isRedirect()) {
            return $this->redirectURL;
        }

        return null;
    }

    public function getMessage(): string
    {
        return __('Thank you for your order.');
    }

    protected function setRedirectURL(string $url): void
    {
        $this->redirectURL = $url;
    }

    protected function setRedirect(bool $redirect): void
    {
        $this->redirect = $redirect;
    }
}
