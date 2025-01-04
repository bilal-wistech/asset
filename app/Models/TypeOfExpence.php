<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeOfExpence extends Model
{
    use HasFactory;  

    protected $table = 'type_of_expences';

    public function expence()
    {
        return $this->hasMany(AddExpence::class , 'type_id');
    }
}
