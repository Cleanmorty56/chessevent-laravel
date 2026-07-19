<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reg_to_tournaments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('tournaments');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('registration_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reg_to_tournaments');
    }
};