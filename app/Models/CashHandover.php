<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashHandover extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'cash_handovers';
    protected $fillable = ['total_amount', 'handover_by', 'handover_to', 'handover_date'];
    public function getActivitylogOptions(): LogOptions
    {
        $log = new LogOptions();
        return $log->logAll()
            ->useLogName('Cash Handover');
    }
    public function receipts()
    {
        return $this->belongsToMany(Receipt::class, 'cash_handover_receipts');
    }

    public function handoverByUser()
    {
        return $this->belongsTo(User::class, 'handover_by');
    }

    public function handoverToUser()
    {
        return $this->belongsTo(User::class, 'handover_to');
    }
}
