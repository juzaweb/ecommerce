const mix = require('laravel-mix');
const path = require('path');

mix.disableNotifications();
mix.version();

const baseAsset = path.dirname(__filename, '');
const basePublish = 'modules/ecommerce/assets/public';

mix.setPublicPath(basePublish);


