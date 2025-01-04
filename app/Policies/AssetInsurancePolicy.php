<?php

namespace App\Policies;

use App\Policies\SnipePermissionsPolicy;

class AssetInsurancePolicy extends SnipePermissionsPolicy
{
    protected function columnName()
    {
        return 'insurance';
    }
}