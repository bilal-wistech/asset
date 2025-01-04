<?php

namespace App\Policies;

use App\Policies\SnipePermissionsPolicy;

class DeductionPolicy extends SnipePermissionsPolicy
{
    protected function columnName()
    {
        return 'deductions';
    }
}