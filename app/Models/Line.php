<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Line extends Model
{
    public $timestamps = false;  
    protected $table = 'lines';

    protected $fillable = [
        'code',
        'start_time_operation',
        'end_time_operation',
        'type',
        'map'
    ];

    public function stations()
    {
        return $this->hasMany(Station::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}