

@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ trans('general.asset_insurance') }}
@parent
@stop


@section('header_right')
    @can('create', \App\Models\Insurance::class)
        <a href="{{ route('insurance.create') }}" class="btn btn-primary pull-right">
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
            data-columns="{{ \App\Presenters\InsurancePresenter::dataTableLayout() }}"
            data-cookie-id-table="insuranceTable"
            data-pagination="true"
            data-id-table="insuranceTable"
            data-search="true"
            data-side-pagination="server"
            data-show-columns="true"
            data-show-fullscreen="true"
            data-show-export="true"
            data-show-refresh="true"
            data-sort-order="asc"
            id="insuranceTable"
            class="table table-striped snipe-table"
            data-url="{{ route('api.insurance.index', ['target' => $request->target]) }}"
            data-export-options='{
              "fileName": "export-insurance-{{ date('Y-m-d') }}",
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

