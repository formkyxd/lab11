<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Line extends Model
{
    public $timestamps = false;

    protected $table = 'lines';

    protected $fillable = [
        'code',
        'start_time_operation',
        'end_time_operation',
        'type',
        'map',
    ];

    public function stations(): HasMany
    {
        return $this->hasMany(Station::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
