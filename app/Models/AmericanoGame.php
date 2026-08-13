<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AmericanoGame extends Model
{
    protected $fillable = [
        'tournament_id', 'round', 'court',
        'player_a1_id', 'player_a2_id',
        'player_b1_id', 'player_b2_id',
        'score_a', 'score_b', 'status',
    ];

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(AmericanoTournament::class, 'tournament_id');
    }

    public function playerA1(): BelongsTo
    {
        return $this->belongsTo(AmericanoPlayer::class, 'player_a1_id');
    }

    public function playerA2(): BelongsTo
    {
        return $this->belongsTo(AmericanoPlayer::class, 'player_a2_id');
    }

    public function playerB1(): BelongsTo
    {
        return $this->belongsTo(AmericanoPlayer::class, 'player_b1_id');
    }

    public function playerB2(): BelongsTo
    {
        return $this->belongsTo(AmericanoPlayer::class, 'player_b2_id');
    }
}
