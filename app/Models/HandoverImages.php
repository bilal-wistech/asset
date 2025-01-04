<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandoverImages extends Model
{
    use HasFactory;

    protected $table = 'handover_images';

    protected $fillable = [

        '_token',
        'asset_id',
        'notes',
        'checkin_date',
        'images',
        'user_id',
        'reason_id'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reason()
    {
        return $this->belongsTo(CheckinReason::class, 'reason_id');
    }

}
