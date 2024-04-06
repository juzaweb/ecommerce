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
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;
use Juzaweb\CMS\Contracts\HookActionContract;
use Juzaweb\CMS\Http\Controllers\FrontendController;
use Juzaweb\Ecommerce\Http\Resources\OrderResource;
use Juzaweb\Ecommerce\Models\DownloadLink;
use Juzaweb\Ecommerce\Models\Order;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends FrontendController
{
    public function __construct(
        protected HookActionContract $hookAction
    ) {
    }

    public function download(Order $order): View|Factory|Response|string
    {
        abort_unless($order->isPaymentCompleted(), 403);

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

        $order->load([
            'downloadableProducts' => fn ($q) => $q
                ->with(['downloadLinks'])
                ->select(['posts.id', 'posts.title', 'posts.slug'])
        ]);

        return $this->view(
            'theme::profile.index',
            array_merge(
                compact('pages', 'page', 'title'),
                [
                    'order' => OrderResource::make($order)->resolve(),
                ]
            )
        );
    }

    public function doDownload(Order $order, string $token): StreamedResponse|RedirectResponse
    {
        abort_unless($order->isPaymentCompleted(), 403);

        $decode = decrypt(urldecode($token));

        if (Carbon::make($decode['expire_at'])?->isPast()) {
            abort(404, __('Download link has been expired'));
        }

        $downloadLink = DownloadLink::findOrFail($decode['id']);

        //$downloadLink->increment('download_count');

        if (is_url($downloadLink->url)) {
            return redirect()->to($downloadLink->url);
        }

        return Storage::disk('protected')->download($downloadLink->url);
    }
}
