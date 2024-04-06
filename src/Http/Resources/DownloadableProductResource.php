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

use Illuminate\Http\Resources\Json\JsonResource;
use Juzaweb\Ecommerce\Models\Order;

class DownloadableProductResource extends JsonResource
{
    protected ?Order $order = null;

    public function withOrder(?Order $order): static
    {
        $this->order = $order;

        return $this;
    }

    public function toArray($request): array
    {
        $data = [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
        ];

        if ($this->resource->relationLoaded('downloadLinks')) {
            $data['download_links'] = DownloadLinkCollection::make($this->resource->downloadLinks)
                ->withOrder($this->order)
                ->resolve();
        }

        return $data;
    }
}
