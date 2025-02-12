@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Riding Companies
    @parent
@stop


@section('header_right')
    @can('create', \App\Models\RidingCompany::class)
        <a href="{{ route('riding-companies.create') }}" class="btn btn-primary pull-right">
            {{ trans('Create') }}</a>
    @endcan
@stop




{{-- Page content --}}
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    <div class="table-responsive">

                        {{-- <table data-columns="{{ \App\Presenters\RidingCompanyPresenter::dataTableLayout() }}"
                            data-cookie-id-table="RidingCompanyTable" data-pagination="true"
                            data-id-table="RidingCompanyTable" data-search="true" data-side-pagination="server"
                            data-show-columns="true" data-show-fullscreen="true" data-show-export="true"
                            data-show-refresh="true" data-sort-order="asc" id="RidingCompanyTable"
                            class="table table-striped snipe-table" data-url="{{ route('api.riding-companies.index') }}"
                            data-export-options='{
              "fileName": "export-asset-assignment-{{ date('Y-m-d') }}",
              "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","icon"]
              }'>
                        </table> --}}

                        <table data-cookie-id-table="RidingCompanyTable" data-pagination="true"
                            data-id-table="RidingCompanyTable" data-search="true" data-side-pagination="server"
                            data-show-columns="true" data-show-fullscreen="true" data-show-export="true"
                            data-show-refresh="true" data-sort-order="asc" id="RidingCompanyTable"
                            class="table table-striped snipe-table" data-url="{{ route('api.riding-companies.index') }}">
                            <thead>
                                <tr>
                                    <th data-sortable="true" data-field="id" data-visible="false">
                                        {{ trans('ID') }}</th>
                                    <th data-sortable="true" data-field="name" data-visible="true">
                                        {{ trans('Name') }}</th>
                                    <th data-sortable="true" data-field="status" data-visible="true">
                                        {{ trans('status') }}</th>
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
