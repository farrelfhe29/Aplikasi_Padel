<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('americano_tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedTinyInteger('points_per_game')->default(24); // 16, 24, or 32
            $table->enum('status', ['ongoing', 'finished'])->default('ongoing');
            $table->unsignedTinyInteger('total_rounds')->default(0); // calculated on create
            $table->unsignedTinyInteger('current_round')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('americano_tournaments');
    }
};
