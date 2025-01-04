<?php

namespace App\Models;

use App\Models\Asset;
use App\Presenters\Presentable;
use App\Models\Traits\Searchable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fine extends Model
{
    protected $presenter = \App\Presenters\FinePresenter::class;
    use HasFactory, Searchable, Presentable,LogsActivity;

    protected $table = 'fines';

    protected $fillable = [
        'fine_date',
        'fine_type',
        'asset_id',
        'user_id',
        'amount',
        'location',
        'note',
        'fine_image',
        'fine_number',
        'notification',
      
    ];

    protected $searchableAttributes = [
        'fine_date',
        'fine_type',
        'asset_id',
        'user_id',
        'amount',
        'location',
        'note',
        'fine_image',
        'fine_number'
    ];
    public function getActivitylogOptions(): LogOptions
    {
        $log = new LogOptions();
        return $log->logAll()
            ->useLogName('Fine');
    }
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function user()
    {
        // return 'dd';
        return $this->belongsTo(User::class);
        
    }
    
    
    public function type()
    {
        // return 'hdhdhdh';
        return $this->belongsTo(FineType::class , 'fine_type', 'id');
    }

    public function findLocation()
    {
        // return 'hdhdhdh';
        return $this->belongsTo(Location::class , 'location', 'id');
    }
    
     public function getImageUrl()
    {
        if ($this->image && ! empty($this->image)) {
            return Storage::disk('public')->url(app('assets_upload_path').e($this->image));
        } elseif ($this->model && ! empty($this->model->image)) {
            return Storage::disk('public')->url(app('models_upload_path').e($this->model->image));
        }

        return false;
    }
}
