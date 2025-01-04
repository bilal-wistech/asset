<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccidentType extends Model
{
    use HasFactory;

    protected $table = 'accident_types';

    public function accident()
    {
        return $this->hasMany(Accident::class , 'accident_type', 'id');
    }
}
