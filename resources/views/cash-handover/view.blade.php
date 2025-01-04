@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ sprintf('%s %s', trans('Cash Handover Verification for Adjustment: ADJ - '), $receipt->id) }}
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
                                <th style="width: 200px;">Handovered Amount</th>
                                <td>{{ number_format($handover->total_amount, 2) ?? '0.00' }}</td>
                            </tr>
                            <tr>
                                <th>Handovered By</th>
                                <td>{{ $handover->handoverByUser->username ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Handovered To</th>
                                <td>{{ $handover->handoverToUser->username ?? 'N/A' }}</td>
                            </tr>
                            @if (Auth::user()->id == $handover->handover_to ||
                                    Auth::user()->isSuperUser() ||
                                    Auth::user()->can('cash-handover.verifiy'))
                                <tr>
                                    @if ($handover->is_verified == 0)
                                        <td>
                                            <div style="display: flex; gap: 10px; align-items: center;">
                                                <form action="{{ route('cash-handover.verifiy') }}" method="post"
                                                    style="margin: 0;">
                                                    @csrf
                                                    <input type="hidden" name="cash_handover_id"
                                                        value="{{ $handover->id }}">
                                                    <input type="hidden" name="verified_by" value="{{ Auth::user()->id }}">
                                                    <input type="hidden" name="receipt_id" value="{{ $receipt->id }}">
                                                    <input type="hidden" name="status" value="1">
                                                    <button type="submit" class="btn btn-success">Verify</button>
                                                </form>
                                            </div>
                                        </td>
                                    @else
                                        <td>
                                            <span class="label label-success">Verified</span>
                                        </td>
                                    @endif
                                </tr>
                            @endif
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
