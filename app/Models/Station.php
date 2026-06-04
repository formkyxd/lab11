<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Station extends Model
{
    protected $table = 'stations';

    protected $fillable = [
        'name',
        'position_station',
        'line_id',
    ];

    public function line(): BelongsTo
    {
        return $this->belongsTo(Line::class);
    }
}
