@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('Cash Adjustment') }}
    @parent
@stop


@section('header_right')
    @can('create', \App\Models\CashAdjustment::class)
        <a href="{{ route('cash-adjustments.create') }}" class="btn btn-primary pull-right">
            Create Cash Adjustment</a>
    @endcan
@stop

{{-- Page content --}}
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    <div class="table-responsive">
                        <div class="table-responsive">
                            <table data-cookie-id-table="CashAdjustmentTable" data-pagination="true"
                                data-id-table="CashAdjustmentTable" data-search="true" data-side-pagination="server"
                                data-show-columns="true" data-show-fullscreen="true" data-show-export="true"
                                data-show-refresh="true" data-sort-order="asc" id="CashAdjustmentTable"
                                class="table table-striped snipe-table"
                                data-url="{{ route('api.cash-adjustments.index') }}">
                                <thead>
                                    <tr>
                                        <th data-sortable="true" data-field="id" data-visible="false">
                                            {{ trans('ID') }}</th>
                                        <th data-sortable="true" data-field="driver_id" data-visible="true">
                                            {{ trans('Driver') }}</th>
                                        <th data-sortable="true" data-field="user_id" data-visible="true">
                                            {{ trans('User') }}</th>
                                        <th data-sortable="true" data-field="amount" data-visible="true">
                                            {{ trans('Amount') }}</th>
                                        <th data-sortable="true" data-field="created_at" data-visible="true">
                                            {{ trans('Created At') }}</th>
                                        <th data-sortable="true" data-field="updated_at" data-visible="false">
                                            {{ trans('Updated At') }}</th>
                                        <th data-field="actions" data-sortable="false">
                                            {{ trans('Actions') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>

    @stop

    @section('moar_scripts')
        @include ('partials.bootstrap-table')
    @stop
