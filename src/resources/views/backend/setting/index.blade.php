@extends('cms::layouts.backend')

@section('content')
    <form method="post" action="{{ route('admin.setting.save') }}" class="form-ajax">
        @component('cms::components.tabs', [
            'tabs' => [
                'general' => trans('cms::app.general'),
                'shop' => trans('ecom::content.shop'),
            ]
        ])
            @slot('tab_general')
                @include('ecom::backend.setting.components.setting.general')
            @endslot

            @slot('tab_shop')
                @include('ecom::backend.setting.components.setting.shop')
            @endslot
        @endcomponent

        <button type="submit" class="btn btn-success">{{__('Save Changes')}}</button>
    </form>
@endsection
