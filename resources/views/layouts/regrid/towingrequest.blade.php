@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Towing Requests
    @parent
@stop

@section('header_right')
    <style>
        .select2-container .select2-selection--single {
            height: 34px !important;
        }

        .select2-container--default .select2-selection--single {
            border: 1px solid #ccc !important;
            border-radius: 0px !important;
        }
    </style>

    @php
        $selectedUserId = request()->input('user_id');
    @endphp
    {{-- <div class="container-fluid" style="padding-right: 60px;">
        <div class="row">
            <form class="col-12 row">

                <div class="col-md-4 mb-3">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" class="form-control" name="start_date"
                        value="{{ old('start_date', $old_request['start_date'] ?? '') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" class="form-control" name="end_date"
                        value="{{ old('end_date', $old_request['end_date'] ?? '') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Select</label>
                    <select class="form-control select2" id="user_id" name="user_id">
                        <option value="">Select</option>
                        @foreach ($users as $names)
                            <option value="{{ $names->id }}"
                                {{ old('user_id', $old_request['user_id'] ?? '') == $names->id ? 'selected' : '' }}>
                                {{ $names->username }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 text-right">
                <button id="export_pdf_button" class="btn btn-primary pr-30">Export PDF</button>
                <button id="export_button" class="btn btn-primary pr-30">Export</button>
                <button id="reset_button" class="btn btn-secondary pl-30">Reset</button>
            </div>
        </div>
        <br>
    </div> --}}
@stop

{{-- Page content --}}
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    <div class="table-responsive">
                        <table data-columns="{{ \App\Presenters\TowingPresenter::dataTableLayout() }}"
                            data-cookie-id-table="TowingTable" data-pagination="true" data-id-table="TowingTable"
                            data-search="false" data-side-pagination="server" data-show-columns="true"
                            data-show-fullscreen="true" data-show-export="false" data-show-refresh="true"
                            data-sort-order="asc" id="TowingTable" class="table table-striped snipe-table"
                            data-url="{{ url('api/show_towing_data') }}">
                        </table>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
@stop

@section('moar_scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.approved-btn', function() {
                var assetId = $(this).data('id');
                $.ajax({
                    url: '/approve-towing-request',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        asset_id: assetId
                    },
                    success: function(response) {
                        alert('Towing request updated successfully!');
                        $('.approved-btn[data-id="' + assetId + '"]').closest('td').html('');
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred: ' + error);
                    }
                });
            });
        });
    </script>

    @include ('partials.bootstrap-table', [
        'exportFile' => 'expence-export',
        'search' => false,
        'columns' => \App\Presenters\TowingPresenter::dataTableLayout(),
    ])
@stop
