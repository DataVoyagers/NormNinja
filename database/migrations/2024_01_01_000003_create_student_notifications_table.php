<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['general', 'quiz', 'game', 'material', 'reminder', 'event'])->default('general');
            $table->foreignId('reference_id')->nullable(); // ID of quiz/game/material if applicable
            $table->string('reference_type')->nullable(); // Model type (Quiz, Game, LearningMaterial)
            $table->timestamps();
            
            // Index for faster queries
            $table->index('teacher_id');
            $table->index('created_at');
        });

        // Pivot table for which students have read the notification
        Schema::create('notification_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_notification_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->unique(['student_notification_id', 'student_id']);
            $table->index(['student_id', 'is_read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_reads');
        Schema::dropIfExists('student_notifications');
    }
};
