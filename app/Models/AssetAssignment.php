<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AssetAssignment extends Model
{
    use HasFactory;
    protected $table = 'asset_assignments';

    
    public function isDeletable()
    {
        return Gate::allows('delete', $this)
                && ($this->itemCount() == 0);
    }

    public function itemCount()
    {
        return 1;
    }


    public function userIds()
    {
        return $this->hasMany(AssetAssignTo::class, 'assignment_id', 'id');
    }




    public function assignedBy()
    {
        return $this->hasOne(User::class, 'id', 'assigned_by')->select([DB::raw('CONCAT(first_name, " ", last_name) AS show_name')]);
    }


    public function asset()
    {
        return $this->hasOne(Asset::class, 'id', 'asset_id');
    }
}
