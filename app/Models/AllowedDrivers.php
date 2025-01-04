<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowedDrivers extends Model
{
    use HasFactory;
    protected $table = 'allowed_drivers';
}
