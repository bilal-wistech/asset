<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RidingCompany extends Model
{
    use HasFactory;
    protected $table = 'riding_companies';
    protected $fillable = [
        'name', 
        'status',
    ];
}
