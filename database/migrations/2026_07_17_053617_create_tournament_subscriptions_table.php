<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournament_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->boolean('notify_draw')->default(true);
            $table->boolean('notify_start')->default(true);
            $table->boolean('notify_result')->default(true);
            $table->timestamps();
            
            $table->unique(['user_id', 'tournament_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournament_subscriptions');
    }
};