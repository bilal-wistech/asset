<?php

namespace App\Models;
use App\Models\Deduction;
use App\Models\ReceiptDetail;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeductionType extends Model
{
    use HasFactory,LogsActivity;

    protected $table = 'deduction_types';
    protected $fillable = ['name'];
    public function getActivitylogOptions(): LogOptions
    {
        $log = new LogOptions();
        return $log->logAll()
            ->useLogName('Deduction Type');
    }
    public function receiptDetails()
    {
        return $this->hasMany(ReceiptDetail::class, 'type_id', 'id');
    }
    
}
