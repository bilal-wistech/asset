@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ trans('general.type_of_expence') }}
@parent
@stop


@section('header_right')
@can('create', \App\Models\TypeOfExpence::class)
<a href="{{ route('expence.create') }}" class="btn btn-primary pull-right">
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
            data-columns="{{ \App\Presenters\ExpenceTypePresenter::dataTableLayout() }}"
            data-cookie-id-table="ExpenseTypeTable"
            data-pagination="true"
            data-id-table="ExpenseTypeTable"
            data-search="true"
            data-side-pagination="server"
            data-show-columns="true"
            data-show-fullscreen="true"
            data-show-export="true"
            data-show-refresh="true"
            data-sort-order="asc"
            id="ExpenseTypeTable"
            class="table table-striped snipe-table"
            data-url="{{ route('api.type.index') }}"
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
      'columns' => \App\Presenters\ExpenceTypePresenter::dataTableLayout()
  ])
@stop

