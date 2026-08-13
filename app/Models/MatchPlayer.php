<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchPlayer extends Model
{
    protected $fillable = ['match_id', 'name', 'team'];

    public function match()
    {
        return $this->belongsTo(PadelMatch::class, 'match_id');
    }
}
