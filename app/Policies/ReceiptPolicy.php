<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Receipt;

class ReceiptPolicy extends SnipePermissionsPolicy
{
    protected function columnName()
    {
        return 'receipt';
    }
    
    public function create(User $user)
    {
        return $user->hasAccess('receipts.create');
    }
    
    public function delete(User $user, $receipt = null)
    {
        return $user->hasAccess('receipts.delete');
    }
}