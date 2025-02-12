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
            // Debounce function to prevent too many rapid requests
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
                    let driverHtml = `
                        <div class="driver-row mb-4">
                            <h4><strong>Driver: ${driver.name || driver.username}</strong></h4>
                            <div class="row">
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
                        </div>
                    </div>`;

                    container.append(driverHtml);
                });
            }

            const updateSalary = debounce(function(input) {
                const driverId = $(input).data('driver');
                const companyId = $(input).data('company');
                const amount = $(input).val();

                $.ajax({
                    url: '{{ route('salaries.store') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        driver_id: driverId,
                        riding_company_id: companyId,
                        from_date: $('#from_date').val(),
                        to_date: $('#to_date').val(),
                        amount_paid: amount || 0
                    },
                    success: function(response) {
                        if (response.success) {
                            updateDriverTotal(driverId);
                        } else {
                            alert('Error saving salary. Please try again.');
                        }
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON?.message || 'Error saving salary';
                        alert(error);
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
            $('#from_date, #to_date').change(fetchSalaryData);

            $(document).on('input', '.salary-input', function() {
                const driverId = $(this).data('driver');
                updateDriverTotal(driverId);
                updateSalary(this);
            });
        });
    </script>
@stop
