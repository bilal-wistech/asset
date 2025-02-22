@extends('layouts/default')

@section('title')
    Create Salary Cash
    @parent
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    <form id="salaryForm" method="post" action="{{ route('salary-cash.store') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date">Date:</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">Driver:</label>
                                    <select name="user_id" id="user_id" class="form-control">
                                        <option value="">Select Driver</option>
                                        @foreach ($drivers as $driver)
                                            <option value="{{ $driver->id }}">
                                                {{ $driver->first_name }} {{ $driver->last_name }} ({{ $driver->username }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date">Amount:</label>
                                    <input type="number" class="form-control" id="total_amount" name="total_amount" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="salary_daterange">Salary to be included:</label>
                                    <input type="text" class="form-control" id="salary_daterange" name="salary_daterange"
                                        required>
                                    <!-- Hidden inputs to store individual dates -->
                                    <input type="hidden" name="salary_to_be_included_from" id="salary_to_be_included_from">
                                    <input type="hidden" name="salary_to_be_included_to" id="salary_to_be_included_to">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('moar_scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $('#user_id').select2({
                placeholder: "Select a driver",
                allowClear: true
            });
        flatpickr("#salary_daterange", {
            mode: "range",
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    // Set hidden inputs with selected dates
                    document.getElementById('salary_to_be_included_from').value = selectedDates[0].toISOString().split('T')[0];
                    document.getElementById('salary_to_be_included_to').value = selectedDates[1].toISOString().split('T')[0];
                }
            }
        });
    </script>
@stop
