@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('Create Cash Adjustment') }}
    @parent
@stop

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4"> <!-- Adjust column size for better width -->
                <div class="card shadow-lg p-4">
                    <form action="{{ route('cash-adjustments.store') }}" method="POST">
                        @csrf

                        <!-- Driver Dropdown -->
                        <div class="mb-3">
                            <label for="driver_id" class="form-label">Select Driver</label>
                            <select class="form-control" id="driver_id" name="driver_id" required>
                                <option value="">-- Select a Driver --</option>
                                @foreach ($drivers as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->username }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Amount Input -->
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
