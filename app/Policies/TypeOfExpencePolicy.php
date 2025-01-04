<?php

namespace App\Policies;

use App\Policies\SnipePermissionsPolicy;

class TypeOfExpencePolicy extends SnipePermissionsPolicy
{
    protected function columnName()
    {
        return 'type_of_expences';
    }
}