<?php

namespace App\Http\Controllers;

use App\Models\MatchPlayer;
use App\Models\MatchSet;
use App\Models\PadelMatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MatchController extends Controller
{
    /**
     * Show the match creation form.
     */
    public function create(): View
    {
        return view('match.create');
    }

    /**
     * Store a new match and initialize session state.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'mode'         => ['required', 'in:single,double'],
            'format'       => ['required', 'in:best_of_1,best_of_3'],
            'golden_point' => ['boolean'],
            'team_a'       => ['required', 'array', 'min:1'],
            'team_a.*'     => ['required', 'string', 'max:50'],
            'team_b'       => ['required', 'array', 'min:1'],
            'team_b.*'     => ['required', 'string', 'max:50'],
        ]);

        $match = PadelMatch::create([
            'mode'          => $request->mode,
            'format'        => $request->format,
            'golden_point'  => $request->boolean('golden_point'),
            'status'        => 'ongoing',
            'session_token' => Str::uuid(),
        ]);

        foreach ($request->team_a as $name) {
            if (trim($name)) {
                MatchPlayer::create(['match_id' => $match->id, 'name' => trim($name), 'team' => 'A']);
            }
        }
        foreach ($request->team_b as $name) {
            if (trim($name)) {
                MatchPlayer::create(['match_id' => $match->id, 'name' => trim($name), 'team' => 'B']);
            }
        }

        // Initialize session state
        $this->initializeMatchState($match);

        return redirect()->route('match.scoreboard', $match->id);
    }

    /**
     * Display the live scoreboard.
     */
    public function scoreboard(PadelMatch $match): View
    {
        if ($match->status === 'finished') {
            return redirect()->route('match.result', $match->id);
        }

        $state = session("match_{$match->id}");
        if (! $state) {
            $this->initializeMatchState($match);
            $state = session("match_{$match->id}");
        }

        return view('match.scoreboard', compact('match', 'state'));
    }

    /**
     * Return current match state as JSON (called by Alpine.js).
     */
    public function state(PadelMatch $match): JsonResponse
    {
        $state = session("match_{$match->id}");
        if (! $state) {
            $this->initializeMatchState($match);
            $state = session("match_{$match->id}");
        }
        return response()->json($state);
    }

    /**
     * Add a point to a team (A or B).
     */
    public function addPoint(Request $request, PadelMatch $match): JsonResponse
    {
        $request->validate(['team' => ['required', 'in:A,B']]);

        if ($match->status === 'finished') {
            return response()->json(['error' => 'Match sudah selesai'], 422);
        }

        $state = session("match_{$match->id}");
        if (! $state) {
            $this->initializeMatchState($match);
            $state = session("match_{$match->id}");
        }

        // Save snapshot for undo (exclude history to prevent exponential growth)
        $snapshot = $state;
        unset($snapshot['history']);
        $state['history'][] = json_encode($snapshot);
        if (count($state['history']) > 20) {
            array_shift($state['history']);
        }

        $team = $request->team;
        $opponent = $team === 'A' ? 'B' : 'A';

        $state = $this->processPoint($state, $team, $opponent, $match);

        session(["match_{$match->id}" => $state]);

        // If match finished, persist result to DB
        if ($state['status'] === 'finished') {
            $this->persistResult($match, $state);
        }

        return response()->json($state);
    }

    /**
     * Undo the last point.
     */
    public function undoPoint(PadelMatch $match): JsonResponse
    {
        $state = session("match_{$match->id}");
        if (! $state || empty($state['history'])) {
            return response()->json(['error' => 'Tidak ada riwayat untuk di-undo'], 422);
        }

        $previousState = json_decode(array_pop($state['history']), true);
        $previousState['history'] = $state['history'];

        session(["match_{$match->id}" => $previousState]);

        return response()->json($previousState);
    }

    /**
     * Show match result.
     */
    public function result(PadelMatch $match): View
    {
        $match->load('players', 'sets');
        $state = session("match_{$match->id}");

        return view('match.result', compact('match', 'state'));
    }

    /**
     * Force finish (reset) the match.
     */
    public function finish(PadelMatch $match): RedirectResponse
    {
        $state = session("match_{$match->id}");
        if ($state && $state['status'] !== 'finished') {
            $state['status'] = 'finished';
            
            // Determine winner based on current lead if aborted manually
            if ($state['sets_a'] > $state['sets_b']) {
                $state['winner'] = 'A';
            } elseif ($state['sets_b'] > $state['sets_a']) {
                $state['winner'] = 'B';
            } elseif ($state['games_a'] > $state['games_b']) {
                $state['winner'] = 'A';
            } elseif ($state['games_b'] > $state['games_a']) {
                $state['winner'] = 'B';
            } elseif ($state['points_a'] > $state['points_b']) {
                $state['winner'] = 'A';
            } elseif ($state['points_b'] > $state['points_a']) {
                $state['winner'] = 'B';
            } else {
                $state['winner'] = null; // truly tied
            }

            session(["match_{$match->id}" => $state]);
            $this->persistResult($match, $state);
        }

        return redirect()->route('match.result', $match->id);
    }

    // ─── Private helpers ─────────────────────────────────────────────────────────

    private function initializeMatchState(PadelMatch $match): void
    {
        $teamA = $match->players()->where('team', 'A')->pluck('name')->toArray();
        $teamB = $match->players()->where('team', 'B')->pluck('name')->toArray();

        $state = [
            'match_id'      => $match->id,
            'mode'          => $match->mode,
            'format'        => $match->format,
            'golden_point'  => $match->golden_point,
            'sets_to_win'   => $match->format === 'best_of_3' ? 2 : 1,
            'team_a'        => $teamA,
            'team_b'        => $teamB,
            // Scoring state
            'points_a'      => 0,  // 0=0, 1=15, 2=30, 3=40
            'points_b'      => 0,
            'deuce'         => false,
            'advantage'     => null,  // 'A' or 'B' or null
            'in_tiebreak'   => false,
            'tb_points_a'   => 0,
            'tb_points_b'   => 0,
            // Games & Sets
            'games_a'       => 0,
            'games_b'       => 0,
            'sets_a'        => 0,
            'sets_b'        => 0,
            'current_set'   => 1,
            'set_history'   => [],  // [{games_a, games_b, winner}]
            // Meta
            'status'        => 'ongoing',
            'winner'        => null,  // 'A' or 'B'
            'history'       => [],
        ];

        session(["match_{$match->id}" => $state]);
    }

    private function processPoint(array $state, string $team, string $opponent, PadelMatch $match): array
    {
        // ── Tiebreak mode ──────────────────────────────────────────────────────
        $teamStr = strtolower($team);
        if ($state['in_tiebreak']) {
            $state["tb_points_{$teamStr}"] = ($state["tb_points_{$teamStr}"] ?? 0) + 1;
            $tA = $state['tb_points_a'];
            $tB = $state['tb_points_b'];

            if ($tA >= 7 && $tA - $tB >= 2) {
                $state = $this->awardGame($state, 'A', $match, true);
            } elseif ($tB >= 7 && $tB - $tA >= 2) {
                $state = $this->awardGame($state, 'B', $match, true);
            }
            return $state;
        }

        // ── Advantage / Deuce logic ────────────────────────────────────────────
        if ($state['deuce']) {
            if ($state['golden_point']) {
                // Golden Point: next point wins the game
                $state = $this->awardGame($state, $team, $match);
                $state['deuce'] = false;
                $state['advantage'] = null;
            } elseif ($state['advantage'] === null) {
                // Coming out of deuce — set advantage
                $state['advantage'] = $team;
            } elseif ($state['advantage'] === $team) {
                // Held advantage — win the game
                $state = $this->awardGame($state, $team, $match);
                $state['deuce'] = false;
                $state['advantage'] = null;
            } else {
                // Opponent had advantage — back to deuce
                $state['advantage'] = null;
            }
            return $state;
        }

        // ── Normal point progression ──────────────────────────────────────────
        $state["points_{$teamStr}"] += 1;
        $pA = $state['points_a'];
        $pB = $state['points_b'];

        // Check for deuce (both at 40 = 3)
        if ($pA === 3 && $pB === 3) {
            $state['deuce'] = true;
            return $state;
        }

        // Check for game win
        if ($state["points_{$teamStr}"] >= 4) {
            $state = $this->awardGame($state, $team, $match);
        }

        return $state;
    }

    private function awardGame(array $state, string $team, PadelMatch $match, bool $isTiebreak = false): array
    {
        // Reset points
        $state['points_a'] = 0;
        $state['points_b'] = 0;
        $state['deuce'] = false;
        $state['advantage'] = null;
        $state['in_tiebreak'] = false;
        $state['tb_points_a'] = 0;
        $state['tb_points_b'] = 0;

        $teamStr = strtolower($team);
        $state["games_{$teamStr}"] += 1;
        $gA = $state['games_a'];
        $gB = $state['games_b'];

        // Check for set win
        $setWinner = null;
        if (($gA >= 6 && $gA - $gB >= 2) || ($gA >= 7)) {
            $setWinner = 'A';
        } elseif (($gB >= 6 && $gB - $gA >= 2) || ($gB >= 7)) {
            $setWinner = 'B';
        } elseif ($gA === 6 && $gB === 6) {
            // Enter tiebreak
            $state['in_tiebreak'] = true;
            return $state;
        }

        if ($setWinner) {
            $state = $this->awardSet($state, $setWinner, $gA, $gB, $isTiebreak, $match);
        }

        return $state;
    }

    private function awardSet(array $state, string $winner, int $gA, int $gB, bool $isTiebreak, PadelMatch $match): array
    {
        $state['set_history'][] = [
            'set'      => $state['current_set'],
            'games_a'  => $gA,
            'games_b'  => $gB,
            'winner'   => $winner,
            'tiebreak' => $isTiebreak,
        ];

        $winnerStr = strtolower($winner);
        $state["sets_{$winnerStr}"] += 1;
        $state['games_a'] = 0;
        $state['games_b'] = 0;
        $state['current_set'] += 1;

        $setsToWin = $state['sets_to_win'];

        if ($state["sets_{$winnerStr}"] >= $setsToWin) {
            $state['status'] = 'finished';
            $state['winner'] = $winner;
        }

        return $state;
    }

    private function persistResult(PadelMatch $match, array $state): void
    {
        $match->update([
            'status'     => 'finished',
            'winner_side' => $state['winner'],
        ]);

        foreach ($state['set_history'] as $set) {
            MatchSet::create([
                'match_id'   => $match->id,
                'set_number' => $set['set'],
                'games_a'    => $set['games_a'],
                'games_b'    => $set['games_b'],
                'winner_side' => $set['winner'],
                'is_tiebreak' => $set['tiebreak'],
            ]);
        }
    }
}
