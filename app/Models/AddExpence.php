<?php

namespace App\Models;

use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AddExpence extends Model
{
    use HasFactory, Searchable;

    protected $table = 'add_expences';
    protected $fillable = [
       
        'approved', 
    ];
    protected $searchableAttributes = [
        'user_id',
        'created_at'
    ];
    public function type()
    {
        
        return $this->belongsTo(TypeOfExpence::class, 'type_id', 'id');

    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id', 'id');
    }

    public function userdata()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
