<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Juzaweb\Modules\Ecommerce\Http\Controllers\CartController;
use Juzaweb\Modules\Ecommerce\Http\Controllers\CheckoutController;

Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
Route::delete('cart/{itemId}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('checkout/{cartId}', [CheckoutController::class, 'index'])->name('checkout');
Route::post('checkout/{cartId}', [CheckoutController::class, 'index']);

Route::get('invoices/{orderId}', [CheckoutController::class, 'thankyou'])->name('checkout.thankyou');
