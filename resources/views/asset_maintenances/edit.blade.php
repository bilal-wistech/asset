@extends('layouts/default')

{{-- Page title --}}
@section('title')
  @if ($item->id)
    {{ trans('admin/asset_maintenances/form.update') }}
  @else
    {{ trans('admin/asset_maintenances/form.create') }}
  @endif
  @parent
@stop


@section('header_right')
<a href="{{ URL::previous() }}" class="btn btn-primary pull-right">
  {{ trans('general.back') }}</a>
@stop


{{-- Page content --}}
@section('content')
<!-- Load jQuery (Make sure this is included before any other script that uses jQuery) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- Modal hidden by default --}}
<div class="modal fade" id="createModal2" tabindex="-1" role="dialog" aria-labelledby="createModal2Label" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h2 class="modal-title">{{ trans('admin/asset_maintenances/form.create_type') }}</h2>
          </div>
          <div class="modal-body">
              <form action="{{ route('api.asset_maintenance_type_create') }}" method="POST" id="asset_maintenance_form" onsubmit="return false">
                  @csrf
                  <div class="alert alert-danger" id="modal_error_msg" style="display:none"></div> 
                  <div class="dynamic-form-row">
                      <div class="col-md-4 col-xs-12"><label for="modal-name">{{ trans('general.name') }}:</label></div>
                      <div class="col-md-8 col-xs-12 required"><input type='text' name="name" id='modal-name' class="form-control"></div>
                  </div>
              </form>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('button.cancel') }}</button>
              <button type="button" class="btn btn-primary" id="modal-save2">{{ trans('general.save') }}</button>
          </div>
      </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal Structure -->
<div class="modal fade" id="createModal1" tabindex="-1" role="dialog" aria-labelledby="createModal1Label" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
              <h2 class="modal-title">{{ trans('admin/suppliers/table.create') }}</h2>
          </div>
          <div class="modal-body">
              <form action="{{ route('api.suppliers.store') }}" method="POST" id="supplier-form1" onsubmit="return false">
                  @csrf
                  <div class="alert alert-danger" id="modal_error_msg" style="display:none"></div>
                  <div class="dynamic-form-row">
                      <div class="col-md-4 col-xs-12"><label for="modal-name">{{ trans('general.name') }}:</label></div>
                      <div class="col-md-8 col-xs-12 required">
                          <input type="text" name="name" id="modal-name" class="form-control" required>
                      </div>
                  </div>
              </form>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('button.cancel') }}</button>
              <button type="button" class="btn btn-primary" id="modal-save1">{{ trans('general.save') }}</button>
          </div>
      </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="row">
  <div class="col-md-9">
    @if ($item->id)
      <form class="form-horizontal" method="post" action="{{ route('maintenances.update', $item->id) }}" autocomplete="off">
      {{ method_field('PUT') }}
    @else
      <form class="form-horizontal" method="post" action="{{ route('maintenances.store') }}" autocomplete="off">
    @endif
    <!-- CSRF Token -->
    {{ csrf_field() }}

    <div class="box box-default">
      <div class="box-header with-border">
        <h2 class="box-title">
          @if ($item)
          {{ $item->name }}
          @endif
        </h2>
      </div><!-- /.box-header -->

      <div class="box-body">
        @include ('partials.forms.edit.asset-select', ['translated_name' => trans('admin/hardware/table.asset_tag'), 'fieldname' => 'asset_id', 'required' => 'true'])
        @include ('partials.forms.edit.supplier-select', ['translated_name' => trans('general.supplier'), 'fieldname' => 'supplier_id', 'required' => 'true'])
        @include ('partials.forms.edit.maintenance_type')

        <!-- Title -->
        <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
          <label for="title" class="col-md-3 control-label">
            {{ trans('admin/asset_maintenances/form.title') }}
          </label>
          <div class="col-md-7{{  (Helper::checkIfRequired($item, 'title')) ? ' required' : '' }}">
            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', $item->title) }}" />
            {!! $errors->first('title', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
          </div>
        </div>

        <!-- Start Date -->
        <div class="form-group {{ $errors->has('start_date') ? ' has-error' : '' }}">
          <label for="start_date" class="col-md-3 control-label">{{ trans('admin/asset_maintenances/form.start_date') }}</label>

          <div class="input-group col-md-3{{  (Helper::checkIfRequired($item, 'start_date')) ? ' required' : '' }}">
            <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd"  data-autoclose="true" data-date-clear-btn="true">
              <input type="text" class="form-control" placeholder="{{ trans('general.select_date') }}" name="start_date" id="start_date" value="{{ old('start_date', $item->start_date) }}">
              <span class="input-group-addon"><i class="fas fa-calendar" aria-hidden="true"></i></span>
            </div>
            {!! $errors->first('start_date', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
          </div>
        </div>



        <!-- Completion Date -->
        <div class="form-group {{ $errors->has('completion_date') ? ' has-error' : '' }}">
          <label for="start_date" class="col-md-3 control-label">{{ trans('admin/asset_maintenances/form.completion_date') }}</label>

          <div class="input-group col-md-3{{  (Helper::checkIfRequired($item, 'completion_date')) ? ' required' : '' }}">
            <div class="input-group date" data-date-clear-btn="true" data-provide="datepicker" data-date-format="yyyy-mm-dd"  data-autoclose="true">
              <input type="text" class="form-control" placeholder="{{ trans('general.select_date') }}" name="completion_date" id="completion_date" value="{{ old('completion_date', $item->completion_date) }}">
              <span class="input-group-addon"><i class="fas fa-calendar" aria-hidden="true"></i></span>
            </div>
            {!! $errors->first('completion_date', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
          </div>
        </div>

        <!-- Warranty -->
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-9">
            <div class="checkbox">
              <label>
                <input type="checkbox" value="1" name="is_warranty" id="is_warranty" {{ Request::old('is_warranty', $item->is_warranty) == '1' ? ' checked="checked"' : '' }} class="minimal"> {{ trans('admin/asset_maintenances/form.is_warranty') }}
              </label>
            </div>
          </div>
        </div>

        <!-- Asset Maintenance Cost -->
        <div class="form-group {{ $errors->has('cost') ? ' has-error' : '' }}">
          <label for="cost" class="col-md-3 control-label">{{ trans('admin/asset_maintenances/form.cost') }}</label>
          <div class="col-md-2">
            <div class="input-group">
              <span class="input-group-addon">
                @if (($item->asset) && ($item->asset->location) && ($item->asset->location->currency!=''))
                  {{ $item->asset->location->currency }}
                @else
                  {{ $snipeSettings->default_currency }}
                @endif
              </span>
              <input class="col-md-2 form-control" type="text" name="cost" id="cost" value="{{ old('cost', Helper::formatCurrencyOutput($item->cost)) }}" />
              {!! $errors->first('cost', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
            </div>
          </div>
        </div>

        <!-- Notes -->
        <div class="form-group {{ $errors->has('notes') ? ' has-error' : '' }}">
          <label for="notes" class="col-md-3 control-label">{{ trans('admin/asset_maintenances/form.notes') }}</label>
          <div class="col-md-7">
            <textarea class="col-md-6 form-control" id="notes" name="notes">{{ old('notes', $item->notes) }}</textarea>
            {!! $errors->first('notes', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
          </div>
        </div>
      </div> <!-- .box-body -->

      <div class="box-footer text-right">
        <button type="submit" class="btn btn-success"><i class="fas fa-check icon-white" aria-hidden="true"></i> {{ trans('general.save') }}</button>
      </div>
    </div> <!-- .box-default -->
    </form>
  </div>
</div>

  <script>
  jQuery(document).ready(function () {
    $('.select2').select2();
    jQuery('#modal-save1').click(function () {
      //alert("ok");
      var form = jQuery('#supplier-form1');
      var formData = form.serialize(); // Get form data

      jQuery.ajax({
        url: form.attr('action'),
        method: 'POST',
        data: formData,
        headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
          jQuery('#createModal1').modal('hide');
          //alert('Supplier created successfully');
        },
        error: function (xhr) {
          var errors = xhr.responseJSON.errors;
          jQuery('#modal_error_msg').html('<ul></ul>').show();
          jQuery.each(errors, function (key, value) {
            jQuery('#modal_error_msg ul').append('<li>' + value + '</li>');
          });
        }
      });
    });
  });

  jQuery(document).ready(function () {
    jQuery('#modal-save2').click(function () {
      //alert("ok");
      var form = jQuery('#asset_maintenance_form');
      var formData = form.serialize(); // Get form data

      jQuery.ajax({
        url: form.attr('action'),
        method: 'POST',
        data: formData,
        headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
          jQuery('#createModal2').modal('hide');
          //alert('Supplier created successfully');
        },
        error: function (xhr) {
          var errors = xhr.responseJSON.errors;
          jQuery('#modal_error_msg').html('<ul></ul>').show();
          jQuery.each(errors, function (key, value) {
            jQuery('#modal_error_msg ul').append('<li>' + value + '</li>');
          });
        }
      });
    });
  });


</script>

@stop
