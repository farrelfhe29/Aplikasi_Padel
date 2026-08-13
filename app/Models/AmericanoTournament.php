<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AmericanoTournament extends Model
{
    protected $fillable = ['name', 'points_per_game', 'status', 'total_rounds', 'current_round'];

    public function players(): HasMany
    {
        return $this->hasMany(AmericanoPlayer::class, 'tournament_id');
    }

    public function games(): HasMany
    {
        return $this->hasMany(AmericanoGame::class, 'tournament_id');
    }

    public function gamesForRound(int $round): HasMany
    {
        return $this->games()->where('round', $round);
    }

    public function getLeaderboard()
    {
        return $this->players()->orderByDesc('total_points')->orderBy('name')->get();
    }

    /**
     * Generate game schedule for all rounds using the standard Americano rotation.
     * Works for 4, 8, 12 players (multiples of 4).
     * For N players, there are N-1 rounds (round-robin).
     *
     * Algorithm: Circle method (polygon algorithm for round-robin)
     * For each round, pair players using a fixed rotation:
     *   - Fix player 0, rotate the rest
     *   - Split into two "teams" of 2 on each court
     */
    public static function generateSchedule(array $playerIds): array
    {
        $n = count($playerIds);
        $rounds = [];

        if ($n < 4) {
            return $rounds;
        }

        $courts = intdiv($n, 4);
        
        // Determine number of rounds to ensure everyone plays roughly the same
        // Standard Americano: multiples of 4 -> N-1 rounds
        // Others: N rounds
        $totalRounds = ($n % 4 === 0) ? $n - 1 : $n;
        // For larger uneven numbers, we might want more rounds to ensure fairness, 
        // but $n rounds is a good baseline so everyone sits out equally.
        if ($n === 5) $totalRounds = 5; // each plays 4
        if ($n === 6) $totalRounds = 6; // each plays 4
        if ($n === 7) $totalRounds = 7; // each plays 4

        $gamesPlayed = array_fill_keys($playerIds, 0);
        $partnerCounts = [];
        $opponentCounts = [];
        foreach ($playerIds as $id1) {
            foreach ($playerIds as $id2) {
                $partnerCounts[$id1][$id2] = 0;
                $opponentCounts[$id1][$id2] = 0;
            }
        }

        for ($round = 1; $round <= $totalRounds; $round++) {
            // 1. Select players for this round (prioritize those with least games)
            $available = $playerIds;
            usort($available, function($a, $b) use ($gamesPlayed) {
                if ($gamesPlayed[$a] !== $gamesPlayed[$b]) {
                    return $gamesPlayed[$a] <=> $gamesPlayed[$b];
                }
                // Random tiebreaker
                return mt_rand(-1, 1);
            });

            $selectedPlayers = array_slice($available, 0, $courts * 4);
            
            // Mark games played
            foreach ($selectedPlayers as $pid) {
                $gamesPlayed[$pid]++;
            }

            $roundGames = [];

            // 2. Assign to courts
            for ($c = 0; $c < $courts; $c++) {
                $courtPlayers = array_slice($selectedPlayers, $c * 4, 4);
                
                // Find best combination of these 4 players (3 possibilities)
                // Possibility 1: (0,1) vs (2,3)
                // Possibility 2: (0,2) vs (1,3)
                // Possibility 3: (0,3) vs (1,2)
                $combos = [
                    [$courtPlayers[0], $courtPlayers[1], $courtPlayers[2], $courtPlayers[3]],
                    [$courtPlayers[0], $courtPlayers[2], $courtPlayers[1], $courtPlayers[3]],
                    [$courtPlayers[0], $courtPlayers[3], $courtPlayers[1], $courtPlayers[2]],
                ];

                $bestCombo = $combos[0];
                $bestScore = PHP_INT_MAX;

                foreach ($combos as $combo) {
                    $pA1 = $combo[0]; $pA2 = $combo[1];
                    $pB1 = $combo[2]; $pB2 = $combo[3];

                    // Score based on how many times they've partnered/opposed
                    // We want to MINIMIZE this score
                    $score = 0;
                    // Partner penalties (heavy)
                    $score += $partnerCounts[$pA1][$pA2] * 100;
                    $score += $partnerCounts[$pB1][$pB2] * 100;
                    // Opponent penalties (lighter)
                    $score += $opponentCounts[$pA1][$pB1] * 10;
                    $score += $opponentCounts[$pA1][$pB2] * 10;
                    $score += $opponentCounts[$pA2][$pB1] * 10;
                    $score += $opponentCounts[$pA2][$pB2] * 10;

                    if ($score < $bestScore) {
                        $bestScore = $score;
                        $bestCombo = $combo;
                    }
                }

                // Apply best combo
                $pA1 = $bestCombo[0]; $pA2 = $bestCombo[1];
                $pB1 = $bestCombo[2]; $pB2 = $bestCombo[3];

                $partnerCounts[$pA1][$pA2]++; $partnerCounts[$pA2][$pA1]++;
                $partnerCounts[$pB1][$pB2]++; $partnerCounts[$pB2][$pB1]++;

                $opponentCounts[$pA1][$pB1]++; $opponentCounts[$pB1][$pA1]++;
                $opponentCounts[$pA1][$pB2]++; $opponentCounts[$pB2][$pA1]++;
                $opponentCounts[$pA2][$pB1]++; $opponentCounts[$pB1][$pA2]++;
                $opponentCounts[$pA2][$pB2]++; $opponentCounts[$pB2][$pA2]++;

                $roundGames[] = [
                    'round'       => $round,
                    'court'       => $c + 1,
                    'player_a1'   => $pA1,
                    'player_a2'   => $pA2,
                    'player_b1'   => $pB1,
                    'player_b2'   => $pB2,
                ];
            }

            $rounds[] = $roundGames;
        }

        return $rounds;
    }
}
