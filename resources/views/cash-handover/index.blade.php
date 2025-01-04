@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('Cash Handover') }}
    @parent
@stop
@section('header_right')
    <a href="{{ route('cash-handover.create') }}" class="btn btn-primary pull-right">
        {{ trans('Create Handover') }}</a>
@stop
{{-- Page content --}}
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    <!-- Table Section -->
                    <div class="table-responsive">
                        {{-- <table data-cookie-id-table="cashHandoverTable" data-pagination="true"
                            data-id-table="cashHandoverTable" data-search="true" data-side-pagination="server"
                            data-show-columns="true" data-show-fullscreen="true" data-show-export="true"
                            data-show-refresh="true" data-sort-order="asc" id="cashHandoverTable"
                            class="table table-striped snipe-table" data-url="{{ route('api.cash-handover.index') }}">
                            <thead>
                                <tr>
                                    <th data-field="state" data-checkbox="true" data-formatter="stateFormatter"></th>
                                    <th data-sortable="true" data-field="id" data-visible="true">
                                        {{ trans('Adjustment ID') }}</th>
                                    <th data-sortable="true" data-field="username" data-visible="true">
                                        {{ trans('Username') }}</th>
                                    <th data-sortable="true" data-field="date" data-visible="true">
                                        {{ trans('Adjustment Date') }}</th>
                                    <th data-sortable="true" data-field="total_amount" data-visible="true">
                                        {{ trans('Amount Paid') }}</th>
                                    <th data-field="actions" data-formatter="actionsFormatter" data-sortable="false">
                                        {{ trans('Actions') }}</th>
                                </tr>
                            </thead>
                        </table> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('moar_scripts')
    @include ('partials.bootstrap-table')
@stop
