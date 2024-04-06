<?php

namespace Juzaweb\Ecommerce\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Juzaweb\Ecommerce\Models\DownloadLink;
use Juzaweb\Ecommerce\Models\Order;

/**
 * @property-read DownloadLink $resource
 */
class DownloadLinkResource extends JsonResource
{
    protected ?Order $order = null;

    public function withOrder(?Order $order): static
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $data = [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
        ];

        if (isset($this->order)) {
            $data['download_url'] = $this->resource->getDownloadUrl($this->order);
        }

        return $data;
    }
}
