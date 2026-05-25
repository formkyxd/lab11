<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    public $timestamps = false; 
    protected $table = 'vehicles';

    protected $fillable = [
        'name',
        'capacity',
        'type',
        'line_id'
    ];

    public function line()
    {
        return $this->belongsTo(Line::class);
    }

    public function driver()
    {
        return $this->hasOne(Driver::class);
    }
}