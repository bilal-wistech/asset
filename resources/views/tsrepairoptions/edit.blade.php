@extends('layouts/edit-form', [
    'createText' => "Add Repair Options" ,
    'updateText' => "Edit Repair Options",
    'helpPosition'  => 'right',
    'topSubmit'  => 'false',
    'formAction' => (isset($item->id)) ? route('tsrepairoptions.updateData', ['id' => $item->id]) : route('tsrepairoptions.store'),
])

@section('inputFields')
  <!-- title -->
  <div class="form-group ">
  {{ Form::label('name', "Repair Options", array('class' => 'col-md-3 control-label')) }}
  <div class="col-md-8">
    <input class="form-control" type="text" name="name" aria-label="name" id="name"
    value="{{ $item->name ? $item->name : '' }}"/>
    {!! $errors->first('name', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
  </div>
  </div>
@endsection
