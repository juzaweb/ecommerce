<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

use Juzaweb\Ecommerce\Http\Controllers\Frontend\OrderController;

Route::get('profile/ecommerce/{order:code}/download', [OrderController::class, 'download'])
    ->name('ecommerce.orders.download');
 