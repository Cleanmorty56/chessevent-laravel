<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gamemodes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('control_time', 60);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gamemodes');
    }
};