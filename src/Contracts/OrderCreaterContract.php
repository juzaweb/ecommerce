<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Contracts;

use Juzaweb\CMS\Models\User;
use Juzaweb\Ecommerce\Models\Order;

interface OrderCreaterContract
{
    public function create(array $data, array $items, User $user): Order;
}
