<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AmericanoPlayer extends Model
{
    protected $fillable = ['tournament_id', 'name', 'total_points'];

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(AmericanoTournament::class, 'tournament_id');
    }
}
