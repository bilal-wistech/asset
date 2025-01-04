@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('Deductions') }}
    @parent
@stop


@section('header_right')
    @can('create', \App\Models\Deduction::class)
        <a href="{{ route('deductions.create') }}" class="btn btn-primary pull-right">
            {{ trans('general.create') }}</a>
    @endcan
@stop

{{-- Page content --}}
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    <div class="table-responsive">

                        <table
                                data-columns="{{ \App\Presenters\DeductionPresenter::dataTableLayout() }}"
                                data-cookie-id-table="DeductionTable"
                                data-pagination="true"
                                data-id-table="DeductionTable"
                                data-search="true"
                                data-side-pagination="server"
                                data-show-columns="true"
                                data-show-fullscreen="true"
                                data-show-export="true"
                                data-show-refresh="true"
                                data-sort-order="asc"
                                id="DeductionTable"
                                class="table table-striped snipe-table"
                                data-url="{{ route('api.deduction.index') }}"
                                data-export-options='{
              "fileName": "export-asset-assignment-{{ date('Y-m-d') }}",
              "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","icon"]
              }'>
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

