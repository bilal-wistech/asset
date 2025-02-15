<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;
    protected $fillable = ['riding_company_id', 'driver_id', 'user_id', 'amount_paid', 'from_date', 'to_date','salary'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
    public function ridingCompany()
    {
        return $this->belongsTo(RidingCompany::class);
    }
    public function driverSalary()
    {
        return $this->hasOne(DriverSalary::class, 'driver_id', 'driver_id');
    }

}
