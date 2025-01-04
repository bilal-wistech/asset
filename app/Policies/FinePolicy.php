<?php

namespace App\Policies;

use App\Policies\SnipePermissionsPolicy;

class FinePolicy extends SnipePermissionsPolicy
{
    protected function columnName()
    {
        return 'fines';
    }
}