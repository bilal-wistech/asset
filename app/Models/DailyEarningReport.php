<?php

namespace App\Models;

use App\Presenters\Presentable;
use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyEarningReport extends Model
{
    protected $presenter = \App\Presenters\AssetPresenter::class;
    use HasFactory, Searchable, Presentable;
    protected $fillable = [
        'id',
        'courier_id',
        'user_id',
        'name',
        'phone',
        'city',
        'offline',
        'days_since_last_delivery',
        'days_since_last_offload',
        'earnings_without_tips_yesterday',
        'hours_online_yesterday',
        'hours_on_task_yesterday',
        'cash_balance'
    ];
    /**
     * The attributes that should be included when searching the model.
     *
     * @var array
     */
    protected $searchableAttributes = [
        'id',
        'courier_id',
        'user_id',
        'name',
        'phone',
        'city',
        'offline',
        'days_since_last_delivery',
        'days_since_last_offload',
        'earnings_without_tips_yesterday',
        'hours_online_yesterday',
        'hours_on_task_yesterday',
        'cash_balance',
        'created_at'
    ];
}
