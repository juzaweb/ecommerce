@extends('core::layouts.admin')

@section('content')
    <form action="{{ $action }}" class="form-ajax" method="post">
        @if($model->exists)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-12">
                <a href="{{ admin_url('products') }}" class="btn btn-warning">
                    <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                </a>

                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('Save') }}
                </button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Information') }}</h3>
                    </div>
                    <div class="card-body">
                        {{ Field::text($model, "{$locale}[name]", ['id' => 'name', 'value' => $model->name, 'label' => __('Name')]) }}

                        <div class="row">
                            <div class="col-md-6">
                                {{ Field::text($model, "price", ['value' => $variant->price, 'label' => __('Price')]) }}
                            </div>

                            <div class="col-md-6">
                                {{ Field::text($model, "compare_price", ['value' => $variant->compare_price, 'label' => __('Compare price')]) }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                {{ Field::text($model, "sku_code", ['value' => $variant->sku_code, 'label' => __('Sku code')]) }}
                            </div>

                            <div class="col-md-6">
                                {{ Field::text($model, "barcode", ['value' => $variant->barcode, 'label' => __('Barcode')]) }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                {{ Field::checkbox($model, 'inventory', ['value' => $model->inventory]) }}
                            </div>

                            <div class="col-md-8">
                                {{ Field::text($model, 'stock_quantity', ['value' => $variant->stock_quantity, 'label' => __('Stock quantity')]) }}
                            </div>
                        </div>

                        {{ Field::editor($model, "{$locale}[content]", ['value' => $model->content, 'label' => __('Content')]) }}
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        {{ Field::select($model, 'type', ['value' => $variant->type])
                            ->dropDownList(\Juzaweb\Modules\Ecommerce\Enums\VariantType::toArray()) }}

                        {{ Field::checkbox($model, 'downloadable', ['value' => $variant->downloadable]) }}
                    </div>
                </div>

                <x-seo-meta :model="$model" :locale="$locale" />
            </div>

            <div class="col-md-3">
                <x-language-card :label="$model" :locale="$locale" />

                <div class="card">
                    <div class="card-body">
                        {{ Field::select($model, 'status', ['value' => $model->status])
                            ->dropDownList(\Juzaweb\Modules\Ecommerce\Enums\ProductStatus::toArray()) }}
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        {{ Field::image(__('Thumbnail'), 'thumbnail', [
                        'value' => $model->getFirstMedia('thumbnail')->path
                        ]) }}
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {

        });
    </script>
@endsection
