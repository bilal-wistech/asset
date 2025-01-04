@extends('layouts/default')
{{-- Page title --}}
@section('title')
Daily Earning Report

@parent
@stop
@section('header_right')
<button class="btn btn-primary collectids pull-right"  style="margin-right: 160px; margin-bottom:16px;" onclick="collectRecords();">Collect Records</button>

<form id="notificationForm" action="{{route('array.users')}}" method="post">
    @csrf
    <input type="hidden" name="highlightedrecordsinput" id="highlightedrecordsinput"/>
    <button class="btn btn-primary pull-right" type="submit" style="margin-right: 5px;margin-bottom:10px; margin-top:-50px;">Send Notification</button>
</form>
@stop
{{-- Page content --}}
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-default">
      <div class="box-body">
        
      </div>
      @php $highlightedRowCount = 0; @endphp

    <div class="table-responsive">
    <table class="table snipe-table">
    <thead>
        <tr>
            @foreach ($records[0] as $header => $value)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
    @foreach ($records as $record)
            @php $lastColumnValue = end($record); @endphp
            <tr style="@if ($lastColumnValue > $setting->cash_limit) background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); @php $highlightedRowCount++; @endphp @endif">
                @foreach ($record as $value)
                    <td>{{ $value }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
</div>
<div style="width:fit-content; ">
<p style="font-size:large; text-align:center; font-weight:bold;  ">Users whose cash limit is exceeded: {{ $highlightedRowCount }}</p>
</div>


      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div>
</div>
@stop
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
    console.log('document is being ready');
    // Array to store highlighted records
    var userslist = [];

    // Function to collect highlighted records
    function collectRecords() {
        var cashLimit = <?php echo json_encode($setting->cash_limit); ?>;
        $('.snipe-table tbody tr').each(function() {
            if ($(this).find('td:last').text() > cashLimit ) {
                var record = [];
                $(this).find('td').each(function() {
                    record.push($(this).text());
                });
                userslist.push(record);
            }
        });
        console.log(userslist);
        document.getElementById('highlightedrecordsinput').value = JSON.stringify(userslist);
        console.log(document.getElementById('highlightedrecordsinput').value);
    }
    $(document).on('click', '.collectids', function() {
        collectRecords();
    });
});
</script>
@section('moar_scripts')
@stop