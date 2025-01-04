@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('general.regrid') }}
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
    <div class="container-fluid" style="padding-right: 60px;">
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
    </div>
@stop

{{-- Page content --}}
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    <div class="table-responsive">
                        <table data-columns="{{ \App\Presenters\ExpencePresenter::dataTableLayout() }}"
                            data-cookie-id-table="expenceTable" data-pagination="true" data-id-table="expenceTable"
                            data-search="false" data-side-pagination="server" data-show-columns="true"
                            data-show-fullscreen="true" data-show-export="false" data-show-refresh="true"
                            data-sort-order="asc" id="expenceTable" class="table table-striped snipe-table"
                            data-url="{{ url('api/show_data') }}">
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
            function refreshTable() {
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();
                var userId = $('#user_id').val();

                $('#expenceTable').bootstrapTable('refreshOptions', {
                    queryParams: function(params) {
                        return {
                            offset: params.offset,
                            limit: params.limit, // Include pagination limits in the request
                            start_date: startDate,
                            end_date: endDate,
                            user_id: userId || '', // Send empty string if user_id is not selected
                        };
                    }
                });
            }


            function resetFilters() {
                $('#start_date').val('');
                $('#end_date').val('');
                $('#user_id').val('').trigger('change'); // Reset and trigger change for select2
                //refreshTable();

            }

            function initializeBootstrapTable() {
                $('.snipe-table').bootstrapTable({
                    classes: 'table table-responsive table-no-bordered',
                    ajaxOptions: {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    },
                    stickyHeader: true,
                    locale: '{{ config('app.locale') }}',
                    stickyHeaderOffsetY: '0px',
                    undefinedText: '',
                    iconsPrefix: 'fa',
                    cookie: true,
                    cookieExpire: '2y',
                    mobileResponsive: true,
                    maintainSelected: true,
                    trimOnSearch: false,
                    pagination: true, // Enable pagination
                    sidePagination: 'server', // Use server-side pagination

                    showSearchClearButton: true,
                    paginationFirstText: "First",
                    paginationLastText: "Last",
                    paginationPreText: "Previous",
                    paginationNextText: "Next",
                    pageList: ['10', '20', '30', '50', '100', '150', '200', '500'],
                    pageSize: 20,
                    paginationVAlign: 'both',
                    formatLoadingMessage: function() {
                        return '<h2><i class="fas fa-spinner fa-spin" aria-hidden="true"></i> Loading... please wait.... </h4>';
                    },

                    icons: {
                        advancedSearchIcon: 'fas fa-search-plus',
                        paginationSwitchDown: 'fa-caret-square-o-down',
                        paginationSwitchUp: 'fa-caret-square-o-up',
                        fullscreen: 'fa-expand',
                        columns: 'fa-columns',
                        refresh: 'fas fa-sync-alt',
                        export: 'fa-download',
                        clearSearch: 'fa-times'
                    },
                    queryParams: function(params) {
                        // Pass pagination and other params to the server
                        return {
                            offset: params.offset,
                            limit: params.limit,
                            search: params.searchText,
                            start_date: $('#start_date').val(),
                            end_date: $('#end_date').val(),
                            user_id: $('#user_id').val()
                        };
                    },
                    onLoadSuccess: function() {
                        $('[data-toggle="tooltip"]').tooltip();
                    },
                    url: "{{ url('api/show_data') }}"
                });
            }

            $('#start_date, #end_date, #user_id').on('change', refreshTable);


            $('#export_button').click(function(event) {
                event.preventDefault();

                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();
                var userId = $('#user_id').val();

                var searchQuery = $('#expenceTable').bootstrapTable('getOptions').queryParams.search;

                $.ajax({
                    url: '{{ route('re_expense.export') }}',
                    method: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        user_id: userId,
                        search: searchQuery
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(response, status, xhr) {
                        var contentType = xhr.getResponseHeader('Content-Type');
                        if (contentType ===
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        ) {
                            var blob = new Blob([response], {
                                type: contentType
                            });
                            var link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = 'reimmensible-expense.xlsx';
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

            $('#reset_button').click(function(event) {
                event.preventDefault();
                resetFilters();
            });

            initializeBootstrapTable();
            // Check for existing filter values and refresh the table if they exist
            if ($('#start_date').val() || $('#end_date').val() || $('#user_id').val()) {
                refreshTable();
            }
        });
        $(document).on('click', '.approve-btn, .disapprove-btn', function() {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();
            var userId = $('#user_id').val();
            var expenseId = $(this).data('id'); // Get the expense ID from the button
            var isApprove = $(this).hasClass('approve-btn');

            // Get the current offset and limit from the bootstrap table
            var options = $('#expenseTable').bootstrapTable('getOptions');
            var pageSize = options.pageSize; // Number of entries per page
            var pageNumber = options.pageNumber; // Current page number

            // Calculate the offset
            var offset = (pageNumber - 1) * pageSize;

            // Construct the AJAX URL
            var url = isApprove ? `/approve/${expenseId}` : `/disapprove/${expenseId}`;

            // Send AJAX request
            $.ajax({
                url: url,
                method: 'POST', // Ensure your routes accept POST requests
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    user_id: userId,
                    offset: offset,
                    limit: pageSize,
                    _token: '{{ csrf_token() }}' // Include CSRF token
                },
                success: function(response) {
                    // Assuming response contains 'old_request' to append values to the form
                    if (response.old_request) {
                        $('#start_date').val(response.old_request.start_date);
                        $('#end_date').val(response.old_request.end_date);
                        $('#user_id').val(response.old_request.user_id).trigger(
                        'change'); // Trigger change to update select2
                    }

                    // Refresh the table
                    refreshTable();
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    alert('An error occurred while processing your request.');
                }
            });
        });

        $(document).ready(function() {
            $('#export_pdf_button').click(function(e) {
                e.preventDefault(); 
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();
                var userId = $('#user_id').val();
                
                if (!startDate && !endDate && !userId) {
            alert('Please select a date or user before Export Pdf!');
            return; 
        }

                $.ajax({
                    url: '{{ route('expence.show_data') }}', 
                    type: 'GET', 
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        export_pdf: 'true',
                        start_date: startDate,
                        end_date: endDate,
                        user_id: userId,
                    },
                    xhrFields: {
                        responseType: 'blob' 
                    },
                    success: function(blob) {
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = 'expenses.pdf'; 
                        link.click(); 
                        window.URL.revokeObjectURL(link.href);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>

    @include ('partials.bootstrap-table', [
        'exportFile' => 'expence-export',
        'search' => false,
        'columns' => \App\Presenters\ExpencePresenter::dataTableLayout(),
    ])
@stop
