<?php

use App\Http\Controllers\Account;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FineController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\ImportsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\AccidentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ActionlogController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeductionController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ViewAssetsController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\CashHandoverController;
use App\Http\Controllers\StatuslabelsController;
use App\Http\Controllers\DepreciationsController;
use App\Http\Controllers\ManufacturersController;
use App\Http\Controllers\AssetAssignmentController;
use App\Http\Controllers\AssetMaintenancesController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\dailyearningreportController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\RidingCompanyController;


Route::group(['middleware' => 'auth'], function () {

    Route::post('/fcm-token', [UsersController::class, 'updateToken'])->name('fcmToken');

    Route::controller(InsuranceController::class)->group(function () {

        Route::get('/insurance/index', 'index')->name('insurance.index');
        Route::match(['post', 'get'], '/insurance/create', 'create')->name('insurance.create');
        Route::match(['post', 'get'], '/insurance/store', 'store')->name('insurance.store');
        Route::match(['post', 'get'], '/insurance/{insurance_id}/edit', 'edit')->name('insurance.edit');
        Route::match(['post', 'get', 'put'], '/insurance/{insurance_id}/update', 'update')->name('insurance.update');
        Route::get('/insurance/view/{id}', 'view')->name('insurance.view');
        Route::get('/insuranceDetail/{id}', 'showDetail')->name('insurance.showDetail');

        Route::match(['get', 'post'], '/insurance/{insurance_id}/delete', 'destroy')->name('insurance.delete');
        Route::match(['post', 'get'], '/getDriverField', 'getDriverField');
    });

    Route::post('/notifications/update', [InsuranceController::class, 'updatenotification'])->name('notifications.update');
    Route::post('/approve-towing-request', [InsuranceController::class, 'updateFailedTowing']);
    Route::post('insurance/toggleAllDrivers', [InsuranceController::class, 'toggleAllDrivers']);

    Route::get('get-accident-minimum-payment', [InsuranceController::class, 'getAccidentMinimumPayment']);


    //fine
    Route::get('/fines', [FineController::class, 'index'])->name('fines');
    Route::get('/create', [FineController::class, 'create'])->name('fines.create');
    Route::post('/store', [FineController::class, 'store'])->name('fines.store');
    Route::get('/fine/show/{id}', [FineController::class, 'show'])->name('fines.show');
    Route::get('fine/{id}/edit', [FineController::class, 'edit'])->name('fines.edit');
    Route::post('fine/{id}/update', [FineController::class, 'update'])->name('fines.update');
    Route::match(['get', 'post'], 'fine/{id}/delete', [FineController::class, 'destroy'])->name('fines.del');
    Route::get('/fetch-fines', [FineController::class, 'fetchFines'])->name('fetch-fines');
    Route::get('/get-fine-type-amount', [FineController::class, 'getFineTypeAmount'])->name('fine_type.amount');
    //deductions

    Route::get('/deductions', [DeductionController::class, 'index'])->name('deductions');
    Route::get('/deductions/create', [DeductionController::class, 'create'])->name('deductions.create');
    Route::post('/deductions/store', [DeductionController::class, 'store'])->name('deductions.store');
    Route::get('/deductions/show/{id}', [DeductionController::class, 'show'])->name('deductions.show');
    Route::get('/deduction/{id}/edit', [DeductionController::class, 'edit'])->name('deduction.edit');

    Route::post('/deductions/{id}/update', [DeductionController::class, 'update'])->name('deductions.update');
    Route::match(['get', 'post'], '/deductions/{id}/delete', [DeductionController::class, 'destroy'])->name('deductions.delete');
    Route::get('/deductions/fetch-deductions', [DeductionController::class, 'fetchDeductions'])->name('deductions.fetch');
    // accident
    Route::get('/accidents', [AccidentController::class, 'index'])->name('accidents');
    Route::get('/create-accident', [AccidentController::class, 'create'])->name('accidents.create');
    Route::post('/store-accident', [AccidentController::class, 'store'])->name('accidents.store');
    Route::get('/accident/show/{id}', [AccidentController::class, 'show'])->name('accidents.show');
    Route::get('accident/{id}/edit', [AccidentController::class, 'edit'])->name('accidents.edit');
    Route::post('accident/{id}/update', [AccidentController::class, 'update'])->name('accidents.update');
    Route::match(['get', 'post'], 'accident/{id}/delete', [AccidentController::class, 'destroy'])->name('accidents.del');
    Route::get('/fetch-accidents', [AccidentController::class, 'fetchAccidents'])->name('fetch-accidents');
    Route::get('/get-accident-type-amount', [AccidentController::class, 'getFineTypeAmount']);
    Route::get('drivers-ledger', [AccidentController::class, 'payableByDrivers'])->name('drivers-ledger');
    Route::get('/get-user-fine', [AccidentController::class, 'getUserFineBoth'])->name('getUserFine');
    // Route::post('/submit-form', [AccidentController::class, 'SaveReceipt'])->name('form.savereceipt');
    // Receipts
    Route::get('/receipts/print/{id}', [ReceiptController::class, 'printReceipt']);
    Route::get('/receipts/download/{id}', [ReceiptController::class, 'downloadReceipt']);
    Route::post('/get-receipts-details', [ReceiptController::class, 'getReceiptsdetail'])->name('receipts.get');
    Route::get('/invoice/{id}', [ReceiptController::class, 'showInvoice']);
    Route::get('receipts', [ReceiptController::class, 'index'])->name('receipts');
    Route::get('receipts/create', [ReceiptController::class, 'CreateReceipt'])->name('receipts.create');
    Route::post('/store-receipt', [ReceiptController::class, 'store'])->name('form.savereceipt');
    Route::get('/receipt/{id}/edit', [ReceiptController::class, 'edit'])->name('receipt.edit');
    Route::get('/receipt/{id}', [ReceiptController::class, 'GetReceipt']);
    Route::post('/receipts/{id}/update', [ReceiptController::class, 'update'])->name('receipts.update');
    Route::match(['get', 'post'], '/receipts/{id}/delete', [ReceiptController::class, 'destroy'])->name('receipts.delete');
    //cash handover
    Route::get('/cash-handover', [CashHandoverController::class, 'index'])->name('cash-handover');
    Route::get('/cash-handover/create', [CashHandoverController::class, 'create'])->name('cash-handover.create');
    Route::post('cash-handover/store', [CashHandoverController::class, 'store'])
        ->name('cash-handover.store');
    Route::get('cash-handover/{id}/view', [CashHandoverController::class, 'view'])->name('cash-handover.view');
    Route::post('cash-handover/verifiy', [CashHandoverController::class, 'verification'])->name('cash-handover.verifiy');
    Route::get('salaries/fetch-data', [SalaryController::class, 'fetchData'])->name('salaries.fetch-data');
    Route::post('salaries/update-driver-salary', [SalaryController::class, 'updateDriverSalary'])
        ->name('salaries.update-driver-salary');
    Route::resource('salaries', SalaryController::class);
   // Route::resource('riding-companies', RidingCompanyController::class)->except('show');
   
    // Display the list of riding companies (Index)
    Route::get('/riding-companies', [RidingCompanyController::class, 'index'])->name('riding-companies.index');

    // Show the form to create a new riding company (Create)
    Route::get('/riding-companies/create', [RidingCompanyController::class, 'create'])->name('riding-companies.create');

    // Store a newly created riding company (Store)
    Route::post('/riding-companies', [RidingCompanyController::class, 'store'])->name('riding-companies.store');

    // Show the form to edit an existing riding company (Edit)
    Route::get('/riding-companies/{riding_company}/edit', [RidingCompanyController::class, 'edit'])->name('riding-companies.edit');

    // Update an existing riding company (Update)
    Route::put('/riding-companies/{riding_company}', [RidingCompanyController::class, 'update'])->name('riding-companies.update');
    Route::patch('/riding-companies/{riding_company}', [RidingCompanyController::class, 'update'])->name('riding-companies.update'); // Optional

    // Delete a riding company (Destroy)
    Route::delete('/riding-companies/{riding_company}', [RidingCompanyController::class, 'destroy'])->name('riding-companies.destroy');


    Route::post(
        '{item_id}/upload',
        [
            App\Http\Controllers\Users\UserFilesController::class,
            'store'
        ]
    )->name('upload/ins')->middleware('auth');
    Route::delete('{userId}/delete', [
        App\Http\Controllers\Users\UserFilesController::class,
        'destroy'
    ])->name('file.destroy')->middleware('auth');

    Route::controller(App\Http\Controllers\Assets\BulkAssetsController::class)->group(function () {
        Route::match(['post', 'get'], '/bulkcheckout/store', 'customStore')->name('bulkcheckout.store');
        Route::match(['post', 'get'], '/bulkcheckout/{id}/edit', 'customEdit')->name('bulkcheckout.edit');
        Route::match(['post', 'get'], '/getAllowedUsers', 'getAllowedUsers');
        Route::match(['post', 'get'], '/runMigration', 'runMigration');
    });

    Route::controller(App\Http\Controllers\TypeOfExpenceController::class)->group(function () {
        Route::get('/expence', 'index')->name('expence.index');
        Route::get('/expence/create', 'create')->name('expence.create');
        Route::match(['post', 'get'], 'expensetype/{id}/edit', 'update')->name('expence.update');
        Route::match(['post', 'put'], 'expensetype/{id}/updateData', 'updateData')->name('expence.updateData');
        Route::match(['post', 'put', 'delete'], 'expensetype/{id}/delete', 'delete')->name('expence.delete');
        Route::post('/expence/store', 'store')->name('expence.store');
    });

    Route::controller(App\Http\Controllers\Assets\AssetCheckinController::class)->group(function () {
        Route::get('/reason/index', 'reasonIndex')->name('reason.index');
        Route::get('/reason/create', 'reasonCreate')->name('reason.create');
        Route::post('/reason/store', 'reasonStore')->name('reason.store');
    });

    Route::controller(App\Http\Controllers\dailyearningreportController::class)->group(function () {
        Route::get('/dailyearningreport', 'index')->name('dailyearningreport.index');
        Route::post('/dailyearningreport/csvfilecontent', 'csvfilecontent')->name('dailyearningreport.csvfilecontent');
        // Route::get('/dailyearningreport/csvfilecontent/getuserids','getuserids')->name('getuser.ids');
        Route::post('/arrayofusers', 'arrayofusers')->name('array.users');
        Route::post('/dailyearningreport/sendnotification', 'sendnotification')->name('send.notification');
        // Route::post('/usersimportedfromcsv','sendnoti')->name('send.notification');
        Route::get('/dailyearningreport/create', 'create')->name('dailyearningreport.create');
        Route::post('/dailyearningreport/import', 'import')->name('dailyearningreport.import');
        Route::match(['post', 'get'], 'dailyearningreport/{id}/edit', 'update')->name('dailyearningreport.update');
        Route::match(
            ['post', 'put'],
            'dailyearningreport/{id}/updateData',
            'updateData'
        )->name('dailyearningreport.updateData');
        Route::match(
            ['post', 'put', 'delete'],
            'dailyearningreport/{id}/delete',
            'delete'
        )->name('dailyearningreport.delete');
        Route::post('/dailyearningreport/store', 'store')->name('dailyearningreport.store');
    });
    // Route::get(
    //     '/show',
    //     [App\Http\Controllers\REgridController::class, 'show']
    // )->name('grid.show');

    Route::controller(AssetAssignmentController::class)->group(function () {
        Route::get('/asset-assignment', 'index')->name('asset-assignment');
        Route::match(['post', 'get'], '/asset-assignment/create', 'create')->name('asset-assignment.create');
        Route::match(['post', 'get'], '/asset-assignment/store', 'store')->name('asset-assignment.store');
        Route::match(['post', 'get'], '/assetassignment/{id}/edit', 'edit')->name('asset-assignment.edit');
        Route::match(
            ['post', 'get', 'put'],
            '/asset-assignment/{id}/update',
            'update'
        )->name('asset-assignment.update');
        Route::get('/asset-assignment/view/{id}', 'view')->name('asset-assignment.view');

        Route::match(['post', 'get'], '/getAllowedDrivers', 'getAllowedDrivers');
    });
    Route::get(
        '/handover-details',
        [AssetAssignmentController::class, 'handoverDetails']
    )->name('handover-details');

    // Repair Options Routes
    Route::controller(App\Http\Controllers\tsrepairoptionsController::class)->group(function () {
        Route::get('/repairoptions', 'index')->name('tsrepairoptions.index');
        Route::get('/repairoptions/create', 'create')->name('tsrepairoptions.create');
        Route::match(['post', 'get'], 'tsrepairoptions/{id}/edit', 'update')->name('tsrepairoptions.update');
        Route::match(
            ['post', 'put'],
            'repairoptions/{id}/updateData',
            'updateData'
        )->name('tsrepairoptions.updateData');
        Route::match(
            ['post', 'put', 'delete'],
            'tsrepairoptions/{id}/delete',
            'delete'
        )->name('tsrepairoptions.delete');
        Route::post('/repairoptions/store', 'store')->name('tsrepairoptions.store');
    });

    /*
    * Companies
    */
    Route::resource('companies', CompaniesController::class, [
        'parameters' => ['company' => 'company_id'],
    ]);

    /*
    * Categories
    */
    Route::resource('categories', CategoriesController::class, [
        'parameters' => ['category' => 'category_id'],
    ]);


    /*
     * Locations
     */

    Route::group(['prefix' => 'locations', 'middleware' => ['auth']], function () {

        Route::get(
            '{locationId}/clone',
            [LocationsController::class, 'getClone']
        )->name('clone/license');

        Route::get(
            '{locationId}/printassigned',
            [LocationsController::class, 'print_assigned']
        )->name('locations.print_assigned');

        Route::get(
            '{locationId}/printallassigned',
            [LocationsController::class, 'print_all_assigned']
        )->name('locations.print_all_assigned');
    });

    Route::resource('locations', LocationsController::class, [
        'parameters' => ['location' => 'location_id'],
    ]);


    /*
    * Manufacturers
    */

    Route::group(['prefix' => 'manufacturers', 'middleware' => ['auth']], function () {
        Route::post(
            '{manufacturers_id}/restore',
            [ManufacturersController::class, 'restore']
        )->name('restore/manufacturer');
    });

    Route::resource('manufacturers', ManufacturersController::class, [
        'parameters' => ['manufacturer' => 'manufacturers_id'],
    ]);

    /*
    * Suppliers
    */
    Route::resource('suppliers', SuppliersController::class, [
        'parameters' => ['supplier' => 'supplier_id'],
    ]);

    /*
    * Depreciations
     */
    Route::resource('depreciations', DepreciationsController::class, [
        'parameters' => ['depreciation' => 'depreciation_id'],
    ]);

    /*
    * Status Labels
     */
    Route::resource('statuslabels', StatuslabelsController::class, [
        'parameters' => ['statuslabel' => 'statuslabel_id'],
    ]);

    /*
    * Departments
    */
    Route::resource('departments', DepartmentsController::class, [
        'parameters' => ['department' => 'department_id'],
    ]);
});

/*
|
|--------------------------------------------------------------------------
| Re-Usable Modal Dialog routes.
|--------------------------------------------------------------------------
|
| Routes for various modal dialogs to interstitially create various things
|
*/

Route::group(['middleware' => 'auth', 'prefix' => 'modals'], function () {
    Route::get('{type}/{itemId?}', [ModalController::class, 'show'])->name('modal.show');
});

/*
|--------------------------------------------------------------------------
| Log Routes
|--------------------------------------------------------------------------
|
| Register all the admin routes.
|
*/

Route::group(['middleware' => 'auth'], function () {
    Route::get(
        'display-sig/{filename}',
        [ActionlogController::class, 'displaySig']
    )->name('log.signature.view');
    Route::get(
        'stored-eula-file/{filename}',
        [ActionlogController::class, 'getStoredEula']
    )->name('log.storedeula.download');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Register all the admin routes.
|
*/

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'authorize:superuser']], function () {
    Route::get('settings', [SettingsController::class, 'getSettings'])->name('settings.general.index');
    Route::post('settings', [SettingsController::class, 'postSettings'])->name('settings.general.save');

    Route::get('branding', [SettingsController::class, 'getBranding'])->name('settings.branding.index');
    Route::post('branding', [SettingsController::class, 'postBranding'])->name('settings.branding.save');

    Route::get('security', [SettingsController::class, 'getSecurity'])->name('settings.security.index');
    Route::post('security', [SettingsController::class, 'postSecurity'])->name('settings.security.save');

    Route::get('groups', [GroupsController::class, 'index'])->name('settings.groups.index');

    Route::get('localization', [SettingsController::class, 'getLocalization'])->name('settings.localization.index');
    Route::post('localization', [SettingsController::class, 'postLocalization'])->name('settings.localization.save');

    Route::get('notifications', [SettingsController::class, 'getAlerts'])->name('settings.alerts.index');
    Route::post('notifications', [SettingsController::class, 'postAlerts'])->name('settings.alerts.save');

    Route::get('slack', [SettingsController::class, 'getSlack'])->name('settings.slack.index');
    Route::post('slack', [SettingsController::class, 'postSlack'])->name('settings.slack.save');

    Route::get('asset_tags', [SettingsController::class, 'getAssetTags'])->name('settings.asset_tags.index');
    Route::post('asset_tags', [SettingsController::class, 'postAssetTags'])->name('settings.asset_tags.save');

    Route::get('barcodes', [SettingsController::class, 'getBarcodes'])->name('settings.barcodes.index');
    Route::post('barcodes', [SettingsController::class, 'postBarcodes'])->name('settings.barcodes.save');

    Route::get('cashlimit', [SettingsController::class, 'getcashlimit'])->name('settings.cashlimit.index');
    Route::post('cashlimit', [SettingsController::class, 'postcashlimit'])->name('settings.cashlimit.save');


    Route::get('labels', [SettingsController::class, 'getLabels'])->name('settings.labels.index');
    Route::post('labels', [SettingsController::class, 'postLabels'])->name('settings.labels.save');

    Route::get('ldap', [SettingsController::class, 'getLdapSettings'])->name('settings.ldap.index');
    Route::post('ldap', [SettingsController::class, 'postLdapSettings'])->name('settings.ldap.save');

    Route::get('phpinfo', [SettingsController::class, 'getPhpInfo'])->name('settings.phpinfo.index');

    Route::get('oauth', [SettingsController::class, 'api'])->name('settings.oauth.index');

    Route::get('purge', [SettingsController::class, 'getPurge'])->name('settings.purge.index');
    Route::post('purge', [SettingsController::class, 'postPurge'])->name('settings.purge.save');

    Route::get('login-attempts', [SettingsController::class, 'getLoginAttempts'])->name('settings.logins.index');

    // Backups
    Route::group(['prefix' => 'backups', 'middleware' => 'auth'], function () {
        Route::get(
            'download/{filename}',
            [SettingsController::class, 'downloadFile']
        )->name('settings.backups.download');

        Route::delete(
            'delete/{filename}',
            [SettingsController::class, 'deleteFile']
        )->name('settings.backups.destroy');

        Route::post(
            '/',
            [SettingsController::class, 'postBackups']
        )->name('settings.backups.create');

        Route::post(
            '/restore/{filename}',
            [SettingsController::class, 'postRestore']
        )->name('settings.backups.restore');

        Route::post(
            '/upload',
            [SettingsController::class, 'postUploadBackup']
        )->name('settings.backups.upload');

        Route::get('/', [SettingsController::class, 'getBackups'])->name('settings.backups.index');
    });

    Route::resource('groups', GroupsController::class, [
        'middleware' => ['auth'],
        'parameters' => ['group' => 'group_id'],
    ]);

    Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
});

/*
|--------------------------------------------------------------------------
| Importer Routes
|--------------------------------------------------------------------------
|
|
|
*/

Route::get(
    '/import',
    \App\Http\Livewire\Importer::class
)->middleware('auth')->name('imports.index');

/*
|--------------------------------------------------------------------------
| Account Routes
|--------------------------------------------------------------------------
|
|
|
*/
Route::group(['prefix' => 'account', 'middleware' => ['auth']], function () {

    // Profile
    Route::get('profile', [ProfileController::class, 'getIndex'])->name('profile');
    Route::post('profile', [ProfileController::class, 'postIndex']);

    Route::get('menu', [ProfileController::class, 'getMenuState'])->name('account.menuprefs');

    Route::get('password', [ProfileController::class, 'password'])->name('account.password.index');
    Route::post('password', [ProfileController::class, 'passwordSave']);

    Route::get('api', [ProfileController::class, 'api'])->name('user.api');

    // View Assets
    Route::get('view-assets', [ViewAssetsController::class, 'getIndex'])->name('view-assets');

    Route::get('requested', [ViewAssetsController::class, 'getRequestedAssets'])->name('account.requested');

    // Profile
    Route::get(
        'requestable-assets',
        [ViewAssetsController::class, 'getRequestableIndex']
    )->name('requestable-assets');
    Route::post(
        'request-asset/{assetId}',
        [ViewAssetsController::class, 'getRequestAsset']
    )->name('account/request-asset');

    Route::post(
        'request/{itemType}/{itemId}',
        [ViewAssetsController::class, 'getRequestItem']
    )->name('account/request-item');

    // Account Dashboard
    Route::get('/', [ViewAssetsController::class, 'getIndex'])->name('account');

    Route::get('accept', [Account\AcceptanceController::class, 'index'])
        ->name('account.accept');

    Route::get('accept/{id}', [Account\AcceptanceController::class, 'create'])
        ->name('account.accept.item');

    Route::post('accept/{id}', [Account\AcceptanceController::class, 'store']);

    Route::get(
        'print',
        [
            ProfileController::class,
            'printInventory'
        ]
    )->name('profile.print');

    Route::post(
        'email',
        [
            ProfileController::class,
            'emailAssetList'
        ]
    )->name('profile.email_assets');
});
//hand over asset feature for user panal


Route::group(['middleware' => ['auth']], function () {
    Route::get(
        'reports/audit',
        [ReportsController::class, 'audit']
    )->name('reports.audit');

    Route::get(
        'reports/depreciation',
        [ReportsController::class, 'getDeprecationReport']
    )->name('reports/depreciation');
    Route::get(
        'reports/export/depreciation',
        [ReportsController::class, 'exportDeprecationReport']
    )->name('reports/export/depreciation');
    Route::get(
        'reports/asset_maintenances',
        [ReportsController::class, 'getAssetMaintenancesReport']
    )->name('reports/asset_maintenances');
    Route::get(
        'reports/export/asset_maintenances',
        [AssetMaintenancesController::class, 'export']
    )->name('reports/export/asset_maintenances');
    Route::get(
        'reports/licenses',
        [ReportsController::class, 'getLicenseReport']
    )->name('reports/licenses');
    Route::get(
        'reports/daily-earning',
        [ReportsController::class, 'getDailyEarningReport']
    )->name('reports/daily-earning');
    Route::get(
        'reports/export/daily-earning',
        [dailyearningreportController::class, 'export']
    )->name('reports/export/daily-earning');
    Route::get(
        'reports/export/licenses',
        [ReportsController::class, 'exportLicenseReport']
    )->name('reports/export/licenses');

    Route::get('reports/accessories', [ReportsController::class, 'getAccessoryReport'])->name('reports/accessories');
    Route::get(
        'reports/export/accessories',
        [ReportsController::class, 'exportAccessoryReport']
    )->name('reports/export/accessories');
    Route::get('reports/custom', [ReportsController::class, 'getCustomReport'])->name('reports/custom');
    Route::post('reports/custom', [ReportsController::class, 'postCustom']);

    Route::get(
        'reports/activity',
        [ReportsController::class, 'getActivityReport']
    )->name('reports.activity');

    Route::post('reports/activity', [ReportsController::class, 'postActivityReport']);

    Route::get(
        'reports/unaccepted_assets/{deleted?}',
        [ReportsController::class, 'getAssetAcceptanceReport']
    )->name('reports/unaccepted_assets');
    Route::get(
        'reports/unaccepted_assets/{acceptanceId}/sent_reminder',
        [ReportsController::class, 'sentAssetAcceptanceReminder']
    )->name('reports/unaccepted_assets_sent_reminder');
    Route::delete(
        'reports/unaccepted_assets/{acceptanceId}/delete',
        [ReportsController::class, 'deleteAssetAcceptance']
    )->name('reports/unaccepted_assets_delete');
    Route::post(
        'reports/unaccepted_assets/{deleted?}',
        [ReportsController::class, 'postAssetAcceptanceReport']
    )->name('reports/export/unaccepted_assets');
});

Route::get(
    'auth/signin',
    [LoginController::class, 'legacyAuthRedirect']
);


/*
|--------------------------------------------------------------------------
| Setup Routes
|--------------------------------------------------------------------------
|
|
|
*/
Route::group(['prefix' => 'setup', 'middleware' => 'web'], function () {
    Route::get(
        'user',
        [SettingsController::class, 'getSetupUser']
    )->name('setup.user');

    Route::post(
        'user',
        [SettingsController::class, 'postSaveFirstAdmin']
    )->name('setup.user.save');


    Route::get(
        'migrate',
        [SettingsController::class, 'getSetupMigrate']
    )->name('setup.migrate');

    Route::get(
        'done',
        [SettingsController::class, 'getSetupDone']
    )->name('setup.done');

    Route::get(
        'mailtest',
        [SettingsController::class, 'ajaxTestEmail']
    )->name('setup.mailtest');

    Route::get(
        '/',
        [SettingsController::class, 'getSetupIndex']
    )->name('setup');
});


Route::group(['middleware' => 'web'], function () {

    Route::get(
        'login',
        [LoginController::class, 'showLoginForm']
    )->name("login");

    Route::post(
        'login',
        [LoginController::class, 'login']
    );

    Route::get(
        'two-factor-enroll',
        [LoginController::class, 'getTwoFactorEnroll']
    )->name('two-factor-enroll');

    Route::get(
        'two-factor',
        [LoginController::class, 'getTwoFactorAuth']
    )->name('two-factor');

    Route::post(
        'two-factor',
        [LoginController::class, 'postTwoFactorAuth']
    );


    Route::post(
        'password/email',
        [ForgotPasswordController::class, 'sendResetLinkEmail']
    )->name('password.email')->middleware('throttle:forgotten_password');

    Route::get(
        'password/reset',
        [ForgotPasswordController::class, 'showLinkRequestForm']
    )->name('password.request')->middleware('throttle:forgotten_password');


    Route::post(
        'password/reset',
        [ResetPasswordController::class, 'reset']
    )->name('password.update')->middleware('throttle:forgotten_password');

    Route::get(
        'password/reset/{token}',
        [ResetPasswordController::class, 'showResetForm']
    )->name('password.reset');


    Route::post(
        'password/email',
        [ForgotPasswordController::class, 'sendResetLinkEmail']
    )->name('password.email')->middleware('throttle:forgotten_password');


    Route::get(
        '/',
        [
            'as' => 'home',
            'middleware' => ['auth'],
            'uses' => 'DashboardController@getIndex'
        ]
    );

    // need to keep GET /logout for SAML SLO
    Route::get(
        'logout',
        [LoginController::class, 'logout']
    )->name('logout.get');

    Route::post(
        'logout',
        [LoginController::class, 'logout']
    )->name('logout.post');
});

//Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::controller(DocumentController::class)->group(function () {

        Route::get('/document/index', 'index')->name('document.index');
        Route::post('/getExpiryData', 'getExpiryData')->name('getExpiryData');
    });
});

Route::get(
    '/health',
    [HealthController::class, 'get']
)->name('health');

Route::middleware(['auth'])->get(
    '/',
    [DashboardController::class, 'index']
)->name('home');

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    return "Cleared!";
});
