<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TowingRequest extends Model
{
    use HasFactory;
    protected $table = 'towings_requests';

    // Define the fillable columns
    protected $fillable = [
        'asset_id',
        'location',
        'towing_date',
        'user_id',
        'reason',
        'failed_reason',
    ];
}
