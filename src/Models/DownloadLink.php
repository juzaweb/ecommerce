<?php

namespace Juzaweb\Ecommerce\Models;

use Juzaweb\CMS\Models\Model;
use Juzaweb\CMS\Traits\UseUUIDColumn;
use Juzaweb\Network\Traits\Networkable;

class DownloadLink extends Model
{
    use Networkable, UseUUIDColumn;

    protected $table = 'ecom_download_links';

    protected $fillable = [
        'product_id',
        'variant_id',
        'name',
        'url',
    ];
}
