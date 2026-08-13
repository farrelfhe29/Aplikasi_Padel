<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->cascadeOnDelete();
            $table->integer('set_number');
            $table->integer('games_a')->default(0);
            $table->integer('games_b')->default(0);
            $table->enum('winner_side', ['A', 'B'])->nullable();
            $table->boolean('is_tiebreak')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_sets');
    }
};
