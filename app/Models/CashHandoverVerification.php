<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashHandoverVerification extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'cash_handover_verification';
    protected $fillable = [
        'verified_by',
        'cash_handover_id',
        'receipt_id',
        'status'
    ];
    public function getActivitylogOptions(): LogOptions
    {
        $log = new LogOptions();
        return $log->logAll()
            ->useLogName('Cash Handover Verification');
    }
}
