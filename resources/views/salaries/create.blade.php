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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="search_driver_id">Driver:</label>
                                    <select name="search_driver_id" id="search_driver_id" class="form-control">
                                        <option value="">Select Driver</option>
                                        @foreach ($drivers as $driver)
                                            <option value="{{ $driver->id }}">
                                                {{ $driver->first_name }} {{ $driver->last_name }} ({{ $driver->username }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="incomplete">Filter:</label>
                                    <select name="incomplete" id="incomplete" class="form-control">
                                        <option value="">Show All</option>
                                        <option value="incomplete">Show Incomplete Only</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="date-range-warning" class="alert alert-warning" style="display: none;">
                            Data already exists for the selected date range
                        </div>

                        <div id="drivers-container">
                            <!-- Drivers and their salary inputs will be populated here -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="salarySlipModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Content will be inserted here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary" onclick="printSalarySlip()">Print</button> --}}
                </div>
            </div>
        </div>
    </div>
@stop

@section('moar_scripts')
    <script>
        $(document).ready(function() {
            $('#search_driver_id').select2({
                placeholder: "Select a driver",
                allowClear: true
            });

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
                const driverId = $('#search_driver_id').val();
                const incomplete = $('#incomplete').val();

                if (!fromDate || !toDate) return;

                $('#drivers-container').html('<div class="alert alert-info">Loading...</div>');
                $('#date-range-warning').hide();

                $.ajax({
                    url: '{{ route('salaries.fetch-data') }}',
                    method: 'GET',
                    data: {
                        from_date: fromDate,
                        to_date: toDate,
                        driver_id: driverId,
                        incomplete: incomplete
                    },
                    success: function(response) {
                        if (response.status === 'warning') {
                            $('#date-range-warning').show().text(response.message);
                            $('#drivers-container').empty();
                        } else if (response.status === 'success' && response.data) {
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
                const fromDate = $('#from_date').val();
                const toDate = $('#to_date').val();
                container.empty();

                if (!data.drivers || !data.ridingCompanies) {
                    container.html('<div class="alert alert-danger">Error loading data</div>');
                    return;
                }

                data.drivers.forEach(driver => {
                    let driverHtml = `
                <div class="driver-row mb-4">
                    <h4><strong>Driver: ${driver.fname} ${driver.lname} (${driver.name})</strong></h4>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>As Per Pay Slip</label>
                                <input type="number"
                                    class="form-control base-salary-input"
                                    data-driver="${driver.id}"
                                    value="${data.driverSalaries[driver.id]?.base_salary || ''}"
                                    min="0"
                                    step="0.01">
                            </div>
                        </div>
            `;

                    let total = 0;
                    data.ridingCompanies.forEach(company => {
                        const salaryArray = data.salaries[driver.id]?.[company.id] || [];
                        const salary = salaryArray[0] || null;
                        const amount = salary ? salary.amount_paid : '';
                        total += parseFloat(amount || 0);

                        driverHtml += `
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>${company.name}</label>
                            <input type="number"
                                class="form-control salary-input"
                                data-driver="${driver.id}"
                                data-company="${company.id}"
                                value="${amount}"
                                min="0"
                                step="0.01">
                        </div>
                    </div>
                `;
                    });

                    driverHtml += `
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Total</label>
                                <input type="text" class="form-control driver-total"
                                    data-driver="${driver.id}"
                                    readonly
                                    value="${total.toFixed(2)}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary payslip" id="driver-${driver.id}"
                                data-driver="${driver.id}"
                                data-from-date="${fromDate}"
                                data-to-date="${toDate}"
                                style="margin-top:22px;"
                                >Payslip</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                    container.append(driverHtml);
                });
            }

            // Updated updateSalary function with proper implementation
            const updateSalary = debounce(function(input) {
                const driverId = $(input).data('driver');
                const companyId = $(input).data('company');
                const amount = $(input).val();
                const fromDate = $('#from_date').val();
                const toDate = $('#to_date').val();

                // Validate required fields
                if (!fromDate || !toDate) {
                    alert('Please select date range first');
                    return;
                }

                $.ajax({
                    url: '{{ route('salaries.store') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        driver_id: driverId,
                        riding_company_id: companyId,
                        from_date: fromDate,
                        to_date: toDate,
                        amount_paid: amount || 0
                    },
                    success: function(response) {
                        if (response.success) {
                            updateDriverTotal(driverId);
                            // Optional: Show success indicator
                            $(input).addClass('is-valid');
                            setTimeout(() => $(input).removeClass('is-valid'), 2000);
                        } else {
                            alert('Error saving salary. Please try again.');
                            $(input).addClass('is-invalid');
                            setTimeout(() => $(input).removeClass('is-invalid'), 2000);
                        }
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON?.message || 'Error saving salary';
                        alert(error);
                        $(input).addClass('is-invalid');
                        setTimeout(() => $(input).removeClass('is-invalid'), 2000);
                    }
                });
            }, 500);

            const updateBaseSalary = debounce(function(input) {
                const driverId = $(input).data('driver');
                const amount = $(input).val();
                const fromDate = $('#from_date').val();
                const toDate = $('#to_date').val();
                $.ajax({
                    url: '{{ route('salaries.update-driver-salary') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        driver_id: driverId,
                        base_salary: amount || 0,
                        from_date: fromDate,
                        to_date: toDate,
                    },
                    success: function(response) {
                        if (response.success) {
                            // Optional: Show success indicator
                            $(input).addClass('is-valid');
                            setTimeout(() => $(input).removeClass('is-valid'), 2000);
                        } else {
                            alert('Error saving base salary. Please try again.');
                            $(input).addClass('is-invalid');
                            setTimeout(() => $(input).removeClass('is-invalid'), 2000);
                        }
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON?.message || 'Error saving base salary';
                        alert(error);
                        $(input).addClass('is-invalid');
                        setTimeout(() => $(input).removeClass('is-invalid'), 2000);
                    }
                });
            }, 500);

            function updateDriverTotal(driverId) {
                let total = 0;
                $(`.salary-input[data-driver="${driverId}"]`).each(function() {
                    total += parseFloat($(this).val() || 0);
                });
                $(`.driver-total[data-driver="${driverId}"]`).val(total.toFixed(2));
            }

            // Event Listeners
            $('#from_date, #to_date, #search_driver_id, #incomplete').change(fetchSalaryData);

            $(document).on('input', '.salary-input', function() {
                const driverId = $(this).data('driver');
                updateDriverTotal(driverId);
                updateSalary(this);
            });

            $(document).on('input', '.base-salary-input', function() {
                updateBaseSalary(this);
            });
            $(document).on('click', '.payslip', function() {
                const driverId = $(this).data('driver');
                const fromDate = $(this).data('from-date');
                const toDate = $(this).data('to-date');

                $.ajax({
                    url: '{{ route('salaries.salary-slip') }}',
                    type: 'POST',
                    data: {
                        driver_id: driverId,
                        from_date: fromDate,
                        to_date: toDate,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            const data = response.data;
                            displaySalarySlip(data, fromDate, toDate);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('Error fetching salary slip data');
                    }
                });
            });

            function displaySalarySlip(data, fromDate, toDate) {
                // Get base salary
                const baseSalary = data.driverSalary ? parseFloat(data.driverSalary.base_salary || 0) : 0;
                const totalCashInHand = parseFloat(data.totalCashInHand);
                const adjustmentsTotalAmount = parseFloat(data.adjustmentsTotalAmount);
                const salaryCash = parseFloat(data.salaryCash.total_amount)
                const expnsesTotalAmount = parseFloat(data.expensesTotalAmount)
                // Check if both values are valid numbers
                const totalDeductions = (isNaN(totalCashInHand) ? 0 : totalCashInHand) + (isNaN(
                    adjustmentsTotalAmount) ? 0 : adjustmentsTotalAmount);
                const totalAdditions = (isNaN(salaryCash) ? 0 : salaryCash) + (isNaN(
                    expnsesTotalAmount) ? 0 : expnsesTotalAmount);
                const total = totalDeductions + totalAdditions;
                const formatDate = (dateString) => {
                    const date = new Date(dateString);
                    const options = {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    };
                    return date.toLocaleDateString('en-US', options);
                };

                // Format fromDate and toDate
                const formattedFromDate = formatDate(fromDate);
                const formattedToDate = formatDate(toDate);
                let html = `
    <div class="salary-slip-container" style="max-width: 800px; margin: 0 auto; padding: 20px;">
        <strong><h2 class="text-center mb-4">Salary Slip</h2></strong>
        
        <div class="d-flex justify-content-between mb-4" style="display: flex; justify-content: space-between;">
            <div><strong>Name: ${data.driver.first_name} ${data.driver.last_name} (${data.driver.username})</strong></div>
            <div><strong>From ${formattedFromDate} to ${formattedToDate}</strong></div>
        </div>

        <table class="table table-bordered">
            <tr>
                <td>As Per Pay Slip</td>
                <td class="text-right">${parseFloat(baseSalary ?? 0).toFixed(2)}</td>
            </tr>
            
            <tr>
                <td colspan="2"><strong>Deductions:</strong></td>
            </tr>
            <tr>
                <td>Cash in Hand</td>
                <td class="text-right">${parseFloat(data?.totalCashInHand ?? 0).toFixed(2)}</td>
            </tr>
            ${Object.entries(data.adjustmentTotals).map(([key, value]) => 
                value ? `
                            <tr>
                                <td>${key.charAt(0).toUpperCase() + key.slice(1)}</td>
                                <td class="text-right">${parseFloat(value ?? 0).toFixed(2)}</td>
                            </tr>` : ''
            ).join('')}

            <tr>
                <td colspan="2"><strong>Other Additions:</strong></td>
            </tr>
            ${data.salaryCash ? `
                        <tr>
                            <td>Cash Adjustment</td>
                            <td class="text-right">${parseFloat(data?.salaryCash?.total_amount ?? 0).toFixed(2)}</td>
                        </tr>
                    ` : ''}
            ${Object.entries(data.expenseTotals).map(([key, value]) => 
                value ? `
                            <tr>
                                <td>${key.charAt(0).toUpperCase() + key.slice(1)}</td>
                                <td class="text-right">${parseFloat(value ?? 0).toFixed(2)}</td>
                            </tr>` : ''
            ).join('')}

            <tr>
                <td><strong>Total Payable in Bank</strong></td>
                <td class="text-right">${parseFloat(total ?? 0).toFixed(2)}<strong></strong></td>
            </tr>
        </table>
    </div>
`;

                // Show in modal
                $('#salarySlipModal .modal-body').html(html);
                $('#salarySlipModal').modal('show');
            }
        });
    </script>
@stop
