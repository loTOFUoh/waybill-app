<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = ['model', 'plate_number', 'fuel_type', 'base_consumption'];
}
