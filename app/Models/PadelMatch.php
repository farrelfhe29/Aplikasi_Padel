<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PadelMatch extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'format',
        'mode',
        'golden_point',
        'status',
        'winner_side',
        'session_token',
    ];

    protected $casts = [
        'golden_point' => 'boolean',
    ];

    public function players()
    {
        return $this->hasMany(MatchPlayer::class, 'match_id');
    }

    public function teamA()
    {
        return $this->hasMany(MatchPlayer::class, 'match_id')->where('team', 'A');
    }

    public function teamB()
    {
        return $this->hasMany(MatchPlayer::class, 'match_id')->where('team', 'B');
    }

    public function sets()
    {
        return $this->hasMany(MatchSet::class, 'match_id')->orderBy('set_number');
    }

    public function getSetsToWinAttribute(): int
    {
        return $this->format === 'best_of_3' ? 2 : 1;
    }
}
