<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Ecommerce\Http\Controllers\Frontend;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Inertia\Response;
use Juzaweb\CMS\Contracts\HookActionContract;
use Juzaweb\CMS\Http\Controllers\FrontendController;
use Juzaweb\Ecommerce\Models\Order;

class OrderController extends FrontendController
{
    public function __construct(
        protected HookActionContract $hookAction
    ) {
    }

    public function download(Order $order): View|Factory|Response|string
    {
        $pages = $this->hookAction->getProfilePages()
            ->where('show_menu', true)
            ->map(function ($item) {
                $item['active'] = $item['slug'] === 'ecommerce/orders';
                return $item;
            })
            ->toArray();

        $title = __('Download').": #{$order->code}";

        $page = [
            'title' => $title,
            'contents' => 'ecom::frontend.profile.orders.download',
        ];

        return $this->view(
            'theme::profile.index',
            compact('order', 'pages', 'page', 'title')
        );
    }
}
