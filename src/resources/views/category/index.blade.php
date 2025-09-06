@extends('core::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @can('product-categories.create')
                <a href="{{ admin_url('product-categories/create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> {{ __('Add Category') }}
                </a>
            @endcan
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            {{--@component('core::components.datatables.filters')
                <div class="col-md-3 jw-datatable_filters">

                </div>
            @endcomponent--}}
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Products') }}</h3>
                </div>
                <div class="card-body">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts() }}
@endsection
