<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FineType extends Model
{
    use HasFactory,LogsActivity;

    protected $table = 'fine_types';
    public function getActivitylogOptions(): LogOptions
    {
        $log = new LogOptions();
        return $log->logAll()
            ->useLogName('Fine Type');
    }
    public function fine()
    {
        return $this->hasMany(Fine::class , 'fine_type', 'id');
    }
}
