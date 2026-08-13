<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('americano_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('americano_tournaments')->cascadeOnDelete();
            $table->string('name');
            $table->unsignedSmallInteger('total_points')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('americano_players');
    }
};
