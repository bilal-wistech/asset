<?php

namespace App\Models;

use App\Models\Asset;
use App\Models\DeductionType;
use App\Presenters\Presentable;
use App\Models\Traits\Searchable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class deduction extends Model
{
    protected $presenter = \App\Presenters\DeductionPresenter::class;
    use HasFactory, Searchable, Presentable,LogsActivity;

    protected $table = 'deductions';

    protected $fillable = [
        'deduction_date', 
        'user_id',
        'amount',
        'reason',
        'note',
      
    ];

    protected $searchableAttributes = [
        'deduction_date', 
        'user_id',
        'amount',
        'reason',
        'note',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $log = new LogOptions();
        return $log->logAll()
            ->useLogName('Deduction');
    }

    public function user()
    {
        // return 'dd';
        return $this->belongsTo(User::class);
        
    }
    
    
    public function type()
    {
        // return 'hdhdhdh';
        return $this->belongsTo(DeductionType::class , 'reason', 'id');
    }


    
    
     
}
