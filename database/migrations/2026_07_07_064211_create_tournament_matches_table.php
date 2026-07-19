<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournament_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('tournaments');
            $table->integer('round');
            $table->foreignId('white_player_id')->constrained('users');
            $table->foreignId('black_player_id')->constrained('users');
            $table->enum('result', ['pending', 'white_win', 'black_win', 'draw'])->default('pending');
            $table->foreignId('winner_id')->nullable()->constrained('users');
            $table->enum('status', ['pending', 'played'])->default('pending');
            $table->timestamp('played_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournament_matches');
    }
};