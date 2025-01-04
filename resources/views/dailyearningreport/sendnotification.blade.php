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
                <h2><strong>Users who have been notified:</strong></h2>
                    <ol style="list-style: none;">
                        @foreach ($details as $detail)
                        <li><h4> <i class="fa-solid fa-square-check" style="color:green; font-size: 20px; "></i> &nbsp &nbsp &nbsp {{ $detail['first_name'] }} {{ $detail['last_name'] }}</h4></li>
                        @endforeach
                    </ol>
                    <h2><strong>Users who are not notified as they are not in Database:</strong></h2>
                    <ul style="list-style: none;">
                        @foreach ($notindb as $name)
                            <li><h4> <i class="fa-solid fa-square-xmark" style="color:red; font-size: 20px; "></i> &nbsp &nbsp &nbsp {{ $name }}</h4></li>
                        @endforeach
                    </ul> 
     </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div>
</div>
@stop
@section('moar_scripts')
@stop