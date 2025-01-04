@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Drivers Ledger
    @parent
@stop

@section('content')
    <style>
        .select2-container .select2-selection--single {
            height: 34px !important;
        }

        .select2-container--default .select2-selection--single {
            border: 1px solid #ccc !important;
            border-radius: 0px !important;
        }

        .pull-right {
            float: unset;
        }

        .pull-right .container-fluid {
            padding: 0 !important;
        }

        .content-header {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            gap: 5px;
            margin-bottom: 0;
            display: flex;
            align-items: left;

        }

        label {
            width: 33%;
            margin-bottom: 0 !important;
        }

        .select2-container {
            width: 75% !important;
        }

        .col-md-3 {
            padding-left: 5px !important;
            padding-right: 5px !important;
        }

        .myform {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ledger-report {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .summary {
            margin-top: 20px;
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .summary p {
            margin: 5px 0;
            font-size: 1.2em;
        }

        .summary strong {
            color: #333;
        }
    </style>

    @php
        $selectedUserId = request()->input('user_id');
    @endphp
    <div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceModalLabel">
                        <i class="fas fa-receipt me-2"></i> Details
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="modal-body">
                    <div class="receipt-info">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>ID:- </strong> <span id="invoiceId">001</span></p>
                                <p><strong>User Name:- </strong> <span id="UserName">John Doe</span></p>

                            </div>
                            <div class="col-md-6">

                                <p><strong>Company Name:- </strong> <span>Knock Knock</span></p>
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered table-hover" id="ReceiptTable2">
                        <thead>
                            <tr>

                                <th scope="col">Date</th>

                                <th scope="col">Total Amount</th>
                                <th scope="col">Description</th>

                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic rows will be added here -->
                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-labelledby="receiptModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiptModalLabel">
                        <i class="fas fa-receipt me-2"></i> Details
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{-- <div class="row">
                    <div class="col-md-6">
                        <h5 class="modal-title" id="receiptModalLabel">
                            <i class="fas fa-receipt me-2"></i>Receipt Details
                        </h5>
                        
                    </div>
                    <div class="col-md-6 text-right">
                        <!-- Close button -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    </div>
                </div> --}}
                </div>
                <div class="modal-body">
                    <div class="receipt-info">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Adjustment ID:- </strong> <span id="receiptId">RCP-2023-001</span></p>
                                <p><strong>Driver Name:- </strong> <span id="driverName1">John Doe</span></p>
                                <p><strong>Company Name:- </strong> <span id="driverName">Knock Knock</span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Adjustment Date:- </strong> <span id="receiptDate">December 5, 2024</span></p>
                                <p><strong>Adjustment Method:- </strong> <span id="deductionWay">Direct Deposit</span></p>
                                <p><strong>Slip Number:- </strong> <span id="slipNumber">SLP-456-789</span></p>
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered table-hover" id="ReceiptTable1">
                        <thead>
                            <tr>

                                <th scope="col">Date</th>
                                <th scope="col">Type</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Payment</th>
                                <th scope="col">Description</th>

                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic rows will be added here -->
                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>
    <form class="col-md-12 d-flex align-items-center" action="{{ route('form.savereceipt') }}" method="POST">
        @csrf
        <div class="container-fluid  ">
            <div class="row align-items-center">
                <!-- Select User Dropdown -->
                <div class="form-group mb-0 mr-3">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="myform">
                                <label for="user_id" class="mb-1">Select User</label>
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
                        </div>

                        <div class="col-md-4">
                            <div class="form-group myform">
                                {{ Form::label('start_date', trans('Start Date'), ['class' => ' control-label']) }}
                                <div class="date" style="display: table" data-provide="datepicker"
                                    data-date-format="dd-mm-yyyy" data-autoclose="true">
                                    <input type="text" class="form-control" placeholder="Select Date (DD-MM-YYYY)"
                                        name="start_date" required>
                                    <span class="input-group-addon"><i class="fas fa-calendar"
                                            aria-hidden="true"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group myform">
                                {{ Form::label('end_date', trans('End Date'), ['class' => ' control-label']) }}
                                <div class="date" style="display: table" data-provide="datepicker"
                                    data-date-format="dd-mm-yyyy" data-autoclose="true">
                                    <input type="text" class="form-control" placeholder="Select Date (DD-MM-YYYY)"
                                        name="end_date" required>
                                    <span class="input-group-addon"><i class="fas fa-calendar"
                                            aria-hidden="true"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ledger-report mt-5" style="display: none; margin-top:45px;">
            <h2>Ledger Report</h2>

            <table class="table">
                <thead>
                    <tr>

                        <th>Date</th>
                        <th>Details</th>
                        <th>Amount</th>
                        <th>Payment</th>


                    </tr>
                </thead>
                <tbody id="ledger-table-body">
                    <!-- Data will be inserted here by JavaScript -->
                </tbody>
            </table>


        </div>
    </form>

@stop

@section('moar_scripts')

    <script>
        $(document).ready(function() {
            // Listen for changes in user_id, start_date, or end_date
            $('#user_id, input[name="start_date"], input[name="end_date"]').on('change', function() {
                var userId = $('#user_id').val();
                var startDate = $('input[name="start_date"]').val();
                var endDate = $('input[name="end_date"]').val();
                // Prepare the data for the AJAX request
                var data = {
                    user_id: userId,
                    start_date: startDate,
                    end_date: endDate
                };
                // Check if any of the dates are selected or if the user is selected
                if (userId || startDate || endDate) {
                    // Send AJAX request
                    $.ajax({
                        url: '{{ route('receipts.get') }}', // Route to the controller method
                        type: 'POST',
                        data: data,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content') // Include CSRF token
                        },
                        success: function(response) {
                            // Empty the table body before adding new rows
                            $('#ledger-table-body').empty();

                            // Loop through the response and insert rows into the table
                            response.forEach(function(result) {
                                // Create a new row for each entry
                                var row = $('<tr>');

                                // Add date
                                row.append('<td>' + result.date + '</td>');

                                // Check the type and populate the details column
                                if (result.type === 'receipt') {
                                    row.append(
                                        '<td><a href="#" class="receipt-link" data-receipt-id="' +
                                        result.id + '">' + 'Adjustment (#' + result
                                        .id + ')</a></td>');
                                    row.append(
                                    '<td></td>'); // Empty details column for receipts
                                    row.append('<td>' + result.total_payment +
                                    '</td>'); // Payment column
                                } else if (result.type === 'invoice') {
                                    row.append(
                                        '<td><a href="#" class="invoice-link" data-invoice-id="' +
                                        result.id + '" data-type-table="' + result
                                        .type_table + '">' + result.type_table +
                                        ' (#' + result.id + ')</a></td>');
                                    row.append('<td>' + result.total_payment +
                                    '</td>'); // Amount column
                                    row.append(
                                    '<td></td>'); // Empty payment column for invoices
                                }

                                // Append the row to the table body
                                $('#ledger-table-body').append(row);
                            });

                            // Show the table after data is populated
                            $('.ledger-report').show();
                        },
                        error: function(xhr, status, error) {
                            console.log(error); // Handle any errors
                        },
                        complete: function() {
                            // Explicitly close the datepickers
                            $('input[name="start_date"], input[name="end_date"]').datepicker(
                                'hide');
                        }
                    });
                }
            });
        });
        $(document).on("click", ".receipt-link", function(e) {
            e.preventDefault();
            var receiptId = $(this).text();
            var id = receiptId.match(/\d+/)[0];

            // Fetch receipt data using the receiptId (you can adjust the URL to your API)
            $.ajax({
                url: '/receipt/' + id,
                method: 'GET',
                success: function(data) {
                    // Populate modal with data
                    $('#receiptId').text('ADJ-' + data[0].receipt_id);
                    if (data[0].slip_id) {
                        $('#slipNumber').text('SLP-' + (data[0].slip_id));
                    } else {
                        $('#slipNumber').text('');
                    }
                    $('#driverName1').text(data[0].user_name);
                    $('#receiptDate').text(data[0].date);
                    $('#deductionWay').text(data[0].deduction_way);

                    var tableBody = $('#ReceiptTable1 tbody');
                    tableBody.empty(); // Clear the existing rows before adding new ones

                    var totalPaidAmount = 0; // To store total paid amount
                    var totalAmount = 0; // To store total amount

                    data.forEach(function(item) {
                        // Check if previous_amount is 0, then mark as "Paid" or "Unpaid"
                        var paymentStatus = item.previous_amount == 0 ?
                            '<span style="color: green; font-weight: bold;">Paid</span>' :
                            '<span style="color: red; font-weight: bold;">Unpaid</span>';

                        var paidAmount = Number(item.payment);
                        var remainingAmount = Number(item.previous_amount);
                        var total = paidAmount + remainingAmount;

                        // Accumulate totals
                        totalPaidAmount += paidAmount;
                        totalAmount += total;

                        var row = '<tr>' +
                            '<td>' + item.date + '</td>' +
                            '<td>' + item.type + '</td>' +

                            '<td>' + total + '</td>' +
                            '<td>' + paymentStatus + '</td>' +
                            '<td>' + (item.description || 'No description') + '</td>' +
                            '</tr>';
                        tableBody.append(row); // Add the row to the table body
                    });

                    var totalsRow2 = '<tr>' +
                        '<td colspan="1"></td>' +
                        '<td style="font-weight: bold;">Total Paid Amount: ' + totalPaidAmount.toFixed(
                            2) + '</td>' +

                        '<td style="font-weight: bold;">Total Remaining Amount: ' + (totalAmount -
                            totalPaidAmount).toFixed(2) + '</td>' +

                        '<td style="font-weight: bold;">Total Amount: ' + totalAmount.toFixed(2) +
                        '</td>' +
                        '</tr>';
                    tableBody.append(totalsRow2);

                    // Show modal
                    $('#receiptModal').modal('show');
                }
            });
        });
        $('#downloadReceiptBtn').on('click', function() {
            var receiptId = $('#receiptId').text();
            var numericId = receiptId.replace('RCP-', '');
            $.ajax({
                url: '/receipts/download/' + numericId,
                method: 'GET',
                xhrFields: {
                    responseType: 'blob' // Set response type to blob
                },
                success: function(blob, status, xhr) {
                    // Extract the filename from the Content-Disposition header
                    var disposition = xhr.getResponseHeader('Content-Disposition');
                    var filename = disposition && disposition.match(/filename="([^"]+)"/) ? disposition
                        .match(/filename="([^"]+)"/)[1] : `Receipt-${receiptId}.pdf`;

                    // Create a link element to trigger the download
                    var link = document.createElement('a');
                    var url = window.URL.createObjectURL(blob);
                    link.href = url;
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();

                    // Clean up
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(link);
                },
                error: function(xhr, status, error) {
                    console.error('Error downloading the receipt:', error);
                }
            });
        });
        $(document).on("click", ".invoice-link", function(e) {
            e.preventDefault(); // Prevent default link behavior

            var invoiceId = $(this).data("invoice-id"); // Get invoice ID from the data attribute
            var typeTable = $(this).data("type-table"); // Get the type_table from the data attribute

            $.ajax({
                url: '/invoice/' + invoiceId, // Ensure the URL matches your route
                method: 'GET',
                data: {
                    type_table: typeTable // Send the type_table as part of the request
                },
                success: function(data) {
                    if (data && data.length > 0) {
                        var record = data[0]; // Assuming the first record is required

                        // Populate the static details
                        $("#invoiceId").text(record.id);
                        $("#UserName").text(record.username);

                        // Populate the dynamic table
                        var tableBody = $("#ReceiptTable2 tbody");
                        tableBody.empty(); // Clear any existing rows

                        data.forEach(function(item) {
                            var row = `
                        <tr>
                            <td>${item.date || '-'}</td>
                            <td>${item.amount || '-'}</td>
                            <td>${item.note || '-'}</td>
                        </tr>
                    `;
                            tableBody.append(row);
                        });

                        // Show the modal
                        $("#invoiceModal").modal("show");
                    } else {
                        console.log("No data returned from the server.");
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error:", error); // Log any errors
                }
            });
        });



        $('.select2').select2();
    </script>

    @include ('partials.bootstrap-table', [
        'exportFile' => 'expence-export',
        'search' => false,
        'columns' => \App\Presenters\ExpencePresenter::dataTableLayout(),
    ])
@stop
