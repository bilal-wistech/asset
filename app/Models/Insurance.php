<?php

namespace App\Models;

use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;

class Insurance extends Model
{
    use HasFactory, SoftDeletes;
    use Searchable;
    protected $table = 'insurance';
    
       protected $searchableAttributes = ['premium_type', 'created_at',  'insurance_date',
    'insurance_from',
    'insurance_to',
    'amount'];

    /**
     * The relations and their attributes that should be included when searching the model.
     *
     * @var array
     */
    protected $searchableRelations = [
        'asset'     => ['name', 'asset_tag'],
        'supplierInfo' => ['name'],
        'drivers' => ['driver_name'],
        
    ];


    public function isDeletable()
    {
        return Gate::allows('delete', $this)
                && ($this->itemCount() == 0);
    }

    public function itemCount()
    {
        return 1;
    }


    public function drivers()
    {
        return $this->hasMany(AllowedDrivers::class, 'insurance_id', 'id');
    }

    public function asset()
    {
        return $this->hasOne(Asset::class, 'id', 'asset_id');
    }

    public function assignedBy()
    {
        return $this->hasOne(User::class, 'id', 'assigned_by')->select(['id', DB::raw('CONCAT(first_name, " ", last_name) AS show_name')]);
    }


    
    public function supplierInfo()
    {
        return $this->hasOne(Supplier::class, 'id', 'vendor_id');
    }




}
