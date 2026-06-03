<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->onDelete('cascade');
            $table->integer('trigger_day')->nullable();
            $table->integer('trigger_affection')->nullable();
            $table->string('trigger_action')->nullable();
            $table->string('title');
            $table->json('content'); // array of {speaker, text, expression}
            $table->boolean('is_repeatable')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
