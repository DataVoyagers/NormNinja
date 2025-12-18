<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('schedules', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id'); // whose schedule
        $table->string('title');              // event title
        $table->date('date');                 // event date
        $table->time('time')->nullable();     // event time (optional)
        $table->text('notes')->nullable();    // extra notes
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
