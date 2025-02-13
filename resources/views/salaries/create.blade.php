@extends('layouts/default')

@section('title')
    Create Salaries
    @parent
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Create Salaries</h3>
                </div>

                <div class="box-body">
                    <form id="salaryForm">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="from_date">From Date:</label>
                                    <input type="date" class="form-control" id="from_date" name="from_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="to_date">To Date:</label>
                                    <input type="date" class="form-control" id="to_date" name="to_date" required>
                                </div>
                            </div>
                        </div>

                        <div id="drivers-container">
                            <!-- Drivers and their salary inputs will be populated here -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('moar_scripts')
    <script>
        $(document).ready(function() {
            function debounce(func, wait) {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }

            function fetchSalaryData() {
                const fromDate = $('#from_date').val();
                const toDate = $('#to_date').val();

                if (!fromDate || !toDate) return;

                $('#drivers-container').html('<div class="alert alert-info">Loading...</div>');

                $.ajax({
                    url: '{{ route('salaries.fetch-data') }}',
                    method: 'GET',
                    data: {
                        from_date: fromDate,
                        to_date: toDate
                    },
                    success: function(response) {
                        if (response.status === 'success' && response.data) {
                            updateDriversContainer(response.data);
                        } else {
                            $('#drivers-container').html(
                                '<div class="alert alert-danger">Error loading data</div>'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#drivers-container').html(
                            `<div class="alert alert-danger">Error: ${error}</div>`
                        );
                    }
                });
            }

            function updateDriversContainer(data) {
                const container = $('#drivers-container');
                container.empty();

                if (!data.drivers || !data.ridingCompanies) {
                    container.html('<div class="alert alert-danger">Error loading data</div>');
                    return;
                }

                data.drivers.forEach(driver => {
                    // Find first salary record for the driver (if any)
                    let firstSalaryRecord = null;
                    for (const companyId in data.salaries[driver.id] || {}) {
                        const salaryArray = data.salaries[driver.id][companyId];
                        if (salaryArray && salaryArray.length > 0) {
                            firstSalaryRecord = salaryArray[0];
                            break;
                        }
                    }

                    let driverHtml = `
                <div class="driver-row mb-4">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <h4 style="margin: 0;"><strong>Driver: ${driver.fname} ${driver.lname} (${driver.name})</strong></h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group mb-0">
                                <label>Salary</label>
                                <input type="number"
                                    class="form-control salary-value-input"
                                    placeholder="Enter Salary"
                                    data-driver="${driver.id}"
                                    value="${firstSalaryRecord?.salary || ''}"
                                    min="0"
                                    step="0.01">
                            </div>
                        </div>
            `;

                    let amountTotal = 0;
                    data.ridingCompanies.forEach(company => {
                        const salaryArray = data.salaries[driver.id]?.[company.id] || [];
                        const salary = salaryArray[0] || null;
                        const amount = salary ? salary.amount_paid : '';
                        amountTotal += parseFloat(amount || 0);

                        driverHtml += `
                    <div class="col-md-2">
                        <div class="form-group mb-0">
                            <label>${company.name}</label>
                            <input type="number"
                                class="form-control amount-input"
                                placeholder="Amount Paid"
                                data-driver="${driver.id}"
                                data-company="${company.id}"
                                value="${amount}"
                                min="0"
                                step="0.01">
                        </div>
                    </div>
                `;
                    });

                    const salaryValue = parseFloat(firstSalaryRecord?.salary || 0);
                    const totalAmount = amountTotal + salaryValue;

                    driverHtml += `
                        <div class="col-md-2">
                            <div class="form-group mb-0">
                                <label>Total</label>
                                <input type="text" class="form-control driver-total"
                                    data-driver="${driver.id}"
                                    readonly
                                    value="${totalAmount.toFixed(2)}">
                            </div>
                        </div>
                    </div>
                </div>`;

                    container.append(driverHtml);
                });
            }

            const updateSalary = debounce(function(input, updateAll = false) {
                const driverId = $(input).data('driver');
                const companyId = $(input).data('company');
                const salary = $(`.salary-value-input[data-driver="${driverId}"]`).val();
                const amount = $(input).val();

                // If this is a salary update, we need to trigger updates for all companies
                if (updateAll) {
                    const promises = [];
                    $(`.amount-input[data-driver="${driverId}"]`).each(function() {
                        const companyInput = $(this);
                        promises.push(
                            $.ajax({
                                url: '{{ route('salaries.store') }}',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    driver_id: driverId,
                                    riding_company_id: companyInput.data('company'),
                                    from_date: $('#from_date').val(),
                                    to_date: $('#to_date').val(),
                                    amount_paid: companyInput.val() || 0,
                                    salary: salary || 0
                                }
                            })
                        );
                    });

                    Promise.all(promises)
                        .then(() => {
                            updateDriverTotals(driverId);
                        })
                        .catch((error) => {
                            alert('Error saving salary. Please try again.');
                            console.error(error);
                        });
                } else {
                    // Single company update
                    $.ajax({
                        url: '{{ route('salaries.store') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            driver_id: driverId,
                            riding_company_id: companyId,
                            from_date: $('#from_date').val(),
                            to_date: $('#to_date').val(),
                            amount_paid: amount || 0,
                            salary: salary || 0
                        },
                        success: function(response) {
                            if (response.success) {
                                updateDriverTotals(driverId);
                            } else {
                                alert('Error saving salary. Please try again.');
                            }
                        },
                        error: function(xhr) {
                            const error = xhr.responseJSON?.message || 'Error saving salary';
                            alert(error);
                        }
                    });
                }
            }, 500);

            function updateDriverTotals(driverId) {
                let amountTotal = 0;
                $(`.amount-input[data-driver="${driverId}"]`).each(function() {
                    amountTotal += parseFloat($(this).val() || 0);
                });

                const salaryValue = parseFloat($(`.salary-value-input[data-driver="${driverId}"]`).val() || 0);
                const totalAmount = amountTotal + salaryValue;

                $(`.driver-total[data-driver="${driverId}"]`).val(totalAmount.toFixed(2));
            }

            // Event Listeners
            $('#from_date, #to_date').change(fetchSalaryData);

            $(document).on('input', '.amount-input', function() {
                const driverId = $(this).data('driver');
                updateDriverTotals(driverId);
                updateSalary(this, false);
            });

            $(document).on('input', '.salary-value-input', function() {
                const driverId = $(this).data('driver');
                updateDriverTotals(driverId);
                updateSalary(this, true);
            });
        });
    </script>
@stop