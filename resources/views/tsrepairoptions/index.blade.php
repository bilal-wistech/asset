@extends('layouts/default')

{{-- Page title --}}
@section('title')
Repair Options
@parent
@stop


@section('header_right')
<a href="{{ route('tsrepairoptions.create') }}" class="btn btn-primary pull-right">
  {{ trans('general.create') }}</a>
@stop

{{-- Page content --}}
@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="box box-default">
      <div class="box-body">
        <div class="table-responsive">

        <table
            data-columns="{{ \App\Presenters\RepairOptionsPresenter::dataTableLayout() }}"
            data-cookie-id-table="RepairOptionsTable"
            data-pagination="true"
            data-id-table="RepairOptionsTable"
            data-search="true"
            data-side-pagination="server"
            data-show-columns="true"
            data-show-fullscreen="true"
            data-show-export="true"
            data-show-refresh="true"
            data-sort-order="asc"
            id="RepairOptionsTable"
            class="table table-striped snipe-table"
            data-url="{{ route('api.repairoptions.index') }}"
            >
          </table>
        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div>
</div>

@stop

@section('moar_scripts')
  @include ('partials.bootstrap-table',
      ['exportFile' => 'category-export',
      'search' => true,
      'columns' => \App\Presenters\RepairOptionsPresenter::dataTableLayout()
  ])
@stop

