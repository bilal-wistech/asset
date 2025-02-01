@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Edit Receipt
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

        .main_row {
            display: flex;
            gap: 5px;
        }

        .my_row {
            width: 20%;
        }

        .my_roww {

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
    <form class="col-md-12 d-flex align-items-center"
          action="{{ route('receipts.update', ['id' => $receipt[0]->receipt_id]) }}" method="POST">
        @csrf
        <div class="container-fluid" style="position: relative">
            <div class="my_roww">
                <div class="form-group  myform {{ $errors->has('receipt') ? 'error' : '' }}">
                    {{ Form::label('receipt', trans('Adjustment-ID'), ['class' => ' control-label']) }}
                    <div class="">
                        <input class="form-control" type="number" name="receipt_id" id="receipt"
                               placeholder="1234567890"
                               required value="{{ old('receipt_id', $receipt[0]->receipt_id ?? '') }}" readonly/>
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
                    <div class="main_row">
                        <div class="my_row">
                            <div class="myform ">
                                <label for="user_id" class="mb-1">Select User</label>
                                <select class="form-control select2" id="user_id" name="user_id">
                                    <option value="">Select</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                                {{ old('user_id', $receipt[0]->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->first_name.' '.$user->last_name. ' - '.$user->username }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="my_row">
                            <div class="form-group myform">
                                {{ Form::label('receipt_date', trans('Adjustment Date'), ['class' => 'control-label']) }}
                                <div class="date" style="display: table">
                                    <input type="text"
                                           class="form-control datepicker"
                                           placeholder="Select Date (DD-MM-YYYY)"
                                           name="receipt_date"
                                           value="{{ old('receipt_date', $receipt[0]->date ? \Carbon\Carbon::parse($receipt[0]->date)->format('d-m-Y') : '') }}"
                                           required>
                                    <span class="input-group-addon">
                <i class="fas fa-calendar" aria-hidden="true"></i>
            </span>
                                </div>
                            </div>
                        </div>
                        <div class="my_row">
                            <div class="form-group myform ">
                                {{ Form::label('deduction_way', trans('Adjustment Way'), ['class' => ' control-label']) }}
                                <div class="">
                                    <select name="deduction_way" class="form-control" required>
                                        <option value="" disabled selected>Select Deduction Way</option>
                                        <option value="cash"
                                                {{ old('deduction_way', $receipt[0]->deduction_way) == 'cash' ? 'selected' : '' }}>
                                            Cash
                                        </option>
                                        <option value="salary"
                                                {{ old('deduction_way', $receipt[0]->deduction_way) == 'salary' ? 'selected' : '' }}>
                                            Salary
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="my_row">
                            <div class="form-group  myform {{ $errors->has('slip') ? 'error' : '' }}">
                                {{ Form::label('slip', trans('Slip-ID'), ['class' => ' control-label']) }}
                                <div class="">
                                    <input class="form-control" type="text" name="slip_id" id="slip"
                                           pattern="\d{3}-?\d{5}-?\d{1}"
                                           title="Enter a valid fine number in the format 031-26837-8 or 031268378"
                                           value="{{ old('slip_id', $receipt[0]->slip_id ?? '') }}"/>
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
        <input type="hidden" name="total_balance" value="{{ $receipt[0]->total_amount }}">
        <div class="table-responsive " style=" margin-top:50px;">
            <table id="finesTable" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Type</th>
                    <th scope="col">Description</th>
                    <th scope="col"> Amount</th>
                    <th scope="col">Payment</th>


                </tr>
                </thead>
                <tbody>
                @foreach ($receipt as $data)
                    {{-- @dd($data) --}}
                    <tr>
                        <td>
                            <a href="{{ match ($data->type) {
                                    'Fine' => url('/fine/show/' . $data->type_id),
                                    'Deduction' => url('/deductions/show/' . $data->type_id),
                                    default => url('/accident/show/' . $data->type_id),
                                } }}"
                               target="_blank" rel="noopener noreferrer">
                                {{ $data->asset_tag . ' - ' . $data->type }}
                            </a>
                        </td>

                        <td>
                            <textarea name="description[{{ $data->id }}]"
                                      class="form-control">{{ old('description.' . $data->id, $data->description) }}</textarea>
                        </td>
                        <td class="amount-cell">{{ number_format($data->previous_amount ?? $data->amount, 2) }}</td>
                        <td>
                            <input type="number" name="payment[{{ $data->id }}]" step="0.01"
                                   value="{{ $data->previous_amount == 0 ? $data->previous_amount : 0 }}"
                                   class="form-control payment-input" data-id="{{ $data->id }}"
                                   placeholder="{{ $data->previous_amount == 0 ? 'Paid' : '' }}"
                                    {{ $data->previous_amount == 0 ? 'readonly' : '' }} />
                        </td>


                        <!-- Hidden inputs for type and type_id -->

                        <input type="hidden" name="type[{{ $data->id }}]" value="{{ $data->type }}">
                        <input type="hidden" name="previous_balance" value="{{ $data->previous_amount }}">

                        <input type="hidden" name="type_id[{{ $data->id }}]" value="{{ $data->type_id }}">
                    </tr>
                @endforeach
                </tbody>
                <tfoot>

                <tr class="total-row">
                    <td colspan="1"><strong>Total Received</strong></td>
                    <td id="totalReceived" colspan="3">{{ ($totalPayment) }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="1"><strong>Remaining Payable<strong></strong></td>
                    <td id="remainingPayable" colspan="3">0.00</td>
                </tr>
                <tr class="total-row">
                    <td colspan="1"><strong>Total<strong></strong></td>
                    <td id="remainingPayable" colspan="3">
                        @if ($receipt[0]->previous_amount == 0)
                            {{ $receipt[0]->total_amount }} <strong style="color: green;">Paid</strong>
                        @else
                            {{ $receipt[0]->total_amount }}
                        @endif
                    </td>

                </tr>
                </tfoot>
            </table>
            @if ($receipt[0]->previous_amount != 0)
                <div class="box-footer text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check icon-white" aria-hidden="true"></i> {{ trans('general.update') }}
                    </button>
                </div>
            @endif
        </div>

    </form>
@stop

@section('moar_scripts')

    <script>
        $(document).ready(function () {
            const initialTotalPayment = parseFloat('{{ round($totalPayment, 2) }}');

            function calculateTotals() {
                let totalAdditionalPayments = 0;

                // Loop through each row in the table
                $('#finesTable tbody tr').each(function () {
                    // Get the payment input value
                    const payment = parseFloat($(this).find('.payment-input').val()) || 0;

                    // Get the remaining amount (value in the amount-cell)
                    const remainingAmount = parseFloat($(this).find('.amount-cell').text().replace(/,/g,
                        '')) || 0;

                    // Add payment to total additional payments
                    totalAdditionalPayments += payment;

                    // Check if payment exceeds remaining amount
                    if (payment > remainingAmount) {
                        alert(
                            `You have only ${remainingAmount.toFixed(2)} to pay. You cannot exceed this limit.`
                        );
                        $(this).find('.payment-input').val(remainingAmount);
                        totalAdditionalPayments = totalAdditionalPayments - payment + remainingAmount;
                    }
                });

                // Calculate total received by adding initial total payment and additional payments
                const totalReceived = initialTotalPayment + totalAdditionalPayments;

                // Update the Total Received field
                $('#totalReceived').text(totalReceived.toFixed(2));

                // Calculate remaining payable
                let remainingPayable = 0;
                $('#finesTable tbody tr').each(function () {
                    const remainingAmount = parseFloat($(this).find('.amount-cell').text().replace(/,/g,
                        '')) || 0;
                    remainingPayable += remainingAmount;
                });

                // Subtract total received from the original remaining payable
                let updatedRemainingPayable = Math.max(remainingPayable - totalAdditionalPayments, 0);
                $('#remainingPayable').text(updatedRemainingPayable.toFixed(2));
            }

            $(document).on('focus', '.payment-input', function () {
                const $input = $(this);
                const remainingAmount = parseFloat(
                    $input.closest('tr').find('.amount-cell').text().replace(/,/g, '')
                ) || 0;

                // Set the input value to the remaining amount if it's currently 0
                if (parseFloat($input.val()) === 0) {
                    $input.val(remainingAmount.toFixed(2));

                    // Recalculate totals after setting the input value
                    calculateTotals();
                }
            });

            // Event listener for input changes in payment inputs
            $(document).on('input', '.payment-input', function () {
                // Recalculate totals
                calculateTotals();
            });

            // Initial calculation on page load
            calculateTotals();

            // Initialize Select2 dropdown
            $('.select2').select2();
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            }).datepicker('setDate', function() {
                // If there's an existing value, use it; otherwise use current date
                var existingDate = $(this).val();
                return existingDate ? existingDate : new Date();
            });
        });
    </script>

    @include ('partials.bootstrap-table', [
        'exportFile' => 'expence-export',
        'search' => false,
        'columns' => \App\Presenters\ExpencePresenter::dataTableLayout(),
    ])
@stop
