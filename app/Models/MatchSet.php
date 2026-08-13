<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchSet extends Model
{
    protected $fillable = [
        'match_id',
        'set_number',
        'games_a',
        'games_b',
        'winner_side',
        'is_tiebreak',
    ];

    protected $casts = [
        'is_tiebreak' => 'boolean',
    ];

    public function match()
    {
        return $this->belongsTo(PadelMatch::class, 'match_id');
    }
}
