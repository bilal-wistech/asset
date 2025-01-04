@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Update Cash Limit
    @parent
@stop

@section('header_right')
    <a href="{{ route('settings.index') }}" class="btn btn-primary"> {{ trans('general.back') }}</a>
@stop


{{-- Page content --}}
@section('content') 

    <style>
        .checkbox label {
            padding-right: 40px;
        }
    </style>


    {{ Form::open(['method' => 'POST', 'files' => false, 'autocomplete' => 'off', 'class' => 'form-horizontal', 'role' => 'form' ]) }}
    <!-- CSRF Token -->
    {{csrf_field()}}

    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            <div class="panel box box-default">
                <div class="box-header with-border">
                    <h2 class="box-title">
                        <i class="fas fa-euro"></i> Cash Limit
                    </h4>
                </div>
                <div class="box-body">
                    <div class="col-md-11 col-md-offset-1">                        
                        <div class="form-group {{ $errors->has('cash_limit') ? 'error' : '' }}">
                            <div class="col-md-5">
                                {{ Form::label('cash_limit', 'Enter Amount:' )}}
                            </div>
                            <div class="col-md-7">
                                {{ Form::text('cash_limit', old('cash_limit', $setting->cash_limit), array('class' => 'form-control', 'style'=>'width: 150px;', 'aria-label'=>'cash_limit')) }}
                                {!! $errors->first('cash_limit', '<span class="alert-msg" aria-hidden="true">:message</span>') !!}
                            </div>
                        </div>
                    </div>

                </div> <!--/.box-body-->
                <div class="box-footer">
                    <div class="text-left col-md-6">
                        <a class="btn btn-link text-left" href="{{ route('settings.index') }}">{{ trans('button.cancel') }}</a>
                    </div>
                    <div class="text-right col-md-6">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-check icon-white" aria-hidden="true"></i> {{ trans('general.save') }}</button>
                    </div>

                </div>
            </div> <!-- /box -->
        </div> <!-- /.col-md-8-->
    </div> <!-- /.row-->

    {{Form::close()}}

@stop
