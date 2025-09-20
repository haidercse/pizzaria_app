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
        Schema::create('employee_availabilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id'); // which employee
            $table->date('date'); // available date
            $table->string('preferred_time'); // morning, evening, full_day
            $table->time('start_time')->nullable(); // custom 
            $table->time('end_time')->nullable();
            $table->text('note')->nullable(); // optional note
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_availabilities');
    }
};
