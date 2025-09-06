<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

use Juzaweb\Core\Facades\RouteResource;
use Juzaweb\Modules\Ecommerce\Http\Controllers\CategoryController;
use Juzaweb\Modules\Ecommerce\Http\Controllers\ProductController;

RouteResource::admin('products', ProductController::class);
RouteResource::admin('product-categories', CategoryController::class);
