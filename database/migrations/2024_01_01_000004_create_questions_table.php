<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->onDelete('cascade');
            $table->string('category'); // multiple_choice, fill_blank, code_complete
            $table->integer('difficulty')->default(1); // 1-3
            $table->text('question_text');
            $table->text('code_snippet')->nullable();
            $table->json('options'); // array of 4 options
            $table->string('correct_answer');
            $table->text('explanation');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
