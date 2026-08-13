<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('americano_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('americano_tournaments')->cascadeOnDelete();
            $table->unsignedTinyInteger('round');
            $table->unsignedTinyInteger('court')->default(1); // for 8+ players, multiple courts
            // Players: A1+A2 vs B1+B2
            $table->foreignId('player_a1_id')->constrained('americano_players')->cascadeOnDelete();
            $table->foreignId('player_a2_id')->constrained('americano_players')->cascadeOnDelete();
            $table->foreignId('player_b1_id')->constrained('americano_players')->cascadeOnDelete();
            $table->foreignId('player_b2_id')->constrained('americano_players')->cascadeOnDelete();
            $table->unsignedTinyInteger('score_a')->default(0);
            $table->unsignedTinyInteger('score_b')->default(0);
            $table->enum('status', ['pending', 'ongoing', 'finished'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('americano_games');
    }
};
