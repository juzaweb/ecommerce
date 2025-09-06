@extends('core::layouts.admin')

@section('content')
    <form action="{{ $action }}" class="form-ajax" method="post">
        @if($model->exists)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-12">
                <a href="{{ admin_url('product-categories') }}" class="btn btn-warning">
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

                        {{ Field::textarea($model, "{$locale}[description]", ['value' => $model->description, 'label' => __('Description')]) }}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <x-language-card :label="$model" :locale="$locale" />
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
