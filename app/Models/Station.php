<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $table = 'stations';

    protected $fillable = [
        'name',
        'position_station',
        'line_id'
    ];

    public function line()
    {
        return $this->belongsTo(Line::class);
    }
}