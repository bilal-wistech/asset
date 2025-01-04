<?php

namespace App\Models;

use App\Models\Receipt;
use App\Models\DeductionType;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReceiptDetail extends Model
{
    use HasFactory,LogsActivity;

    // Specify the table associated with the model (optional if table name is plural and follows Laravel convention)
    protected $table = 'receipt_details';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'receipt_id',
        'type_id',
        'type',
        'amount',
        'payment',
        'description',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        $log = new LogOptions();
        return $log->logAll()
            ->useLogName('Receipt Detail');
    }
    // Define the inverse relationship with the Receipt model (Many-to-One)
    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }
    
}
