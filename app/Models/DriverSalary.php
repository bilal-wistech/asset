<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class DriverSalary extends Model
{
    use HasFactory;
    protected  $fillable = ['driver_id', 'base_salary','from_date','to_date'];

    public function getActivitylogOptions(): LogOptions
    {
        $log = new LogOptions();
        return $log->logAll()
            ->useLogName('DriverSalary');
    }
}
