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
        Schema::create('employee_checkouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->date('date'); // আজকের তারিখ
            $table->string('day'); // Monday, Tuesday etc.
            $table->string('place'); // andel / nusle / event
            $table->decimal('worked_hours', 5, 2); // কত ঘণ্টা কাজ করেছে
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_checkouts');
    }
};
