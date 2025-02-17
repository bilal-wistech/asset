<?php

namespace App\Http\Controllers;

use App\Models\DriverSalary;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Salary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RidingCompany;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('salaries.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ridingCompanies = RidingCompany::all();
        $group_id = [2, 3];
        $drivers = User::join('users_groups', 'users.id', '=', 'users_groups.user_id')
            ->whereIn('users_groups.group_id', $group_id)
            ->select('users.id', 'users.username', 'users.first_name', 'users.last_name')
            ->get();

        return view('salaries.create', compact('ridingCompanies', 'drivers'));
    }

    public function fetchData(Request $request)
    {
        try {
            $validated = $request->validate([
                'from_date' => 'required|date',
                'to_date' => 'required|date|after_or_equal:from_date',
                'driver_id' => 'nullable|exists:users,id',
                'incomplete' => 'nullable|in:incomplete'
            ]);

            $fromDate = Carbon::parse($validated['from_date']);
            $toDate = Carbon::parse($validated['to_date']);

            // Only check for existing data if we're not looking for incomplete entries
            if (empty($validated['incomplete'])) {
                $existingData = Salary::whereBetween('from_date', [$fromDate, $toDate])
                    ->orWhereBetween('to_date', [$fromDate, $toDate])
                    ->exists();

                if ($existingData) {
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'Data already exists for the selected date range'
                    ]);
                }
            }

            $group_id = [2, 3];
            $driversQuery = User::join('users_groups', 'users.id', '=', 'users_groups.user_id')
                ->whereIn('users_groups.group_id', $group_id)
                ->select('users.id', 'users.username as name', 'users.first_name as fname', 'users.last_name as lname');

            // Filter by selected driver if provided
            if (!empty($validated['driver_id'])) {
                $driversQuery->where('users.id', $validated['driver_id']);
            }

            $drivers = $driversQuery->get();
            $ridingCompanies = RidingCompany::select('id', 'name')->get();

            // Get all salaries for the date range
            $salaries = Salary::whereBetween('from_date', [$fromDate, $toDate])
                ->whereBetween('to_date', [$fromDate, $toDate])
                ->get()
                ->groupBy(['driver_id', 'riding_company_id']);

            // Get driver base salaries
            $driverSalaries = DriverSalary::whereIn('driver_id', $drivers->pluck('id'))
                ->whereBetween('from_date', [$fromDate, $toDate])
                ->whereBetween('to_date', [$fromDate, $toDate])
                ->get()
                ->keyBy('driver_id');

            // If incomplete filter is active, filter drivers with any missing salary
            if (!empty($validated['incomplete'])) {
                $driversWithIncomplete = $drivers->filter(function ($driver) use ($salaries, $ridingCompanies, $driverSalaries) {
                    // Check if base salary is missing or empty
                    if (
                        !isset($driverSalaries[$driver->id]) ||
                        $driverSalaries[$driver->id]->base_salary === null ||
                        $driverSalaries[$driver->id]->base_salary === ''
                    ) {
                        return true;
                    }

                    // Check if any company's salary is missing or empty
                    foreach ($ridingCompanies as $company) {
                        $companySalaries = $salaries[$driver->id][$company->id] ?? [];

                        if (
                            empty($companySalaries) ||
                            !isset($companySalaries[0]) ||
                            $companySalaries[0]->amount_paid === null ||
                            $companySalaries[0]->amount_paid === ''
                        ) {
                            return true;
                        }
                    }
                    return false;
                });

                $drivers = $driversWithIncomplete->values(); // Reset array keys
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'drivers' => $drivers,
                    'ridingCompanies' => $ridingCompanies,
                    'salaries' => $salaries,
                    'driverSalaries' => $driverSalaries
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in fetchData: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching data'
            ], 500);
        }
    }

    public function updateDriverSalary(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:users,id',
            'base_salary' => 'required|numeric|min:0',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        try {
            // Create or update salary record
            DriverSalary::updateOrCreate(
                ['driver_id' => $validated['driver_id']],
                [
                    'base_salary' => $validated['base_salary'],
                    'from_date' => $validated['from_date'],
                    'to_date' => $validated['to_date']
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Base salary updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating base salary: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:users,id', // Changed from drivers to users
            'riding_company_id' => 'required|exists:riding_companies,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'amount_paid' => 'required|numeric|min:0'
        ]);

        try {
            $salary = Salary::updateOrCreate(
                [
                    'driver_id' => $validated['driver_id'],
                    'riding_company_id' => $validated['riding_company_id'],
                    'from_date' => $validated['from_date'],
                    'to_date' => $validated['to_date'],
                ],
                [
                    'amount_paid' => $validated['amount_paid'],
                    'user_id' => Auth::user()->id
                ]
            );

            return response()->json([
                'success' => true,
                'salary' => $salary
            ]);
        } catch (\Exception $e) {
            \Log::error('Salary store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving salary'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function show($id)

        {
            $salary = Salary::findOrFail($id); // Get the salary or fail with 404

             $ridingCompanies = RidingCompany::all(); // Get all riding companies

             $driver = User::findOrFail($salary->driver_id); // Get driver details

            // Get the driver's base salary for the given period
            $driverSalary = DriverSalary::where('driver_id', $salary->driver_id)
                ->where('from_date', $salary->from_date)
                ->where('to_date', $salary->to_date)
                ->first();

                //dd($driverSalary);


        // Get payments grouped by company_id for the specific driver and period
            $salaries = Salary::where('driver_id', $salary->driver_id)
            ->where('from_date', $salary->from_date)
            ->where('to_date', $salary->to_date)
            ->select('riding_company_id', DB::raw('SUM(amount_paid) as total_paid'))
            ->groupBy('riding_company_id')
            ->get();

            //dd($salaries);

        // Get selected companies that have paid the driver
        $selectedCompanies = Salary::where('driver_id', $salary->driver_id)
            ->where('from_date', $salary->from_date)
            ->where('to_date', $salary->to_date)
            ->get();


                //dd($salary, $driverSalary, $salaries, $selectedCompanies);

            return view('salaries.show', compact('salary', 'salaries', 'ridingCompanies', 'driverSalary', 'driver', 'selectedCompanies'));
        }

        

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $salary = Salary::where('id', $id)->first();

        if (!$salary) {
            abort(404, "Salary not found.");
        }
        $ridingCompanies = RidingCompany::all();

        $driverSalary = DriverSalary::where('driver_id', $salary->driver_id)
            ->where('from_date', $salary->from_date)
            ->where('to_date', $salary->to_date)
            ->first();
        $driver = User::where('id', $salary->driver_id)->first();
        $salaries = DriverSalary::whereIn('driver_id', $ridingCompanies->pluck('id'))
            ->where('from_date', $salary->from_date)
            ->where('to_date', $salary->to_date)
            ->get()
            ->groupBy(function ($item) {
                return $item->driver_id . '.' . $item->company_id;
            });
          //  $ridingSelectedCompany = RidingCompany::where('id', $salary->riding_company_id)->first();
            $selectedCompanies = Salary::where('driver_id', $salary->driver_id)
            ->where('from_date', $salary->from_date)
            ->where('to_date', $salary->to_date)
            ->get();
        return view('salaries.edit', compact('salary','salaries', 'ridingCompanies', 'driverSalary', 'driver', 'selectedCompanies'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salary $salary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salary $salary)
    {
        //
    }
}