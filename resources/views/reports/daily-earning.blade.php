@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ 'Daily Earning Report' }}
    @parent
@stop


{{-- Page content --}}
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    <div class="form-inline filter_asset_by_date">
                        <div class="form-group">
                          <label for="start_date">Start Date</label>
                          <input type="date" id="start_date" class="form-control" name="start_date">
                        </div>
                        <div class="form-group">
                          <label for="end_date">End Date</label>
                          <input type="date" id="end_date" class="form-control" name="end_date">
                        </div>
                        <button id="export_button" class="btn btn-primary">Export</button>
                        <button id="reset_filters" class="btn btn-secondary">Reset</button>
                      </div>
                    <div class="table-responsive">

                        <table data-columns="{{ \App\Presenters\DailyEarningReportPresenter::dataTableLayout() }}"
                            data-cookie-id-table="dailyEarningReport" data-pagination="true" data-id-table="dailyEarningReport"
                            data-search="false" data-side-pagination="server" data-show-columns="true"
                            data-show-export="false" data-show-refresh="true" data-sort-order="asc" id="dailyEarningReport"
                            class="table table-striped snipe-table" data-url="{{ route('api.daily-earning.index') }}"
                            data-export-options='{
                        "fileName": "daily-earning-report-{{ date('Y-m-d') }}",
                        "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","icon"]
                        }'>
                        </table>

                    </div>
                </div>
            </div>
        </div>


    @stop

    @section('moar_scripts')
        @include ('partials.bootstrap-table')
        <script>
            $(document).ready(function() {
                $('#start_date, #end_date').on('change', function() {
                    var startDate = $('#start_date').val();
                    var endDate = $('#end_date').val();
                    $('#dailyEarningReport').bootstrapTable('refresh', {
                        query: {
                            start_date: startDate,
                            end_date: endDate
                        }
                    });
                });

                $('#reset_filters').on('click', function() {
                    $('#start_date').val('');
                    $('#end_date').val('');
                    $('#dailyEarningReport').bootstrapTable('refresh', {
                        query: {
                            start_date: '',
                            end_date: ''
                        }
                    });
                });

                $('#export_button').click(function(event) {
                    event.preventDefault();

                    var startDate = $('#start_date').val();
                    var endDate = $('#end_date').val();

                    $.ajax({
                        url: '{{ route('reports/export/daily-earning') }}',
                        method: 'GET',
                        data: {
                            start_date: startDate,
                            end_date: endDate,
                        },
                        xhrFields: {
                            responseType: 'blob' // Set the response type as blob
                        },
                        success: function(response, status, xhr) {
                            // Handling export success
                            var contentType = xhr.getResponseHeader('Content-Type');
                            if (contentType ===
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                                ) {
                                var blob = new Blob([response], {
                                    type: contentType
                                });
                                var link = document.createElement('a');
                                link.href = window.URL.createObjectURL(blob);
                                link.download = 'daily-earning-report.xlsx';
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);
                                window.URL.revokeObjectURL(link.href);
                            } else {
                                console.error('Unexpected content type:', contentType);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Export error:', textStatus, errorThrown);
                        }
                    });
                });
            });
        </script>
    @stop
