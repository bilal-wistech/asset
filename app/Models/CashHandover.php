<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashHandover extends Model
{
    use HasFactory;
    protected $table = 'cash_handovers';
    protected $fillable = ['total_amount', 'handover_by', 'handover_to'];
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
