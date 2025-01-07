<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Fine;
use App\Models\User;
use App\Models\Asset;
use App\Models\Company;
use App\Models\License;
use App\Models\Accident;
use App\Models\Receipt;
use App\Models\Deduction;
use App\Models\Category;
use App\Models\Location;
use App\Models\Supplier;
use App\Models\Accessory;
use App\Models\Component;
use App\Models\Insurance;
use App\Models\AssetModel;
use App\Models\Consumable;
use App\Models\Department;
use App\Models\CustomField;
use App\Models\Statuslabel;
use App\Models\Depreciation;
use App\Models\Manufacturer;
use App\Policies\FinePolicy;
use App\Policies\UserPolicy;
use App\Models\PredefinedKit;
use App\Models\TowingRequest;
use App\Models\TypeOfExpence;
use App\Policies\AssetPolicy;
use App\Models\CustomFieldset;
use App\Policies\TowingPolicy;
use Laravel\Passport\Passport;
use App\Models\AssetAssignment;
use App\Policies\CompanyPolicy;
use App\Policies\LicensePolicy;
use App\Policies\AccidentPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\LocationPolicy;
use App\Policies\SupplierPolicy;
use App\Policies\AccessoryPolicy;
use App\Policies\ComponentPolicy;
use App\Policies\ReExpencePolicy;
use App\Policies\AssetModelPolicy;
use App\Policies\ConsumablePolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\CustomFieldPolicy;
use App\Policies\StatuslabelPolicy;
use App\Policies\DepreciationPolicy;
use App\Policies\ManufacturerPolicy;
use Illuminate\Support\Facades\Gate;
use App\Policies\PredefinedKitPolicy;
use App\Policies\TypeOfExpencePolicy;
use App\Policies\AssetInsurancePolicy;
use App\Policies\CustomFieldsetPolicy;
use App\Policies\AssetAssignmentPolicy;
use App\Policies\ReceiptPolicy;
use App\Policies\DeductionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * See SnipePermissionsPolicy for additional information.
     *
     * @var array
     */
    protected $policies = [
        Accessory::class => AccessoryPolicy::class,
        Asset::class => AssetPolicy::class,
        AssetModel::class => AssetModelPolicy::class,
        Category::class => CategoryPolicy::class,
        Component::class => ComponentPolicy::class,
        Consumable::class => ConsumablePolicy::class,
        CustomField::class => CustomFieldPolicy::class,
        CustomFieldset::class => CustomFieldsetPolicy::class,
        Department::class => DepartmentPolicy::class,
        Depreciation::class => DepreciationPolicy::class,
        License::class => LicensePolicy::class,
        Location::class => LocationPolicy::class,
        PredefinedKit::class => PredefinedKitPolicy::class,
        Statuslabel::class => StatuslabelPolicy::class,
        Supplier::class => SupplierPolicy::class,
        User::class => UserPolicy::class,
        Manufacturer::class => ManufacturerPolicy::class,
        Company::class => CompanyPolicy::class,
        AssetAssignment::class => AssetAssignmentPolicy::class,
        Insurance::class => AssetInsurancePolicy::class,
        TypeOfExpence::class => TypeOfExpencePolicy::class,
        //        Fine::class => FinePolicy::class,
        //        Accident::class => AccidentPolicy::class,
        TowingRequest::class => TowingPolicy::class,
        //        Deduction::class => DeductionPolicy::class,
        //        Receipt::class => ReceiptPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            \Laravel\Passport\Console\InstallCommand::class,
            \Laravel\Passport\Console\ClientCommand::class,
            \Laravel\Passport\Console\KeysCommand::class,
        ]);

        $this->registerPolicies();
        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addYears(config('passport.expiration_years')));
        Passport::refreshTokensExpireIn(Carbon::now()->addYears(config('passport.expiration_years')));
        Passport::personalAccessTokensExpireIn(Carbon::now()->addYears(config('passport.expiration_years')));
        Passport::withCookieSerialization();

        // --------------------------------
        // BEFORE ANYTHING ELSE
        // --------------------------------
        // If this condition is true, ANYTHING else below will be assumed
        // to be true. This can cause weird blade behavior.
        Gate::before(function ($user) {
            //            dd($user);
            if ($user->isSuperUser()) {
                return true;
            }
        });

        // --------------------------------
        // GENERAL GATES
        // These control general sections of the admin
        // --------------------------------
        Gate::define('admin', function ($user) {
            if ($user->hasAccess('admin')) {
                return true;
            }
        });

        Gate::define('accessories.files', function ($user) {
            if ($user->hasAccess('accessories.files')) {
                return true;
            }
        });

        Gate::define('components.files', function ($user) {
            if ($user->hasAccess('components.files')) {
                return true;
            }
        });

        Gate::define('consumables.files', function ($user) {
            if ($user->hasAccess('consumables.files')) {
                return true;
            }
        });

        // Can the user import CSVs?
        Gate::define('import', function ($user) {
            if ($user->hasAccess('import')) {
                return true;
            }
        });


        Gate::define('licenses.files', function ($user) {
            if ($user->hasAccess('licenses.files')) {
                return true;
            }
        });
        // access for the dashboard
        Gate::define('dashboard', function ($user) {
            if ($user->hasAccess('dashboard')) {
                return true;
            }
        });
        // access for the reimmensible expense
        Gate::define('add_expences.view', function ($user) {
            if ($user->hasAccess('add_expences.view')) {
                return true;
            }
        });
        // access for the Towing Request
        Gate::define('towing_requests.view', function ($user) {
            if ($user->hasAccess('towing_requests.view')) {
                return true;
            }
        });
        // access for the assets handover details
        Gate::define('handover-details', function ($user) {
            if ($user->hasAccess('handover-details')) {
                return true;
            }
        });

        // -----------------------------------------
        // Reports
        // -----------------------------------------
        Gate::define('reports.view', function ($user) {
            if ($user->hasAccess('reports.view')) {
                return true;
            }
        });

        // -----------------------------------------
        // Self
        // -----------------------------------------
        Gate::define('self.two_factor', function ($user) {
            if (($user->hasAccess('self.two_factor')) || ($user->hasAccess('admin'))) {
                return true;
            }
        });

        Gate::define('self.api', function ($user) {
            return $user->hasAccess('self.api');
        });

        Gate::define('self.edit_location', function ($user) {
            return $user->hasAccess('self.edit_location');
        });

        Gate::define('self.checkout_assets', function ($user) {
            return $user->hasAccess('self.checkout_assets');
        });

        Gate::define('self.view_purchase_cost', function ($user) {
            return $user->hasAccess('self.view_purchase_cost');
        });

        // This is largely used to determine whether to display the gear icon sidenav
        // in the left-side navigation
        Gate::define('backend.interact', function ($user) {
            return $user->can('view', Statuslabel::class)
                || $user->can('view', AssetModel::class)
                || $user->can('view', Category::class)
                || $user->can('view', Manufacturer::class)
                || $user->can('view', Supplier::class)
                || $user->can('view', Department::class)
                || $user->can('view', Location::class)
                || $user->can('view', Company::class)
                || $user->can('view', Manufacturer::class)
                || $user->can('view', CustomField::class)
                || $user->can('view', CustomFieldset::class)
                || $user->can('view', Depreciation::class);
        });


        // This  determines whether or not an API user should be able to get the selectlists.
        // This can seem a little confusing, since view properties may not have been granted
        // to the logged in API user, but creating assets, licenses, etc won't work
        // if the user can't view and interact with the select lists.
        Gate::define('view.selectlists', function ($user) {
            return $user->can('update', Asset::class)
                || $user->can('create', Asset::class)
                || $user->can('checkout', Asset::class)
                || $user->can('checkin', Asset::class)
                || $user->can('audit', Asset::class)
                || $user->can('update', License::class)
                || $user->can('create', License::class)
                || $user->can('update', Component::class)
                || $user->can('create', Component::class)
                || $user->can('update', Consumable::class)
                || $user->can('create', Consumable::class)
                || $user->can('update', Accessory::class)
                || $user->can('create', Accessory::class)
                || $user->can('update', User::class)
                || $user->can('create', User::class);
        });
        Gate::define('view.fines', function ($user) {
            return $user->hasAccess('fines');
        });
        Gate::define('edit.fines', function ($user) {
            return $user->hasAccess('edit.fines');
        });
        Gate::define('delete.fines', function ($user) {
            return $user->hasAccess('delete.fines');
        });
        Gate::define('accidents', function ($user) {
            return $user->hasAccess('accidents');
        });
        Gate::define('accidents.create', function ($user) {
            return $user->hasAccess('accidents.create');
        });
        Gate::define('accidents.edit', function ($user) {
            return $user->hasAccess('accidents.edit');
        });
        Gate::define('accidents.delete', function ($user) {
            return $user->hasAccess('accidents.delete');
        });
        Gate::define('deductions', function ($user) {
            return $user->hasAccess('deductions');
        });
        Gate::define('deductions.create', function ($user) {
            return $user->hasAccess('deductions.create');
        });
        Gate::define('deductions.edit', function ($user) {
            return $user->hasAccess('deductions.edit');
        });
        Gate::define('deductions.delete', function ($user) {
            return $user->hasAccess('deductions.delete');
        });
        Gate::define('receipts.view', function ($user) {
            return $user->hasAccess('receipts.view');
        });
        Gate::define('receipts.create', function ($user) {
            return $user->hasAccess('receipts.create');
        });
        Gate::define('receipts.edit', function ($user) {
            return $user->hasAccess('receipts.edit');
        });
        Gate::define('receipts.delete', function ($user) {
            return $user->hasAccess('receipts.delete');
        });
        Gate::define('cash-handover', function ($user) {
            return $user->hasAccess('cash-handover');
        });
        Gate::define('cash-handover.create', function ($user) {
            return $user->hasAccess('cash-handover.create');
        });
        Gate::define('cash-handover.verifiy', function ($user) {
            return $user->hasAccess('cash-handover.verifiy');
        });
    }
}
