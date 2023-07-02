<?php

require __DIR__ . '/components/setting.route.php';

use Juzaweb\Ecommerce\Http\Controllers\Backend\OrderController;

Route::jwResource(
    'ecommerce/orders',
    OrderController::class,
    [
        'name' => 'orders'
    ]
);
