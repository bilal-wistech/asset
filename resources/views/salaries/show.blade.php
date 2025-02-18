@extends('layouts/default')

@section('title')
    View Salaries
    @parent
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="box box-default shadow p-4 rounded">
                <div class="box-header with-border d-flex align-items-center bg-light p-3 rounded"
                    style="display: flex; justify-content: flex-start; border-bottom: 2px solid #ddd;">
                    <!-- Display the salary period in the heading -->
                    <h2 class="box-title text-primary" style="margin-bottom: 15px;">From
                        <strong>{{ $salary->from_date }}</strong> to <strong>{{ $salary->to_date }}</strong>
                    </h2>
                </div>

                <div class="box-body p-4">
                    <form>
                        <!-- Removed the 'Salary Period' row from here -->



                        <div class="box-body p-4">
                            <table class="table table-bordered table-hover bg-white shadow-sm">
                                <tbody>
                                    <tr class="bg-light">
                                        <th class="text-dark w-50">Driver Name</th>
                                        <td class="w-50">{{ $driver->first_name }} {{ $driver->last_name }}
                                            ({{ $driver->username }})</td>
                                    </tr>
                                    <tr>
                                        @php
                                            $baseSalary = $driverSalary->base_salary ?? 0;
                                            $totalPaid = 0;
                                        @endphp
                                        <th class="text-dark w-50">Base Salary</th>
                                        <td class="w-50">{{ number_format($baseSalary, 2) }}</td>
                                    </tr>

                                    <!-- Amount Paid to Riding Companies Section -->
                                    <tr class="bg-light">
                                        <th class="text-dark w-50" colspan="2">Amount Paid To Riding Companies</th>
                                    </tr>

                                    <!-- Uber, Bolt, Ecabs, and Other Companies -->
                                    @foreach ($ridingCompanies as $company)
                                        @php
                                            $salaryRecord = $salaries->firstWhere('riding_company_id', $company->id);
                                            $salaryAmount = $salaryRecord ? $salaryRecord->total_paid : 0;
                                            $totalPaid += $salaryAmount;
                                        @endphp
                                        <tr>
                                            <th class="text-dark w-50">{{ $company->name }}</th>
                                            <td class="w-50">{{ number_format($salaryAmount, 2) }}</td>
                                        </tr>
                                    @endforeach

                                    <!-- Total Paid Row -->
                                    <tr class="bg-light">
                                        <th class="text-dark w-50">Total Paid</th>
                                        <td class="w-50"><strong
                                                class="text-success">{{ number_format($totalPaid, 2) }}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
    @stop
