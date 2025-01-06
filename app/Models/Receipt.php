<?php

namespace App\Models;

use App\Models\ReceiptDetail;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receipt extends Model
{
    use HasFactory, LogsActivity;

    // Specify the table associated with the model (optional if table name is plural and follows Laravel convention)
    protected $table = 'receipt';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'deleted_at',
        'total_amount',
        'slip_id',
        'receipt_id',
        'user_id',
        'date',
        'deduction_way',
        'added_by'
    ];
    public function getActivitylogOptions(): LogOptions
    {
        $log = new LogOptions();
        return $log->logAll()
            ->useLogName('Receipt');
    }
    public function user()
    {
        // return 'dd';
        return $this->belongsTo(User::class);
    }

    // Define the relationship with the ReceiptDetail model (One-to-Many)
    public function receiptDetails()
    {
        return $this->hasMany(ReceiptDetail::class, 'id', 'receipt_id');
    }
}
