@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('Adjustments') }}

    @parent

@stop

@section('header_right')
    @can('create', \App\Models\Receipt::class)
        <a href="{{ route('receipts.create') }}" class="btn btn-primary pull-right">
            {{ trans('general.create') }}</a>
    @endcan
@stop

{{-- Page content --}}
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    <div class="table-responsive">

                        <table data-columns="{{ \App\Presenters\ReceiptPresenter::dataTableLayout() }}"
                            data-cookie-id-table="ReceiptTable" data-pagination="true" data-id-table="ReceiptTable"
                            data-search="true" data-side-pagination="server" data-show-columns="true"
                            data-show-fullscreen="true" data-show-export="true" data-show-refresh="true"
                            data-sort-order="asc" id="ReceiptTable" class="table table-striped snipe-table"
                            data-url="{{ route('api.receipts.index') }}"
                            data-export-options='{
              "fileName": "export-receipts-{{ date('Y-m-d') }}",
              "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","icon"]
              }'>
                        </table>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
    {{-- Receipt Modal --}}
    <div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-labelledby="receiptModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiptModalLabel">
                        <i class="fas fa-receipt me-2"></i>Adjustment Details
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
                                <p><strong>Adjustment ID:- </strong> <span id="receiptId">ADJ-2023-001</span></p>
                                <p><strong>Driver Name:- </strong> <span id="driverName">John Doe</span></p>
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
                            <i class="fas fa-download"></i> Download Adjustment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('moar_scripts')
    <script>
        $(document).on("click", "#ReceiptTable tbody tr td:first-child a", function(e) {
            e.preventDefault();
            var receiptId = $(this).text();
            var id = receiptId.replace('ADJ-', '');

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
                    $('#driverName').text(data[0].user_name);
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
                            '<td>' + item.asset_tag + ' - ' + item.type + '</td>' +
                            '<td>' + paidAmount + '</td>' +
                            '<td>' + remainingAmount + '</td>' +
                            '<td>' + total + '</td>' +
                            '<td>' + paymentStatus + '</td>' +
                            '<td>' + (item.description || 'No description') + '</td>' +
                            '</tr>';
                        tableBody.append(row); // Add the row to the table body
                    });

                    // Append totals row to the table
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

        // Handle the download button
        $('#downloadReceiptBtn').on('click', function() {
            var receiptId = $('#receiptId').text();
            var numericId = receiptId.replace('ADJ-', '');
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
    </script>
    @include ('partials.bootstrap-table')
@stop
