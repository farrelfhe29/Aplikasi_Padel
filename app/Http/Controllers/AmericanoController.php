<?php

namespace App\Http\Controllers;

use App\Models\AmericanoGame;
use App\Models\AmericanoPlayer;
use App\Models\AmericanoTournament;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AmericanoController extends Controller
{
    /**
     * Show tournament creation form.
     */
    public function create(): View
    {
        return view('americano.create');
    }

    /**
     * Store tournament, create players, and generate full schedule.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'            => ['required', 'string', 'max:100'],
            'points_per_game' => ['required', 'in:16,21,24,32'],
            'players'         => ['required', 'array', 'min:4', 'max:12'],
            'players.*'       => ['required', 'string', 'max:50'],
        ]);

        $playerNames = array_filter(array_map('trim', $request->players));
        $playerCount = count($playerNames);

        if ($playerCount < 4 || $playerCount > 12) {
            return back()->withErrors(['players' => 'Jumlah pemain harus antara 4 hingga 12.'])->withInput();
        }

        // Determine number of rounds (sync with model logic)
        $totalRounds = ($playerCount % 4 === 0) ? $playerCount - 1 : $playerCount;
        if ($playerCount === 5) $totalRounds = 5;
        if ($playerCount === 6) $totalRounds = 6;
        if ($playerCount === 7) $totalRounds = 7;

        $tournament = AmericanoTournament::create([
            'name'            => $request->name,
            'points_per_game' => $request->points_per_game,
            'total_rounds'    => $totalRounds,
            'current_round'   => 1,
            'status'          => 'ongoing',
        ]);

        // Create player records
        $players = [];
        foreach ($playerNames as $name) {
            $players[] = AmericanoPlayer::create([
                'tournament_id' => $tournament->id,
                'name'          => $name,
                'total_points'  => 0,
            ]);
        }

        // Generate schedule
        $playerIds = collect($players)->pluck('id')->toArray();
        $schedule  = AmericanoTournament::generateSchedule($playerIds);

        // Save all games at once
        foreach ($schedule as $roundIndex => $roundGames) {
            foreach ($roundGames as $gameData) {
                AmericanoGame::create([
                    'tournament_id' => $tournament->id,
                    'round'         => $gameData['round'],
                    'court'         => $gameData['court'],
                    'player_a1_id'  => $gameData['player_a1'],
                    'player_a2_id'  => $gameData['player_a2'],
                    'player_b1_id'  => $gameData['player_b1'],
                    'player_b2_id'  => $gameData['player_b2'],
                    'score_a'       => 0,
                    'score_b'       => 0,
                    'status'        => $roundIndex === 0 ? 'ongoing' : 'pending',
                ]);
            }
        }

        return redirect()->route('americano.round', [$tournament->id, 1]);
    }

    /**
     * Show all games for a given round.
     */
    public function round(AmericanoTournament $tournament, int $round): View
    {
        if ($tournament->status === 'finished') {
            return redirect()->route('americano.leaderboard', $tournament->id);
        }

        $games = $tournament->games()
            ->where('round', $round)
            ->with(['playerA1', 'playerA2', 'playerB1', 'playerB2'])
            ->orderBy('court')
            ->get();

        return view('americano.round', compact('tournament', 'games', 'round'));
    }

    /**
     * Live scoreboard for a single game (AJAX).
     */
    public function scoreboard(AmericanoTournament $tournament, AmericanoGame $game): View
    {
        $game->load('playerA1', 'playerA2', 'playerB1', 'playerB2');
        return view('americano.scoreboard', compact('tournament', 'game'));
    }

    /**
     * Add a point (called by Alpine.js via fetch).
     */
    public function addPoint(Request $request, AmericanoTournament $tournament, AmericanoGame $game): JsonResponse
    {
        $request->validate(['team' => ['required', 'in:A,B']]);

        if ($game->status === 'finished' || $tournament->status === 'finished') {
            return response()->json(['error' => 'Game sudah selesai'], 422);
        }

        $team    = $request->team;
        $field   = $team === 'A' ? 'score_a' : 'score_b';
        $newScore = $game->$field + 1;

        $game->update([$field => $newScore]);

        $totalPoints = $game->fresh()->score_a + $game->fresh()->score_b;
        $isFinished  = $totalPoints >= $tournament->points_per_game;

        if ($isFinished) {
            $game->update(['status' => 'finished']);
            $this->applyPointsToPlayers($game->fresh());
        }

        return response()->json([
            'score_a'     => $game->fresh()->score_a,
            'score_b'     => $game->fresh()->score_b,
            'status'      => $game->fresh()->status,
            'max_points'  => $tournament->points_per_game,
            'total_played'=> $game->fresh()->score_a + $game->fresh()->score_b,
        ]);
    }

    /**
     * Manually finish a game (e.g. if match ends before reaching max points).
     */
    public function finishGame(AmericanoTournament $tournament, AmericanoGame $game): RedirectResponse
    {
        if ($game->status !== 'finished') {
            $game->update(['status' => 'finished']);
            $this->applyPointsToPlayers($game);
        }

        // Check if all games in the current round are done
        $pendingInRound = $tournament->games()
            ->where('round', $game->round)
            ->whereIn('status', ['pending', 'ongoing'])
            ->count();

        if ($pendingInRound === 0) {
            // All games in this round are finished — advance round
            $nextRound = $game->round + 1;
            if ($nextRound > $tournament->total_rounds) {
                // All rounds done — finish tournament
                $tournament->update(['status' => 'finished']);
                return redirect()->route('americano.leaderboard', $tournament->id);
            }
            // Activate next round games
            $tournament->games()
                ->where('round', $nextRound)
                ->update(['status' => 'ongoing']);
            $tournament->update(['current_round' => $nextRound]);
            return redirect()->route('americano.round', [$tournament->id, $nextRound]);
        }

        return redirect()->route('americano.round', [$tournament->id, $game->round]);
    }

    /**
     * Advance to the next round if all games are finished.
     */
    public function advanceRound(AmericanoTournament $tournament, int $round): RedirectResponse
    {
        // Check if all games in the given round are done
        $pendingInRound = $tournament->games()
            ->where('round', $round)
            ->whereIn('status', ['pending', 'ongoing'])
            ->count();

        if ($pendingInRound === 0) {
            $nextRound = $round + 1;
            if ($nextRound > $tournament->total_rounds) {
                $tournament->update(['status' => 'finished']);
                return redirect()->route('americano.leaderboard', $tournament->id);
            }
            // Activate next round games
            $tournament->games()
                ->where('round', $nextRound)
                ->where('status', 'pending')
                ->update(['status' => 'ongoing']);
            $tournament->update(['current_round' => $nextRound]);
            return redirect()->route('americano.round', [$tournament->id, $nextRound]);
        }

        return redirect()->back()->withErrors(['msg' => 'Masih ada game yang belum selesai.']);
    }

    /**
     * Show the final leaderboard.
     */
    public function leaderboard(AmericanoTournament $tournament): View
    {
        $leaderboard = $tournament->getLeaderboard();
        $allGames    = $tournament->games()->with(['playerA1', 'playerA2', 'playerB1', 'playerB2'])->orderBy('round')->orderBy('court')->get();
        return view('americano.leaderboard', compact('tournament', 'leaderboard', 'allGames'));
    }

    // ─── Private helpers ────────────────────────────────────────────────────────

    private function applyPointsToPlayers(AmericanoGame $game): void
    {
        // Each player on the winning side gets their team's score added to their total
        $game->playerA1()->increment('total_points', $game->score_a);
        $game->playerA2()->increment('total_points', $game->score_a);
        $game->playerB1()->increment('total_points', $game->score_b);
        $game->playerB2()->increment('total_points', $game->score_b);
    }
}
