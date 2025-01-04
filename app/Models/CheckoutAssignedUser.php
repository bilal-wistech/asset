<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutAssignedUser extends Model
{
    use HasFactory;
    protected $table = 'checkout_assigned_user';


    public function getUsersInfo()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->select(['id', DB::raw('CONCAT(first_name, " ", last_name) AS show_name')]);
    }
}
