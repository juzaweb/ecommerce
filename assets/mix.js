const mix = require('laravel-mix');
const path = require('path');

mix.disableNotifications();
mix.version();

const baseAsset = path.dirname(__filename, '');
const basePublish = baseAsset + '/public';

mix.setResourceRoot(baseAsset);
mix.setPublicPath(basePublish);

mix.styles(
    [
        baseAsset + '/css/bootstrap.min.css',
        baseAsset + '/css/nprogress.css',
        baseAsset + '/css/select2-min.css',
        baseAsset + '/css/checkout.css',
    ],
    `css/checkout.min.css`
);

mix.combine(
    [
        baseAsset + '/js/bootstrap.min.js',
        baseAsset + '/js/twine.min.js',
        baseAsset + '/js/validator.min.js',
        baseAsset + '/js/nprogress.js',
        baseAsset + '/js/money-helper.js',
        baseAsset + '/js/select2-full-min.js',
        baseAsset + '/js/ua-parser.pack.js',
        baseAsset + '/js/checkout.js',
    ],
    `js/checkout.min.js`
);

mix.styles(
    [
        baseAsset + '/css/bootstrap.min.css',
        baseAsset + '/css/thankyou.css',
    ],
    `css/thankyou.min.css`
);

mix.combine(
    [
        baseAsset + '/js/bootstrap.min.js',
        baseAsset + '/js/twine.min.js',
        baseAsset + '/js/thankyou.js',
    ],
    `js/thankyou.min.js`
);
