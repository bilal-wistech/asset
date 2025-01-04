<?php

namespace App\Policies;

use App\Policies\SnipePermissionsPolicy;

class AccidentPolicy extends SnipePermissionsPolicy
{
    protected function columnName()
    {
        return 'accidents';
    }
}