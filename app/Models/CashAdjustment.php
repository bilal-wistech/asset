<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAdjustment extends Model
{
    use HasFactory;

    protected $table = 'cash_adjustment';
    protected $fillable = ['driver_id', 'user_id', 'amount', 'created_at', 'updated_at'];

     // Relationship with User (who made the adjustment)
     public function user()
     {
         return $this->belongsTo(User::class);
     }
 
     // Relationship with Driver (the driver whose cash adjustment is recorded)
     public function driver()
     {
         return $this->belongsTo(User::class, 'driver_id');
     }
}
