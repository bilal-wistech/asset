@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('Salary Cash') }}
    @parent
@stop


@section('header_right')
    {{-- @can('create', \App\Models\Receipt::class) --}}
    <a href="{{ route('salary-cash.create') }}" class="btn btn-primary pull-right">
        Create Salary Cash</a>
    {{-- @endcan --}}
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
                                data-url="{{ route('api.salary-cash') }}">
                                <thead>
                                    <tr>
                                        <th data-sortable="true" data-field="id" data-visible="true">
                                            {{ trans('ID') }}</th>
                                        <th data-sortable="true" data-field="username" data-visible="true">
                                            {{ trans('Driver') }}</th>
                                        <th data-sortable="true" data-field="total_amount" data-visible="true">
                                            {{ trans('Amount') }}</th>
                                        <th data-sortable="true" data-field="salary_to_be_included_from_to" data-visible="true">
                                            {{ trans('Salary to be included') }}</th>
                                        <th data-sortable="true" data-field="added_by" data-visible="true">
                                            {{ trans('Created By') }}</th>
                                        <th data-sortable="true" data-field="date" data-visible="true">
                                            {{ trans('Cash Salary Date') }}</th>
                                        {{-- <th data-field="actions" data-sortable="false">
                                            {{ trans('Actions') }}</th> --}}
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
