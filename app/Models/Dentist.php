<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dentist extends Model
{
    protected $fillable = [
        'name',
        'specialization',
    ];
}
