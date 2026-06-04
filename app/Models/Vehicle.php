<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vehicle extends Model
{
    public $timestamps = false;

    protected $table = 'vehicles';

    protected $fillable = [
        'name',
        'capacity',
        'type',
        'line_id',
    ];

    public function line(): BelongsTo
    {
        return $this->belongsTo(Line::class);
    }

    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class);
    }
}
