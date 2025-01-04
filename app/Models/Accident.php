<?php

namespace App\Models;

use App\Models\Asset;
use App\Presenters\Presentable;
use App\Models\Traits\Searchable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Accident extends Model
{
    protected $presenter = \App\Presenters\AccidentPresenter::class;
    use HasFactory, Searchable, Presentable,LogsActivity;

    protected $table = 'accidents';

    protected $fillable = [
        'accident_date',
        // 'accident_type',
        'asset_id',
        'responsibility_amount',
        'user_id',
        // 'amount',
        'location',
        'note',
        'accident_image',
        'accident_number',
        'responsibility',
        'claim_opening',
        'damages_amount',
        'claimable',
        'relevant_files'

    ];

    protected $searchableAttributes = [
        'accident_date',
        // 'accident_type',
        'asset_id',
        'responsibility_amount',
        'user_id',
        // 'amount',
        'location',
        'note',
        'accident_image',
        'accident_number',
        'claim_opening',
        'damages_amount',
        'claimable',
        'relevant_files'
    ];
    public function getActivitylogOptions(): LogOptions
    {
        $log = new LogOptions();
        return $log->logAll()
            ->useLogName('Accident');
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
        return $this->belongsTo(AccidentType::class, 'accident_type', 'id');
    }

    public function findLocation()
    {
        // return 'hdhdhdh';
        return $this->belongsTo(Location::class, 'location', 'id');
    }

    public function getImageUrl()
    {
        if ($this->image && !empty($this->image)) {
            return Storage::disk('public')->url(app('assets_upload_path') . e($this->image));
        } elseif ($this->model && !empty($this->model->image)) {
            return Storage::disk('public')->url(app('models_upload_path') . e($this->model->image));
        }

        return false;
    }
}
