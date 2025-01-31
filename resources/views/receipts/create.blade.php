@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Create Adjustment
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
            align-items: center;
            justify-content: center;
        }

        label {
            width: 30%;
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

        .row_input {
            width: 25%;
        }

        .my_row {
            display: flex;
            gap: 5px;
        }

        .row_inpt {

            position: absolute;
            top: -250%;
            right: 0;
        }
        .date {
            position: relative;
        }

        .input-group-addon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .form-control {
            padding-right: 35px;
        }
    </style>

    @php
        $selectedUserId = request()->input('user_id');
    @endphp
    <form class="col-md-12 d-flex align-items-center" action="{{ route('form.savereceipt') }}" method="POST">
        @csrf
        <div class="container-fluid" style="position: relative">
            <div class="row_inpt">
                <div class="form-group  myform {{ $errors->has('receipt') ? 'error' : '' }}">
                    {{ Form::label('receipt', trans('Adjustment-ID'), ['class' => ' control-label']) }}
                    <div class="">
                        <input class="form-control" type="number" name="receipt_id" id="receipt"
                               placeholder="1234567890"
                               required value="{{ old('receipt_id', $nextReceiptId ?? '') }}"
                               title="This is an auto-generated ID. You can edit it if necessary."/>
                        {!! $errors->first(
                            'receipt',
                            '<span class="alert-msg" aria-hidden="true"><i
                                                                                                                                                                                                                                                                            class="fas fa-times" aria-hidden="true"></i> :message</span>',
                        ) !!}
                    </div>
                </div>
            </div>
            <div class="row align-items-center">

                <!-- Select User Dropdown -->
                <div class="form-group mb-0 mr-3 ">
                    <div class="my_row">
                        <div class="row_input">
                            <div class="myform ">
                                <label for="user_id" class="mb-1">Select User</label>
                                <select class="form-control select2" id="user_id" name="user_id">
                                    <option value="">Select</option>
                                    @foreach ($users as $names)
                                        <option value="{{ $names->id }}"
                                                {{ old('user_id', $old_request['user_id'] ?? '') == $names->id ? 'selected' : '' }}>
                                            {{ $names->first_name.' '.$names->last_name.' - '.$names->username }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row_input">
                            <div class="form-group myform">
                                {{ Form::label('receipt_date', trans('Adjustment Date'), ['class' => 'control-label']) }}
                                <div class="date" style="display: table">
                                    <input type="text"
                                           class="form-control datepicker"
                                           placeholder="Select Date (DD-MM-YYYY)"
                                           name="receipt_date"
                                           required>
                                    <span class="input-group-addon">
                <i class="fas fa-calendar" aria-hidden="true"></i>
            </span>
                                </div>
                            </div>
                        </div>
                        <div class="row_input">
                            <div class="form-group myform ">
                                {{ Form::label('deduction_way', trans('Adjustment Way'), ['class' => ' control-label']) }}
                                <div class="">
                                    <select name="deduction_way" class="form-control" required>
                                        <option value="" disabled selected>Select Adjustment Way</option>
                                        <option value="cash">Cash</option>
                                        <option value="salary">Salary</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row_input">
                            <div class="form-group  myform {{ $errors->has('slip') ? 'error' : '' }}">
                                {{ Form::label('slip', trans('Slip-ID'), ['class' => ' control-label']) }}
                                <div class="">
                                    <input class="form-control" type="text" name="slip_id" id="slip"
                                           pattern="\d{3}-?\d{5}-?\d{1}"
                                           title="Enter a valid fine number in the format 031-26837-8 or 031268378"
                                           value="{{ old('slip_id' ?? '') }}"/>
                                    {!! $errors->first(
                                        'slip',
                                        '<span class="alert-msg" aria-hidden="true"><i
                                                                                                                                                                                                                                                                                                                                                                                                                class="fas fa-times" aria-hidden="true"></i> :message</span>',
                                    ) !!}
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- </div> --}}

                </div>

                <!-- Add additional filters or buttons here if needed -->

            </div>
        </div>
        <div class="table-responsive " style="display: none; margin-top:50px;">
            <table data-cookie-id-table="FinesTable" data-pagination="true" data-id-table="FinesTable"
                   data-search="false"
                   class="table table-striped snipe-table">
                <thead>
                <tr>
                    <th scope="col">Type</th>
                    <th scope="col">Description</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Payment</th>

                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <div class="box-footer text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check icon-white" aria-hidden="true"></i> {{ trans('general.save') }}
                </button>
            </div>
        </div>

    </form>
@stop
@section('moar_scripts')
    <script>
        $(document).ready(function () {
            $('#user_id').on('change', function () {
                const userId = $(this).val();
                if (userId) {
                    $.ajax({
                        url: "{{ route('getUserFine') }}",
                        type: "GET",
                        data: {
                            user_id: userId
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);

                            if (!response || !response.details || response.details.length ===
                                0) {
                                $('.box-footer').hide(); // Hides the box-footer div
                            } else {
                                $('.box-footer')
                                    .show(); // Shows the box-footer div in case it's hidden
                            }
                            const tableBody = $('.snipe-table tbody');
                            const tableContainer = $('.table-responsive');
                            tableBody.empty();

                            let totalAmount = parseFloat(response.total_fine || 0);

                            const dataToSend = {
                                _token: "{{ csrf_token() }}",
                                user_id: userId,
                                receipt_id: $('#receipt').val(),
                                receipt_date: $('input[name="receipt_date"]').val(),
                                deduction_way: $('select[name="deduction_way"]').val(),
                                types: {}
                            };

                            if (totalAmount > 0) {
                                response.details.forEach((detail, index) => {
                                    const form = $('form');
                                    const detailAmount = parseFloat(detail.amount);

                                    // Append hidden inputs for type details
                                    form.append(`
                                    <input type="hidden" name="type_id[${index}]" value="${detail.id}" />
                                    <input type="hidden" name="payment[${index}]" class="payment-hidden-input" data-type-id="${detail.id}" value="0" />
                                    <input type="hidden" name="type[${index}]" value="${detail.type}" />
                                    <input type="hidden" name="description[${index}]" value="${detail.note || ''}" />
                                `);

                                    // Initialize dataToSend.types
                                    dataToSend.types[detail.id] = {
                                        type: detail.type,
                                        amount: detailAmount.toFixed(2),
                                        payment: "0.00",
                                        description: detail.note ||
                                            "No note available",
                                        original_amount: detailAmount.toFixed(2)
                                    };

                                    // Append table rows
                                    tableBody.append(`
                                    <tr>
                                        <td>
  <a
    href="${
                                        detail.type === 'Fine'
                                            ? `/fine/show/${detail.id}`
                                            : detail.type === 'Deduction'
                                                ? `/deductions/show/${detail.id}`
                                                : `/accident/show/${detail.id}`
                                    }"
    target="_blank"
    rel="noopener noreferrer"
  >
    ${detail.tag ? `${detail.tag} - ${detail.type}` : `${detail.type}-${detail.id}`}
  </a>
</td>
                                        <td>${detail.note || 'No note available'}</td>
                                        <td>${detailAmount.toFixed(2)}</td>
                                        <td>
                                            <input type="number"
                                                   class="form-control payment-input"
                                                   name="payment[${index}]"
                                                   data-type-id="${detail.id}"
                                                   data-amount="${detailAmount.toFixed(2)}"
                                                   min="0"
                                                   max="${detailAmount.toFixed(2)}"
                                                   step="0.01"
                                                   placeholder="Enter payment" />
                                        </td>

                                    </tr>
                                `);
                                });

                                // Add summary rows
                                tableBody.append(`
                                <tr class="total-row">
                                    <td colspan="1"><strong>Total Payable</strong></td>
                                    <td colspan="3" id="total-payable"><strong>${totalAmount.toFixed(2)}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="1"><strong>Total Received:</strong> </td>
                                    <td colspan="3"><strong id="total-received">0.00</strong></td>
                                </tr>
                                <tr>
                                    <td colsan="1"><strong>Remaining Payable</strong></td>
                                    <td colsan="3"><strong id="total-fine">${totalAmount.toFixed(2)}</strong></td>
                                    // <td></td>
                                    // <td></td>
                                </tr>

                            `);

                                tableContainer.show();

                                // Handle payment input changes
                                $('.payment-input').on('input', function () {
                                    const currentInput = $(this);
                                    const typeId = currentInput.data('type-id');
                                    const fineAmount = parseFloat(currentInput.data(
                                        'amount'));
                                    let inputValue = parseFloat(currentInput.val()) ||
                                        0;

                                    // Validate input
                                    if (inputValue < 0) {
                                        currentInput.val(0);
                                        return;
                                    }

                                    // Limit input to the specific fine amount for this type
                                    if (inputValue > fineAmount) {
                                        alert(
                                            `Payment for this type cannot exceed ${fineAmount.toFixed(2)}`
                                        );
                                        currentInput.val(fineAmount.toFixed(2));
                                        inputValue = fineAmount;
                                    }

                                    // Update the specific type's payment in dataToSend
                                    if (dataToSend.types[typeId]) {
                                        dataToSend.types[typeId].payment = inputValue
                                            .toFixed(2);
                                    }

                                    // Synchronize with hidden input
                                    $(`input.payment-hidden-input[data-type-id="${typeId}"]`)
                                        .val(inputValue.toFixed(2));

                                    // Recalculate total payments
                                    const totalPayments = Object.values(dataToSend
                                        .types)
                                        .reduce((sum, type) => sum + parseFloat(type
                                            .payment || 0), 0);

                                    // Update UI
                                    $('#total-received').text(totalPayments.toFixed(2));
                                    $('#total-fine').text(Math.max(0, (totalAmount -
                                        totalPayments)).toFixed(2));

                                    console.log('Data to Send:', dataToSend);
                                });

                                console.log('Initial Data to Send:', dataToSend);
                            } else {
                                tableBody.append(`
                                <tr>
                                    <td colspan="4" class="text-center">No matching records found</td>
                                </tr>
                            `);
                                tableContainer.show();
                            }
                        },
                        error: function () {
                            $('#total_fine').val(0);
                            $('.snipe-table tbody').empty();
                            $('.table-responsive').hide();
                        }
                    });
                } else {
                    $('#total_fine').val(0);
                    $('.snipe-table tbody').empty();
                    $('.table-responsive').hide();
                }
            });
            // Handle payment input click to auto-fill amount
            $('.snipe-table').on('click', '.payment-input', function () {
                const currentInput = $(this);
                const fineAmount = parseFloat(currentInput.data(
                    'amount')); // Get the fine amount from the data attribute

                if (currentInput.val() === '') { // Only populate if the input is empty
                    currentInput.val(fineAmount.toFixed(2));
                }

                // Trigger the 'input' event to recalculate totals
                currentInput.trigger('input');
            });
            // Form submission handling
            $('form').on('submit', function (e) {
                const formDataArray = [];
                $('input.dynamic-input').each(function () {
                    const name = $(this).attr('name');
                    const value = $(this).val();
                    const match = name.match(/\[(\d+)]/);
                    if (match) {
                        const index = match[1];
                        if (!formDataArray[index]) {
                            formDataArray[index] = {};
                        }

                        if (name.startsWith('type_id')) {
                            formDataArray[index].type_id = value;
                        } else if (name.startsWith('payment')) {
                            formDataArray[index].payment = value;
                        } else if (name.startsWith('type')) {
                            formDataArray[index].type = value;
                        } else if (name.startsWith('description')) {
                            formDataArray[index].description = value;
                        }
                    }
                });

                const totalFine = $("#total-fine").text();
                const totalReceived = $("#total-received").text();
                const totalPayable = $("#total-payable").text();

                const form = $('form');
                form.append(`
                <input type="hidden" name="total_fine" value="${totalFine}">
                <input type="hidden" name="total_recived" value="${totalReceived}">
                <input type="hidden" name="total_payable" value="${totalPayable}">
            `);
            });
        });
        $('.select2').select2();

            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            }).datepicker('setDate', new Date());  // Set current date as default
    </script>


    @include ('partials.bootstrap-table', [
        'exportFile' => 'expence-export',
        'search' => false,
        'columns' => \App\Presenters\ExpencePresenter::dataTableLayout(),
    ])
@stop
