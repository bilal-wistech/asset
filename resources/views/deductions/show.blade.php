{{-- dd{{ $data }} --}}
@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ sprintf('%s %s', trans(' Deduction Details '), ' ') }}
    @parent
@stop

{{-- Page content --}}
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 200px;">Username</th>
                                <td>{{ $data->user->username ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Amount </th>
                                <td>{{ $data->amount ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;"> Previous Amount </th>
                                <td>{{ $data->previous_total ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Deduction Date</th>
                                <td>{{ \Carbon\Carbon::parse($data->deduction_date)->format('d-M-Y') ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Reason</th>
                                <td>{{ $data->reason ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Notes</th>
                                <td>{{ $data->note ?? '' }}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@stop

@section('moar_scripts')
    @include ('partials.bootstrap-table')
@stop
