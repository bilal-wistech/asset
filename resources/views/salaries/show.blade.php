@extends('layouts/default')

@section('title')
    View Salaries
    @parent
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Salary Details</h3>
                </div>

                <div class="box-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Driver Name</th>
                                <th>Base Salary</th>
                                @foreach ($ridingCompanies as $company)
                                    <th>{{ $company->name }}</th>
                                @endforeach
                                <th>Total Paid</th>
                                <th>From Date</th>
                                <th>To Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $driver->first_name }} {{ $driver->last_name }} ({{ $driver->username }})</td>

                                @php
                                    $baseSalary = $driverSalary->base_salary ?? 0;
                                    $totalPaid = 0; // Excluding base salary from total
                                @endphp

                                <td>{{ number_format($baseSalary, 2) }}</td>

                                @foreach ($ridingCompanies as $company)
                                    @php
                                        // Find salary for the current company
                                        $salaryRecord = $salaries->firstWhere('riding_company_id', $company->id);
                                        $salaryAmount = $salaryRecord ? $salaryRecord->total_paid : 0;
                                        $totalPaid += $salaryAmount;
                                    @endphp
                                    <td>{{ number_format($salaryAmount, 2) }}</td>
                                @endforeach

                                <td><strong>{{ number_format($totalPaid, 2) }}</strong></td>
                                <td>{{ $salary->from_date }}</td>
                                <td>{{ $salary->to_date }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
