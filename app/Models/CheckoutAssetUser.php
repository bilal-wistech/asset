<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutAssetUser extends Model
{
    use HasFactory;
    protected $table = 'checkout_asset_user';


    public function getCheckOutUsersInfo()
    {
        return $this->hasMany(CheckoutAssignedUser::class, 'checkout_asset_user_id', 'id');
    }
}
