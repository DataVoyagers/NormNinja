<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add due_date to assignments if not exists
        if (Schema::hasTable('assignments') && !Schema::hasColumn('assignments', 'due_date')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->dateTime('due_date')->nullable()->after('description');
            });
        }

        // Add due_date to games if not exists
        if (Schema::hasTable('games') && !Schema::hasColumn('games', 'due_date')) {
            Schema::table('games', function (Blueprint $table) {
                $table->dateTime('due_date')->nullable()->after('game_data');
            });
        }

        // Add due_date to quizzes if not exists
        if (Schema::hasTable('quizzes') && !Schema::hasColumn('quizzes', 'due_date')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->dateTime('due_date')->nullable()->after('available_until');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('assignments', 'due_date')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->dropColumn('due_date');
            });
        }

        if (Schema::hasColumn('games', 'due_date')) {
            Schema::table('games', function (Blueprint $table) {
                $table->dropColumn('due_date');
            });
        }

        if (Schema::hasColumn('quizzes', 'due_date')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->dropColumn('due_date');
            });
        }
    }
};
