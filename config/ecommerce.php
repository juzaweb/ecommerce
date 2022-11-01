<?php

return [
    /**
     * Cart Helper class support
     */
    'cart' => \Juzaweb\Ecommerce\Supports\DBCart::class,

    /**
     * Payment method supported
     */
    'payment_methods' => [
        'cod' => 'Cash on delivery',
        'paypal' => 'Paypal',
        'custom' => 'Custom',
    ],
];
