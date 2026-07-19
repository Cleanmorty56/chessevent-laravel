<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('white_user_id')->constrained('users');
            $table->foreignId('black_user_id')->nullable()->constrained('users');
            $table->foreignId('tournament_id')->nullable()->constrained('tournaments');
            $table->enum('status', ['pending', 'active', 'finished', 'draw', 'white_win', 'black_win'])->default('pending');
            $table->string('current_fen')->nullable();
            $table->timestamp('last_move_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->foreignId('winner_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};