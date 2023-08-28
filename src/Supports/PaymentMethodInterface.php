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

interface PaymentMethodInterface
{
    public function purchase(array $params): PaymentMethodInterface;

    public function completed(array $params): PaymentMethodInterface;

    public function isSuccessful(): bool;

    public function isRedirect(): bool;

    public function getRedirectURL(): null|string;

    public function getMessage(): string;
}
