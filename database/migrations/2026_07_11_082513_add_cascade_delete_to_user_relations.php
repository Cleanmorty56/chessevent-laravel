<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Для reg_to_tournaments
        Schema::table('reg_to_tournaments', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->dropForeign(['user_id']);
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        // Для tournament_matches
        Schema::table('tournament_matches', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->dropForeign(['white_player_id']);
            $table->dropForeign(['black_player_id']);
            $table->dropForeign(['winner_id']);
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
            $table->foreign('white_player_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('black_player_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('winner_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        // Для tournament_byes
        Schema::table('tournament_byes', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->dropForeign(['user_id']);
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        // Для planning
        Schema::table('plannings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Для reg_to_tournaments - возвращаем без каскадного удаления
        Schema::table('reg_to_tournaments', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->dropForeign(['user_id']);
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->foreign('user_id')->references('id')->on('users');
        });
        
        // Для tournament_matches - возвращаем без каскадного удаления
        Schema::table('tournament_matches', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->dropForeign(['white_player_id']);
            $table->dropForeign(['black_player_id']);
            $table->dropForeign(['winner_id']);
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->foreign('white_player_id')->references('id')->on('users');
            $table->foreign('black_player_id')->references('id')->on('users');
            $table->foreign('winner_id')->references('id')->on('users');
        });
        
        // Для tournament_byes - возвращаем без каскадного удаления
        Schema::table('tournament_byes', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->dropForeign(['user_id']);
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->foreign('user_id')->references('id')->on('users');
        });
        
        // Для planning - возвращаем без каскадного удаления
        Schema::table('plannings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};