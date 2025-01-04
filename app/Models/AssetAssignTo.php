<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AssetAssignTo extends Model
{
    use HasFactory;
    protected $table = 'asset_assign_to';

    public function userInfo()
    {
        return $this->hasOne(User::class, 'id', 'driver_id')->select(['id', DB::raw('CONCAT(first_name, " ", last_name) AS show_name')]);
    }
}
