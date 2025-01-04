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
</style>

@php
$selectedUserId = request()->input('user_id');
@endphp
<div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-labelledby="receiptModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">
                    <i class="fas fa-receipt me-2"></i>Ledger Details
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
                            <p><strong>Receipt ID:- </strong> <span id="receiptId">RCP-2023-001</span></p>
                            <p><strong>Driver Name:- </strong> <span id="driverName">John Doe</span></p>
                            <p><strong>Company Name:- </strong> <span id="driverName">Knock Knock</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Receipt Date:- </strong> <span id="receiptDate">December 5, 2024</span></p>
                            <p><strong>Deduction Way:- </strong> <span id="deductionWay">Direct Deposit</span></p>
                            <p><strong>Slip Number:- </strong> <span id="slipNumber">SLP-456-789</span></p>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered table-hover" id="ReceiptTable1">
                    <thead>
                        <tr>
                            <th scope="col">Type</th>
                            <th scope="col">Paid Amount</th>
                            <th scope="col">Remaining Amount</th>
                            <th scope="col">Total Amount</th>
                            <th scope="col">Payment status</th>
                            <th scope="col">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic rows will be added here -->
                    </tbody>
                </table>

                <div class="text-right mt-4">
                    <button class="btn btn-download btn-success" id="downloadReceiptBtn">
                        <i class="fas fa-download"></i> Download Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<form class="col-md-12 d-flex align-items-center" action="{{ route('form.savereceipt') }}" method="POST">
    @csrf
    <div class="container-fluid">
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
                            {{ Form::label('start_date', trans('Start Date'), array('class' => ' control-label')) }}
                            <div class="date" style="display: table" data-provide="datepicker" data-date-format="dd-mm-yyyy"
                                data-autoclose="true">
                                <input type="text" class="form-control" placeholder="Select Date (DD-MM-YYYY)" name="start_date"
                                    required>
                                <span class="input-group-addon"><i class="fas fa-calendar" aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group myform">
                            {{ Form::label('end_date', trans('End Date'), array('class' => ' control-label')) }}
                            <div class="date" style="display: table" data-provide="datepicker" data-date-format="dd-mm-yyyy"
                                data-autoclose="true">
                                <input type="text" class="form-control" placeholder="Select Date (DD-MM-YYYY)" name="end_date"
                                    required>
                                <span class="input-group-addon"><i class="fas fa-calendar" aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add additional filters or buttons here if needed -->
        </div>
    </div>

    <div class="table-responsive" style="display: none; margin-top:50px;">
        <table data-cookie-id-table="FinesTable" data-pagination="true" data-id-table="FinesTable" data-search="false"
            class="table table-striped snipe-table">
            <thead>
                <tr>
                    <th>Receipt ID</th>
                    <th scope="col">UserName</th>
                    <th scope="col">Date</th>
                    <th scope="col">Deduction Way</th>
                    <th scope="col">Total Amount</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</form>

@stop

{{-- Page content --}}
{{-- @section('content')
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
@stop --}}

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
                url: '{{ route("receipts.get") }}',  // Route to the controller method
                type: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Include CSRF token
                },
                success: function(response) {
                    // Empty the table body before appending new data
                    var $tableBody = $('table.snipe-table tbody');
                    $tableBody.empty();

                    // Check if response is empty
                    if (response.length === 0) {
                        $tableBody.html('<tr><td colspan="5" class="text-center">No matching records found</td></tr>');
                    } else {
                        // Loop through the response and append rows
                        response.forEach(function(receipt) {
                            var row = '<tr>';
                            row += '<td><a href="#" class="receipt-link" data-receipt-id="' + receipt.receipt_id + '">' + receipt.receipt_id + '</a></td>';
                            row += '<td>' + receipt.user.username + '</td>';
                            row += '<td>' + receipt.date + '</td>';
                            row += '<td>' + receipt.deduction_way + '</td>';
                            row += '<td>' + receipt.total_amount + '</td>';
                            row += '</tr>';
                            // Append the row to the table body
                            $tableBody.append(row);
                        });
                    }

                    // Make the table visible after loading data
                    $('.table-responsive').show();
                },
                error: function(xhr, status, error) {
                    console.log(error); // Handle any errors
                },
                complete: function() {
                    // Explicitly close the datepickers
                    $('input[name="start_date"], input[name="end_date"]').datepicker('hide');
                }
            });
        }
    });
});

$(document).on("click", ".receipt-link", function(e) {
    e.preventDefault();
    var receiptId = $(this).text();
    var id = receiptId.replace('RCP-', '');
    
    // Fetch receipt data using the receiptId (you can adjust the URL to your API)
    $.ajax({
        url: '/receipt/' + id,
        method: 'GET',
        success: function(data) {
            // Populate modal with data
            $('#receiptId').text('RCP-' + data[0].receipt_id);
            $('#slipNumber').text('SLP-' + (data[0].slip_id || '-'));
            $('#driverName').text(data[0].user_name);
            $('#receiptDate').text(data[0].date);
            $('#deductionWay').text(data[0].deduction_way);
            
            var tableBody = $('#ReceiptTable1 tbody');
            tableBody.empty(); // Clear the existing rows before adding new ones

            var totalPaidAmount = 0;  // To store total paid amount
            var totalAmount = 0;      // To store total amount

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
                    '<td>' + item.type + '</td>' +
                    '<td>' + paidAmount + '</td>' +
                    '<td>' + remainingAmount + '</td>' +
                    '<td>' + total + '</td>' +
                    '<td>' + paymentStatus + '</td>' +
                    '<td>' + (item.description || 'No description') + '</td>' +
                    '</tr>';
                tableBody.append(row); // Add the row to the table body
            });

            var totalsRow2 = '<tr>' +
                '<td colspan="1"></td>'+
                '<td style="font-weight: bold;">Total Paid Amount: ' + totalPaidAmount.toFixed(2) + '</td>' +
                
                '<td style="font-weight: bold;">Total Remaining Amount: ' + (totalAmount-totalPaidAmount).toFixed(2) + '</td>' +
                
                '<td style="font-weight: bold;">Total Amount: ' + totalAmount.toFixed(2) + '</td>' +
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
            var filename = disposition && disposition.match(/filename="([^"]+)"/) ? disposition.match(/filename="([^"]+)"/)[1] : `Receipt-${receiptId}.pdf`;

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
$('.select2').select2();
</script>



@include ('partials.bootstrap-table', [
'exportFile' => 'expence-export',
'search' => false,
'columns' => \App\Presenters\ExpencePresenter::dataTableLayout(),
])
@stop