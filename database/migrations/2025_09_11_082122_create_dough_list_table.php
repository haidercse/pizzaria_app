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
        Schema::create('dough_list', function (Blueprint $table) {
            $table->id();
            $table->string('dough_litter');
            $table->string('dough_total_weight')->nullable();
            $table->string('dough_num_of_cajas')->nullable();
            $table->string('day')->nullable();
            $table->date('date')->nullable();
            $table->string('status')->nullable()->default('1');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dough_list');
    }
};
