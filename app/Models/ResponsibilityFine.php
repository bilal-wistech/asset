<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponsibilityFine extends Model
{
    use HasFactory;
    protected $table = 'responsibility_fines';
    protected $fillable = [
        'user_id', 
        'asset_id',
        'responsibility_amount',
    ];

    
}
