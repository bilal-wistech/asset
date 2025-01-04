<?php

namespace App\Policies;

use App\Policies\SnipePermissionsPolicy;

class AssetAssignmentPolicy extends SnipePermissionsPolicy
{
    protected function columnName()
    {
        return 'asset_assignments';
    }
}