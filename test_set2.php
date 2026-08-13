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
    'points_a'      => 3,
    'points_b'      => 0,
    'deuce'         => false,
    'advantage'     => null,
    'in_tiebreak'   => false,
    'tb_points_a'   => 0,
    'tb_points_b'   => 0,
    'games_a'       => 5,
    'games_b'       => 0,
    'sets_a'        => 0, 
    'sets_b'        => 0,
    'current_set'   => 1,
    'set_history'   => [],
    'status'        => 'ongoing',
    'winner'        => null,
    'history'       => [],
];

$method = new ReflectionMethod($controller, 'processPoint');
$method->setAccessible(true);
$newState = $method->invoke($controller, $state, 'A', 'B', $match);

echo "After winning Set 1:\n";
echo "Current Set: " . $newState['current_set'] . "\n";
echo "Sets A: " . $newState['sets_a'] . "\n";
echo "Games A: " . $newState['games_a'] . "\n";
echo "Points A: " . $newState['points_a'] . "\n";

// Now score a point in Set 2
$newState2 = $method->invoke($controller, $newState, 'A', 'B', $match);
echo "\nAfter scoring first point in Set 2:\n";
echo "Current Set: " . $newState2['current_set'] . "\n";
echo "Sets A: " . $newState2['sets_a'] . "\n";
echo "Games A: " . $newState2['games_a'] . "\n";
echo "Points A: " . $newState2['points_a'] . "\n";
