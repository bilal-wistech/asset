@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('Cash Handover') }}
    @parent
@stop
@section('header_right')
    @can('cash-handover.create')
        <a href="{{ route('cash-handover.create') }}" class="btn btn-primary pull-right">
            {{ trans('Create Cash Handover') }}</a>
    @endcan
@stop
{{-- Page content --}}
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    <!-- Table Section -->
                    <div class="table-responsive">
                        <table data-cookie-id-table="cashHandoverTable" data-pagination="true"
                            data-id-table="cashHandoverTable" data-search="true" data-side-pagination="server"
                            data-show-columns="true" data-show-fullscreen="true" data-show-export="true"
                            data-show-refresh="true" data-sort-order="asc" id="cashHandoverTable"
                            class="table table-striped snipe-table"
                            data-url="{{ route('api.cash-handover.cashHandover') }}">
                            <thead>
                                <tr>
                                    <th data-sortable="true" data-field="id" data-visible="true">
                                        {{ trans('Cash Handover ID') }}</th>
                                    <th data-sortable="true" data-field="handover_by" data-visible="true">
                                        {{ trans('Cash Handover By') }}</th>
                                    <th data-sortable="true" data-field="handover_to" data-visible="true">
                                        {{ trans('Cash Handover To') }}</th>
                                    <th data-sortable="true" data-field="handover_date" data-visible="true">
                                        {{ trans('Cash Handover Date') }}</th>
                                    <th data-sortable="true" data-field="total_amount" data-visible="true">
                                        {{ trans('Amount Handovered') }}</th>
                                    <th data-sortable="true" data-field="is_verified" data-visible="true">
                                        {{ trans('Verified') }}</th>
                                    <th data-field="actions" data-sortable="false">
                                        {{ trans('Actions') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('moar_scripts')
    @include ('partials.bootstrap-table')
@stop
