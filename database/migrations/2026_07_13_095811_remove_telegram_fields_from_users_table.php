<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Reverse the migrations.
     */
    public function up()
    {
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['telegram_id', 'telegram_username', 'telegram_verified_at']);
    });
    }
};
