<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashHandoverVerification extends Model
{
    use HasFactory;
    protected $table = 'cash_handover_verification';
    protected $fillable = [
        'verified_by',
        'cash_handover_id',
        'receipt_id',
        'status'
    ];
}
