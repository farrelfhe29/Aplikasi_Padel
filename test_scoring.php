<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$match = App\Models\PadelMatch::first();
$controller = new App\Http\Controllers\MatchController();

$state = [
    'match_id'      => $match->id,
    'mode'          => $match->mode,
    'format'        => 'best_of_3',
    'golden_point'  => false,
    'sets_to_win'   => 2,
    'points_a'      => 0,
    'points_b'      => 0,
    'deuce'         => false,
    'advantage'     => null,
    'in_tiebreak'   => false,
    'tb_points_a'   => 0,
    'tb_points_b'   => 0,
    'games_a'       => 0,
    'games_b'       => 0,
    'sets_a'        => 1, // A already won 1 set
    'sets_b'        => 0,
    'current_set'   => 2,
    'set_history'   => [],
    'status'        => 'ongoing',
    'winner'        => null,
    'history'       => [],
];

// simulate A winning the second set
// Give A 5 games
$state['games_a'] = 5;
$state['points_a'] = 3; // 40

// Now process one more point for A
$method = new ReflectionMethod($controller, 'processPoint');
$method->setAccessible(true);
$newState = $method->invoke($controller, $state, 'A', 'B', $match);

echo "Sets A: " . $newState['sets_a'] . "\n";
echo "Status: " . $newState['status'] . "\n";
echo "Winner: " . $newState['winner'] . "\n";
