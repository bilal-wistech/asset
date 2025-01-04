@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('Create Cash Handover') }}
    @parent
@stop

{{-- Page content --}}
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Form Section - Below Table -->
            <div id="handover-form" {{-- style="display: none;" --}} class="mt-4">
                <div class="box box-default">
                    <div class="box-body">
                        <form id="cash-handover-form">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="handover-by">Cash Handover by</label>
                                        <input type="text" class="form-control" id="handover-by"
                                            value="{{ Auth::user()->username }}" readonly>
                                        <input type="hidden" id="handover-by-id" value="{{ Auth::user()->id }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="handover-to">Handover to</label>
                                        <select class="form-control select2" id="handover-to" required>
                                            <option value="">Select User</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->username ?? '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="total-amount">Handover Date</label>
                                        <input type="date" class="form-control" id="handover-date">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="total-amount">Total Amount</label>
                                        <input type="text" class="form-control" id="total-amount" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="box box-default">

                <div class="box-body">
                    <!-- Table Section -->

                    <div class="table-responsive">
                        <table data-cookie-id-table="cashHandoverTable" data-pagination="true"
                            data-id-table="cashHandoverTable" data-search="true" data-side-pagination="server"
                            data-show-columns="true" data-show-fullscreen="true" data-show-export="true"
                            data-show-refresh="true" data-sort-order="asc" id="cashHandoverTable"
                            class="table table-striped snipe-table" data-url="{{ route('api.cash-handover.index') }}">
                            <thead>
                                <tr>
                                    <th data-field="state" data-checkbox="true" data-formatter="stateFormatter"></th>
                                    <th data-sortable="true" data-field="id" data-visible="true">
                                        {{ trans('Adjustment ID') }}</th>
                                    <th data-sortable="true" data-field="username" data-visible="true">
                                        {{ trans('Username') }}</th>
                                    <th data-sortable="true" data-field="date" data-visible="true">
                                        {{ trans('Adjustment Date') }}</th>
                                    <th data-sortable="true" data-field="total_amount" data-visible="true">
                                        {{ trans('Amount Paid') }}</th>
                                    {{-- <th data-field="actions" data-formatter="actionsFormatter" data-sortable="false">
                                        {{ trans('Actions') }}</th> --}}
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('moar_scripts')
    {{-- PNotify JS Files --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.2.1/pnotify.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.2.1/pnotify.buttons.js"></script>

    {{-- Initialize PNotify defaults --}}
    <script>
        PNotify.defaults.styling = 'bootstrap3';
        PNotify.defaults.delay = 3000; // Default delay of 3 seconds
    </script>
    @include ('partials.bootstrap-table')

    <script>
        $(document).ready(function() {
            var $table = $('#cashHandoverTable');
            var selectedRows = new Set(); // Using Set to track selected rows
            var totalAmount = 0;

            // Initialize select2 for better dropdown experience
            $('#handover-to').select2({
                width: '100%',
                placeholder: 'Select User'
            });

            // Formatter for the state (checkbox) column
            window.stateFormatter = function(value, row) {
                if (row.handed_over) {
                    return {
                        disabled: true,
                        checked: false
                    };
                }
                return {
                    disabled: false,
                    checked: false
                };
            };

            // Formatter for the actions column
            // window.actionsFormatter = function(value, row) {
            //     if (row.handed_over) {
            //         return `<a href="/cash-handover/${row.id.replace('ADJ-', '')}/view" 
        //     class="btn btn-sm btn-info" 
        //     title="View Details">
        //     <i class="fa fa-eye"></i>
        // </a>`;
            //     }
            //     return '';
            // };

            // Function to update total amount
            function updateTotalAmount(row, isChecked) {
                const amount = parseFloat(row.total_amount) || 0;

                if (isChecked) {
                    totalAmount += amount;
                    selectedRows.add(row.id);
                } else {
                    totalAmount -= amount;
                    selectedRows.delete(row.id);
                }

                // Update form total amount
                $('#total-amount').val(totalAmount.toFixed(2));

                // Show/hide form based on selections
                // if (selectedRows.size > 0) {
                //     $('#handover-form').show();
                // } else {
                //     $('#handover-form').hide();
                // }
            }

            // Handle individual row selection
            $table.on('check.bs.table', function(e, row) {
                if (!selectedRows.has(row.id)) {
                    updateTotalAmount(row, true);
                }
            });

            $table.on('uncheck.bs.table', function(e, row) {
                if (selectedRows.has(row.id)) {
                    updateTotalAmount(row, false);
                }
            });

            // Handle all selection/deselection
            $table.on('check-all.bs.table', function(e, rows) {
                rows.forEach(function(row) {
                    if (!selectedRows.has(row.id) && !row.handed_over) {
                        updateTotalAmount(row, true);
                    }
                });
            });

            $table.on('uncheck-all.bs.table', function() {
                totalAmount = 0;
                selectedRows.clear();
                $('#total-amount').val('0.00');
                // $('#handover-form').hide();
            });

            // Initialize the bootstrap table
            $table.bootstrapTable({
                formatRecordsPerPage: function(pageNumber) {
                    return pageNumber + ' rows visible';
                },
                formatShowingRows: function(pageFrom, pageTo, totalRows) {
                    return 'Showing ' + pageFrom + ' to ' + pageTo + ' of ' + totalRows + ' entries';
                },
                checkboxHeader: true,
                columns: [{
                        field: 'state',
                        checkbox: true,
                        formatter: 'stateFormatter'
                    }, {
                        field: 'id',
                        title: 'Adjustment ID',
                        sortable: true
                    }, {
                        field: 'username',
                        title: 'Username',
                        sortable: true
                    }, {
                        field: 'date',
                        title: 'Adjustment Date',
                        sortable: true
                    }, {
                        field: 'total_amount',
                        title: 'Amount Paid',
                        sortable: true
                    },
                    // {
                    //     field: 'actions',
                    //     title: 'Actions',
                    //     sortable: false,
                    //     formatter: 'actionsFormatter'
                    // }
                ]
            });

            // Handle form submission
            $('#cash-handover-form').on('submit', function(e) {
                e.preventDefault();

                if (selectedRows.size === 0) {
                    new PNotify({
                        title: 'Warning',
                        text: 'Please select at least one receipt to handover.',
                        type: 'warning'
                    });
                    return;
                }

                if (!$('#handover-to').val()) {
                    new PNotify({
                        title: 'Warning',
                        text: 'Please select a user to handover to.',
                        type: 'warning'
                    });
                    return;
                }

                // Get all selected rows
                var selectedRowData = $table.bootstrapTable('getSelections');
                var receiptIds = selectedRowData.map(function(row) {
                    return row.id.replace('ADJ-', '');
                });

                // Prepare form data
                var formData = {
                    receipt_ids: receiptIds,
                    total_amount: totalAmount,
                    handover_by: $('#handover-by-id').val(),
                    handover_to: $('#handover-to').val(),
                    handover_date: $('#handover-date').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                };

                // Submit the handover
                $.ajax({
                    url: '{{ route('cash-handover.store') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        new PNotify({
                            title: 'Success',
                            text: 'Cash handover saved successfully!',
                            type: 'success'
                        });

                        // Reset form and table
                        // $('#handover-form').hide();
                        $('#handover-to').val('').trigger('change');
                        $('#handover-date').val('').trigger('change');
                        $('#total-amount').val('').trigger('change');
                        totalAmount = 0;
                        selectedRows.clear();
                        $table.bootstrapTable('refresh');
                    },
                    error: function(xhr) {
                        new PNotify({
                            title: 'Error',
                            text: 'Error saving cash handover: ' +
                                (xhr.responseJSON?.message || 'Unknown error occurred'),
                            type: 'error'
                        });
                    }
                });
            });
        });
    </script>
@stop
