@php
    /**
     * @var \Juzaweb\Backend\Models\Post $model
     * @var \Juzaweb\Ecommerce\Models\ProductVariant $variant
     */

    $downloadLinks = \Juzaweb\Ecommerce\Models\DownloadLink::where(['variant_id' => $variant->id])->get();
@endphp

<div class="card">
    <div class="card-header">
        <div class="d-flex flex-column justify-content-center">
            <h5 class="mb-0">{{ __('Product Info') }}</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                {{ Field::text(trans('ecom::content.price'), 'meta[price]', [
                    'value' => $variant->price ? number_format($variant->price) : '',
                    'class' => 'is-number number-format',
                    'prefix' => '$'
                ]) }}
            </div>

            <div class="col-md-6">
                {{ Field::text(trans('ecom::content.compare_price'), 'meta[compare_price]', [
                    'value' => $variant->compare_price ? number_format($variant->compare_price) : '',
                    'class' => 'is-number number-format',
                    'prefix' => '$'
                ]) }}
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                {{ Field::text(trans('ecom::content.sku_code'), 'meta[sku_code]', [
                    'value' => $variant->sku_code ?? '',
                ]) }}
            </div>

            <div class="col-md-6">
                {{ Field::text(trans('ecom::content.barcode'), 'meta[barcode]', [
                    'value' => $variant->barcode ?? '',
                ]) }}
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                {{ Field::checkbox(
                    trans('ecom::content.inventory_management'),
                    'meta[inventory_management]',
                    [
                        'checked' => $model->getMeta('inventory_management') == 1,
                    ]
                ) }}

                {{ Field::text(trans('ecom::content.quantity'), 'meta[quantity]', [
                    'value' => $model->getMeta('quantity') ?? '',
                    'class' => 'is-number number-format'
                ]) }}

                {{ Field::checkbox(
                    trans('ecom::content.allows_ordering_out_of_stock'),
                    'meta[disable_out_of_stock]',
                    [
                        'checked' => $model->getMeta('disable_out_of_stock') == 1,
                    ]
                ) }}
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="d-flex flex-column justify-content-center">
            <h5 class="mb-0">{{ __('Advanced') }}</h5>
        </div>
    </div>

    <div class="card-body">
        {{ Field::checkbox(trans('Downloadable'), 'meta[downloadable]', ['checked' => $model->getMeta('downloadable') == 1]) }}

        <div class="row download-links-box form-repeater">

            <div class="col-md-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th class="w-25">{{ trans('File Name') }}</th>
                        <th>{{ trans('File Url') }}</th>
                    </tr>
                    </thead>
                    <tbody class="repeater-items">
                    @foreach($downloadLinks as $downloadLink)
                        @component(
                            'ecom::backend.product.components.download-link-item',
                            ['marker' => $downloadLink->id, 'item' => $downloadLink]
                        )

                        @endcomponent
                    @endforeach

                    </tbody>
                </table>
            </div>

            <div class="col-md-12">
                <button type="button" class="btn btn-primary btn-sm add-repeater-item">
                    {{ trans('cms::app.add_repeater_item', ['label' => trans('Download Links')]) }}
                </button>
            </div>

            <script type="text/html" class="repeater-item-template">
                @component('ecom::backend.product.components.download-link-item', ['marker' => '{marker}'])

                @endcomponent
            </script>
        </div>

    </div>
</div>

{{ Field::images(trans('ecom::content.images'), 'meta[images]', [
    'value' => $model->getMeta('images', []),
]) }}