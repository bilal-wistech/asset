@extends('layouts/edit-form', [
    'createText' => trans('general.type_of_expence') ,
    'updateText' => trans('general.type_of_expence'),
    'helpPosition'  => 'right',
    'topSubmit'  => 'false',
    'formAction' => (isset($item->id)) ? route('expence.updateData', ['id' => $item->id]) : route('expence.store'),
])

@section('inputFields')

                         <!-- title -->
                      <div class="form-group ">
                      {{ Form::label('name', trans('general.type_of_expence'), array('class' => 'col-md-3 control-label')) }}
                      <div class="col-md-8">
                        <input class="form-control" type="text" name="name" aria-label="name" id="name"
                               value="{{ $item->title ? $item->title : '' }}"/>
                        {!! $errors->first('name', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                      </div>
                    </div>




@endsection


