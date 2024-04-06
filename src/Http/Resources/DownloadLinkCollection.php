<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Ecommerce\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Juzaweb\Ecommerce\Models\Order;

class DownloadLinkCollection extends ResourceCollection
{
    protected ?Order $order = null;

    public function withOrder(?Order $order): static
    {
        $this->order = $order;

        return $this;
    }

    public function toArray($request)
    {
        return $this->collection->map(
            fn ($item) => DownloadLinkResource::make($item)->withOrder($this->order)->resolve($request)
        )->toArray();
    }
}
