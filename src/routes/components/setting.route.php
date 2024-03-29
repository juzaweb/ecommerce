<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

use Juzaweb\Ecommerce\Http\Controllers\Backend\InventoryController;
use Juzaweb\Ecommerce\Http\Controllers\Backend\PaymentMethodController;
use Juzaweb\Ecommerce\Http\Controllers\Backend\SettingController;
use Juzaweb\Ecommerce\Http\Controllers\Backend\VariantController;

Route::get('ecommerce/settings', [SettingController::class, 'index'])->name('admin.ecommerce.setting');

Route::jwResource(
    'ecommerce/payment-methods',
    PaymentMethodController::class,
    [
        'name' => 'payment_methods'
    ]
);
