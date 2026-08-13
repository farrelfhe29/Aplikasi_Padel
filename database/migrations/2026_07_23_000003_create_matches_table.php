<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->enum('format', ['best_of_1', 'best_of_3'])->default('best_of_3');
            $table->enum('mode', ['single', 'double'])->default('double');
            $table->boolean('golden_point')->default(false);
            $table->enum('status', ['ongoing', 'finished'])->default('ongoing');
            $table->enum('winner_side', ['A', 'B'])->nullable();
            $table->string('session_token')->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
