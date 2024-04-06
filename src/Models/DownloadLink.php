<?php

namespace Juzaweb\Ecommerce\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Juzaweb\CMS\Models\Model;
use Juzaweb\CMS\Traits\UseUUIDColumn;
use Juzaweb\Network\Traits\Networkable;

/**
 * Juzaweb\Ecommerce\Models\DownloadLink
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $url
 * @property int $site_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $product_id
 * @property int $variant_id
 * @method static Builder|DownloadLink newModelQuery()
 * @method static Builder|DownloadLink newQuery()
 * @method static Builder|DownloadLink query()
 * @method static Builder|DownloadLink whereCreatedAt($value)
 * @method static Builder|DownloadLink whereId($value)
 * @method static Builder|DownloadLink whereName($value)
 * @method static Builder|DownloadLink whereProductId($value)
 * @method static Builder|DownloadLink whereSiteId($value)
 * @method static Builder|DownloadLink whereUpdatedAt($value)
 * @method static Builder|DownloadLink whereUrl($value)
 * @method static Builder|DownloadLink whereUuid($value)
 * @method static Builder|DownloadLink whereVariantId($value)
 * @mixin Eloquent
 */
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

    public function generateDownloadToken(Order $order): string
    {
        $token = encrypt(
            [
                'id' => $this->id,
                'url' => $this->url,
                'expire_at' => now()->addHour()->format('Y-m-d H:i:s'),
                'order_code' => $order->code,
            ]
        );

        return urlencode($token);
    }

    public function getDownloadUrl(Order $order): string
    {
        return route('ecommerce.orders.do-download', [$order->code, $this->generateDownloadToken($order)]);
    }
}
