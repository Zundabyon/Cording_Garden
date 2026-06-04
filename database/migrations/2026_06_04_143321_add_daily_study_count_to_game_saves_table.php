<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_saves', function (Blueprint $table) {
            $table->unsignedTinyInteger('daily_study_count')->default(0)->after('is_weekend');
            $table->unsignedBigInteger('current_study_character_id')->nullable()->after('daily_study_count');
        });
    }

    public function down(): void
    {
        Schema::table('game_saves', function (Blueprint $table) {
            $table->dropColumn(['daily_study_count', 'current_study_character_id']);
        });
    }
};
