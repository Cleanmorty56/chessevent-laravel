<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plannings', function (Blueprint $table) {
            $table->id();
            $table->string('content');
            $table->string('organizer', 85)->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('gamemode_id')->constrained('gamemodes');
            $table->string('imageFile');
            $table->integer('quantity_rounds');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->tinyInteger('status')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plannings');
    }
};