<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('img');
            $table->string('name', 90);
            $table->string('description');
            $table->foreignId('gamemode_id')->constrained('gamemodes');
            $table->string('location', 90);
            $table->integer('quantity_rounds');
            $table->enum('status', ['Запланирован', 'В процессе', 'Завершен'])->default('Запланирован');
            $table->foreignId('level_id')->constrained('levels');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};