<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('name_kana')->nullable();
            $table->string('gender'); // female, male, mysterious
            $table->text('personality');
            $table->string('subject'); // php, laravel, html, css, js, typescript, vue, error
            $table->text('description')->nullable();
            $table->boolean('is_unlocked')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
