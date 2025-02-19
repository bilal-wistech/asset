<?php

/*
 |--------------------------------------------------------------------------
 | DO NOT EDIT THIS FILE DIRECTLY.
 |--------------------------------------------------------------------------
*/


return [

    'Global' => [
        [
            'permission' => 'superuser',
            'label' => 'Super User',
            'note' => 'Determines whether the user has full access to all aspects of the admin. This setting overrides any more specific permissions throughout the system. ',
            'display' => true,
        ],
    ],

    'Admin' => [
        [
            'permission' => 'admin',
            'label' => '',
            'note' => 'Determines whether the user has access to most aspects of the admin. ',
            'display' => true,
        ],
    ],
    'Dashboard' => [
        [
            'permission' => 'dashboard',
            'label' => 'View',
            'note' => 'This will allow users to view the dash board.',
            'display' => true,
        ],
    ],
    'CSV Import' => [
        [
            'permission' => 'import',
            'label' => '',
            'note' => 'This will allow users to import even if access to users, assets, etc is denied elsewhere.',
            'display' => true,
        ],
    ],

    'Reports' => [
        [
            'permission' => 'reports.view',
            'label' => 'View',
            'note' => 'Determines whether the user has the ability to view reports.',
            'display' => true,
        ],
    ],

    'Assets' => [
        [
            'permission' => 'assets.view',
            'label' => 'View ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'assets.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'assets.edit',
            'label' => 'Edit  ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'assets.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'assets.checkout',
            'label' => 'Checkout ',
            'note' => '',
            'display' => false,
        ],

        [
            'permission' => 'assets.checkin',
            'label' => 'Checkin ',
            'note' => '',
            'display' => true,
        ],

        [
            'permission' => 'assets.checkout',
            'label' => 'Checkout ',
            'note' => '',
            'display' => true,
        ],

        [
            'permission' => 'assets.audit',
            'label' => 'Audit ',
            'note' => 'Allows the user to mark an asset as physically inventoried.',
            'display' => true,
        ],


        [
            'permission' => 'assets.view.requestable',
            'label' => 'View Requestable Assets',
            'note' => '',
            'display' => true,
        ],

    ],

    'Accessories' => [
        [
            'permission' => 'accessories.view',
            'label' => 'View ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'accessories.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'accessories.edit',
            'label' => 'Edit ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'accessories.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'accessories.checkout',
            'label' => 'Checkout ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'accessories.checkin',
            'label' => 'Checkin ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'accessories.files',
            'label' => 'View and Modify Accessory Files',
            'note' => '',
            'display' => true,
        ],

    ],

    'Consumables' => [
        [
            'permission' => 'consumables.view',
            'label' => 'View',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'consumables.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'consumables.edit',
            'label' => 'Edit ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'consumables.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'consumables.checkout',
            'label' => 'Checkout ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'consumables.files',
            'label' => 'View and Modify Consumable Files',
            'note' => '',
            'display' => true,
        ],
    ],


    'Licenses' => [
        [
            'permission' => 'licenses.view',
            'label' => 'View',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'licenses.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'licenses.edit',
            'label' => 'Edit ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'licenses.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'licenses.checkout',
            'label' => 'Checkout ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'licenses.keys',
            'label' => 'View License Keys',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'licenses.files',
            'label' => 'View and Modify License Files',
            'note' => '',
            'display' => true,
        ],
    ],


    'Components' => [
        [
            'permission' => 'components.view',
            'label' => 'View',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'components.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'components.edit',
            'label' => 'Edit ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'components.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'components.checkout',
            'label' => 'Checkout ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'components.checkin',
            'label' => 'Checkin ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'components.files',
            'label' => 'View and Modify Component Files',
            'note' => '',
            'display' => true,
        ],

    ],

    'Kits' => [
        [
            'permission' => 'kits.view',
            'label' => 'View ',
            'note' => 'These are predefined kits that can be used to quickly checkout assets, licenses, etc.',
            'display' => true,
        ],
        [
            'permission' => 'kits.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'kits.edit',
            'label' => 'Edit  ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'kits.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
    ],

    'Users' => [
        [
            'permission' => 'users.view',
            'label' => 'View ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'users.create',
            'label' => 'Create Users',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'users.edit',
            'label' => 'Edit Users',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'users.delete',
            'label' => 'Delete Users',
            'note' => '',
            'display' => true,
        ],

    ],

    'Models' => [
        [
            'permission' => 'models.view',
            'label' => 'View ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'models.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'models.edit',
            'label' => 'Edit  ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'models.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],

    ],

    'Categories' => [
        [
            'permission' => 'categories.view',
            'label' => 'View ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'categories.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'categories.edit',
            'label' => 'Edit  ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'categories.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
    ],

    'Departments' => [
        [
            'permission' => 'departments.view',
            'label' => 'View ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'departments.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'departments.edit',
            'label' => 'Edit  ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'departments.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
    ],

    'Status Labels' => [
        [
            'permission' => 'statuslabels.view',
            'label' => 'View ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'statuslabels.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'statuslabels.edit',
            'label' => 'Edit  ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'statuslabels.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
    ],

    'Custom Fields' => [
        [
            'permission' => 'customfields.view',
            'label' => 'View',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'customfields.create',
            'label' => 'Create',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'customfields.edit',
            'label' => 'Edit',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'customfields.delete',
            'label' => 'Delete',
            'note' => '',
            'display' => true,
        ],
    ],

    'Suppliers' => [
        [
            'permission' => 'suppliers.view',
            'label' => 'View ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'suppliers.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'suppliers.edit',
            'label' => 'Edit  ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'suppliers.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
    ],


    'Manufacturers' => [
        [
            'permission' => 'manufacturers.view',
            'label' => 'View ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'manufacturers.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'manufacturers.edit',
            'label' => 'Edit  ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'manufacturers.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
    ],

    'Depreciations' => [
        [
            'permission' => 'depreciations.view',
            'label' => 'View ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'depreciations.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'depreciations.edit',
            'label' => 'Edit  ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'depreciations.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
    ],

    'Locations' => [
        [
            'permission' => 'locations.view',
            'label' => 'View ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'locations.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'locations.edit',
            'label' => 'Edit  ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'locations.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
    ],

    'Companies' => [
        [
            'permission' => 'companies.view',
            'label' => 'View ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'companies.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'companies.edit',
            'label' => 'Edit  ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'companies.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
    ],
    'Self' => [
        [
            'permission' => 'self.two_factor',
            'label' => 'Two-Factor Authentication',
            'note' => 'The user may disable/enable two-factor authentication themselves if two-factor is enabled and set to selective.',
            'display' => true,
        ],

        [
            'permission' => 'self.api',
            'label' => 'Create API Keys',
            'note' => 'The user create personal API keys to utilize the REST API.',
            'display' => true,
        ],

        [
            'permission' => 'self.edit_location',
            'label' => 'Profile Edit Location',
            'note' => 'The user may update their own location in their profile. Note that this is not affected by any additional Users permissions you grant to this user or group.',
            'display' => true,
        ],

        [
            'permission' => 'self.checkout_assets',
            'label' => 'Self-Checkout',
            'note' => 'This user may check out assets that are marked for self-checkout.',
            'display' => true,
        ],

        [
            'permission' => 'self.view_purchase_cost',
            'label' => 'View Purchase-Cost Column',
            'note' => 'This user can see the purchase cost column of items assigned to them.',
            'display' => true,
        ],

    ],
    'Asset Assignment' => [
        [
            'permission' => 'asset_assignments.view',
            'label' => 'View ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'asset_assignments.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'asset_assignments.edit',
            'label' => 'Edit  ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'asset_assignments.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
    ],
    'Asset Insurance' => [
        [
            'permission' => 'insurance.view',
            'label' => 'View ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'insurance.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'insurance.edit',
            'label' => 'Edit  ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'insurance.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
    ],
    'Type of Expence' => [
        [
            'permission' => 'type_of_expences.view',
            'label' => 'View ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'type_of_expences.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'type_of_expences.edit',
            'label' => 'Edit  ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'type_of_expences.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
    ],
    'Re-Immensible Expence' => [
        [
            'permission' => 'add_expences.view',
            'label' => 'View ',
            'note' => '',
            'display' => true,
        ],

    ],
    'Towing Requests' => [
        [
            'permission' => 'towing_requests.view',
            'label' => 'View',
            'note' => '',
            'display' => true,
        ],
    ],
    'Fines' => [
        [
            'permission' => 'fines.view',
            'label' => 'View ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'fines.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'fines.edit',
            'label' => 'Edit ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'fines.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
    ],
    'Deductions' => [
        [
            'permission' => 'deductions.view',
            'label' => 'View ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'deductions.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'deductions.edit',
            'label' => 'Edit ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'deductions.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
    ],
    'Receipts' => [
        [
            'permission' => 'receipts.view',
            'label' => 'View ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'receipts.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'receipt.edit',
            'label' => 'Edit ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'receipts.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
    ],
    'Accident' => [
        [
            'permission' => 'accidents.view',
            'label' => 'View ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'accidents.create',
            'label' => 'Create ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'accidents.edit',
            'label' => 'Edit ',
            'note' => '',
            'display' => true,
        ],
        [
            'permission' => 'accidents.delete',
            'label' => 'Delete ',
            'note' => '',
            'display' => true,
        ],
    ],
    'Asset Handover Details' => [
        [
            'permission' => 'handover-details',
            'label' => 'View',
            'note' => 'This will allow users to view Asset Handover Details.',
            'display' => true,
        ],
    ],
    'Cash Handover' => [
        [
            'permission' => 'cash-handover',
            'label' => 'View',
            'note' => 'This will allow users to view Cash Handover',
            'display' => true,
        ],
    ],
    'Cash Handover Create' => [
        [
            'permission' => 'cash-handover.create',
            'label' => 'Create',
            'note' => 'This will allow users to Create Cash Handover',
            'display' => true,
        ],
    ],
    'Cash Handover Verify' => [
        [
            'permission' => 'cash-handover.verifiy',
            'label' => 'Verify',
            'note' => 'This will allow users to Verify Cash Handover',
            'display' => true,
        ],
    ],
    'Salaries' => [
        [
            'permission' => 'salaries.index',
            'label' => 'View',
            'note' => 'This will allow users to view driver salaries',
            'display' => true,
        ],
    ],
    'Create Salaries' => [
        [
            'permission' => 'salaries.create',
            'label' => 'Create',
            'note' => 'This will allow users to create driver salaries',
            'display' => true,
        ],
    ],
    'Edit Salaries' => [
        [
            'permission' => 'salaries.create',
            'label' => 'Edit',
            'note' => 'This will allow users to edit driver salaries',
            'display' => true,
        ],
    ]
];
