@extends('layouts/default')
{{-- Page title --}}
@section('title')
Daily Earning Report
@parent
@stop
{{-- Page content --}}
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-default">
      <div class="box-body">
        <div class=" row form-group {{ $errors->has((isset($fieldname) ? $fieldname : 'dereport')) ? 'has-error' : '' }}">
            <form action="{{ route('dailyearningreport.csvfilecontent') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="col-md-9" style="display:inline-block;">
                <input type="file" id="{{ (isset($fieldname) ? $fieldname : 'dereport') }}" name="{{ (isset($fieldname) ? $fieldname : 'dereport') }}" aria-label="{{ (isset($fieldname) ? $fieldname : 'dereport') }}"  class="sr-only">
                <label class="btn btn-default" aria-hidden="true">
                    Browse file to import...
                    <input type="file"  name="{{ (isset($fieldname) ? $fieldname : 'dereport') }}" class="js-uploadFile" id="uploadFile" data-maxsize="{{ Helper::file_upload_max_size() }}" accept=".csv" style="display:none; max-width: 90%" aria-label="{{ (isset($fieldname) ? $fieldname : 'image') }}" aria-hidden="true">
                </label>
                <span class='label label-default' id="uploadFile-info"></span>
                
                <p class="help-block" id="uploadFile-status">Accepted File Type is ".csv"</p>
                {!! $errors->first('dereport', '<span class="alert-msg" aria-hidden="true">:message</span>') !!}
                <!-- <span id="#spanforerrorofimage" style="color:red; display:none;"> Image Field is required</span> -->
                <button type="submit" class="btn btn-primary" >Import</button>
            </div>
            </form>
        </div>
      </div>
        
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div>
</div>
@stop
@section('moar_scripts')
@stop