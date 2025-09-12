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

Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
