<?php

namespace App\Http\Controllers;

use ExpoSDK\Expo;
use App\Models\User;
use League\Csv\Reader;
use App\Models\Setting;
use ExpoSDK\ExpoMessage;
use App\Models\ExpoToken;
use Illuminate\Http\Request;
use App\Models\DailyEarningReport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Exports\DailyEarningReportsExport;
use App\Notifications\SendPushNotification;
use Illuminate\Support\Facades\Notification;
use Kutia\Larafirebase\Facades\Larafirebase;

class dailyearningreportController extends Controller
{
    //
    public function index()
    {
        return view('dailyearningreport/index');
    }

    public function csvfilecontent(Request $request)
    {
        $request->validate([
            'dereport' => 'required|mimes:csv,txt'
        ]);
        // Retrieve the uploaded CSV file
        $file = $request->file('dereport');
        // Read the CSV file using League\Csv\Reader
        $csv = Reader::createFromPath($file->getPathname(), 'r');
        $csv->setHeaderOffset(0);
        // Initialize an empty array to hold the CSV records
        $records = [];
        // Iterate over the CSV records and convert each record into an associative array
        foreach ($csv->getRecords() as $index => $record) {
            try {
                // Validate and map the CSV data to model fields
                $data = [
                    'courier_id' => $record['Courier ID'],
                    'name' => $record['Name'],
                    'phone' => $record['Phone Number'],
                    'city' => $record['City'],
                    'offline' => $record['Offline?'] === 'Yes',
                    'days_since_last_delivery' => $record['Days since last Delivery'] ?? null,
                    'days_since_last_offload' => $record['Days Since Last Offload'] ?? null,
                    'earnings_without_tips_yesterday' => $record['Earnings without tips yesterday'] ?? null,
                    'hours_online_yesterday' => $record['Hours online yesterday'] ?? null,
                    'hours_on_task_yesterday' => $record['Hours on task yesterday'] ?? null,
                    'cash_balance' => $record['Cash balance'] ?? null,
                ];

                $validator = Validator::make($data, [
                    'courier_id' => 'required',
                    'name' => 'required',
                    'phone' => 'required',
                    'city' => 'required',
                    'offline' => 'required',
                    'days_since_last_delivery' => 'nullable',
                    'days_since_last_offload' => 'nullable',
                    'earnings_without_tips_yesterday' => 'nullable',
                    'hours_online_yesterday' => 'nullable',
                    'hours_on_task_yesterday' => 'nullable',
                    'cash_balance' => 'nullable',
                ]);

                if ($validator->fails()) {
                    throw new \Exception('Validation failed for record at index ' . $index);
                }

                DailyEarningReport::create($data);

                $records[] = $record;
            } catch (\Exception $e) {
                // Log the error and add to errors array
                Log::error('Error processing record at index ' . $index . ': ' . $e->getMessage());
                $errors[] = [
                    'record' => $record,
                    'error' => $e->getMessage(),
                ];
            }
        }
        $setting = Setting::getSettings();
        // Pass the CSV data to the view for displaying in a table
        return view('dailyearningreport/csvfilecontent', ['records' => $records], compact('setting'));
    }

    public function arrayofusers(Request $request)
    {
        // Retrieve the JSON string from the input field
        $highlightedRecordsJson = $request->input('highlightedrecordsinput');

        // Decode the JSON string into an array
        $highlightedRecords = json_decode($highlightedRecordsJson, true);

        // Check if the decoding was successful
        if ($highlightedRecords === null) {
            // Handle the case where decoding failed
            return response()->json(['error' => 'Invalid JSON format'], 400);
        }

        $secondElements = array_map(function ($record) {
            return strstr($record[2], 'knc M', true); // Extract name before 'knc M'
        }, $highlightedRecords);

        $names = $secondElements;
        $userIds = [];
        $notfound = [];

        // Loop through each name
        foreach ($names as $name) {
            $nameParts = explode(' ', $name);
            $firstName = $nameParts[0];
            $lastNameParts = array_slice($nameParts, 1);
            $lastName = implode(' ', $lastNameParts);
            $user = User::where('first_name', $firstName)->where('last_name', $lastName)->first();

            // If user found, add the user ID to the array
            if ($user) {
                $userIds[] = $user->id;
            } else {
                $name = $firstName . ' ' . $lastName;
                $notfound[] = $name;
            }
        }
        $Userdb = User::all();
        $details = [];
        foreach ($userIds as $userid) {
            $user = User::find($userid);
            // Check if the user was found
            if ($user) {
                // Add user details to the $details array
                $details[] = [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    // Add any other user details you need
                ];
            }
        }
        foreach ($userIds as $user_id) {
            // $user_id = 188; this is the test user's id, 
            try {
                $tokens = ExpoToken::where('user_id', $user_id)->pluck('expo_token')->toArray();
                // dd($tokens);
                foreach ($tokens as $token) {
                    //   dd($token);
                    $messages =
                        new ExpoMessage([
                            'title' => 'Cash Limit Exceeded',
                            'body' => 'Kindly Deposit additional funds to the Company.',
                            'data' => [
                                'user_id' => "Test User Amir",
                            ],

                        ]);
                    (new Expo)->send($messages)->to($token)->push();
                }

                //return view('dailyearningreport/sendnotification' ,  ['userindb'=>$userIds,'notindb'=>$notfound,'details'=>$details])->with('success', 'Notification sent successfully!');
            } catch (\Exception $e) {
                report($e);
                //return redirect()->back()->with('error','Something goes wrong while sending notification.');
            }
            return view('dailyearningreport/sendnotification', ['userindb' => $userIds, 'notindb' => $notfound, 'details' => $details]);
        }
    }

    public function sendnotification(Request $request)
    {
        return view('dailyearningsreport/sendnotification');
    }
    public function export(Request $request)
    {
        $query = DailyEarningReport::select('daily_earning_reports.*');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }
        $dailyEarningReports = $query->get();

        // Generate and return Excel download response
        return Excel::download(new DailyEarningReportsExport($dailyEarningReports), 'daily_earning_report.xlsx');
    }
}
