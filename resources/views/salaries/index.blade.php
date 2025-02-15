@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('Salaries') }}
    @parent
@stop


@section('header_right')
    @can('create', \App\Models\Salary::class)
        <a href="{{ route('salaries.create') }}" class="btn btn-primary pull-right">
            Create Salary</a>
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
                            <table data-cookie-id-table="SalaryTable" data-pagination="true" data-id-table="SalaryTable"
                                data-search="true" data-side-pagination="server" data-show-columns="true"
                                data-show-fullscreen="true" data-show-export="true" data-show-refresh="true"
                                data-sort-order="asc" id="SalaryTable" class="table table-striped snipe-table"
                                data-url="{{ route('api.salaries.index') }}">
                                <thead>
                                    <tr>
                                        <th data-sortable="true" data-field="id" data-visible="false">
                                            {{ trans('ID') }}</th>
                                        <th data-sortable="true" data-field="driver" data-visible="true">
                                            {{ trans('Driver') }}</th>
                                        <th data-sortable="true" data-field="base_salary" data-visible="true">
                                            {{ trans('Salary') }}</th>
                                        <th data-sortable="true" data-field="total_amount_paid" data-visible="true">
                                            {{ trans('Total Amount') }}</th>
                                        <th data-sortable="true" data-field="from_date" data-visible="true">
                                            {{ trans('From Date') }}</th>
                                        <th data-sortable="true" data-field="to_date" data-visible="true">
                                            {{ trans('To Date') }}</th>
                                        <th data-sortable="true" data-field="user_id" data-visible="true">
                                            {{ trans('Created By') }}</th>
                                        <th data-sortable="true" data-field="created_at" data-visible="false">
                                            {{ trans('Created At') }}</th>
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
