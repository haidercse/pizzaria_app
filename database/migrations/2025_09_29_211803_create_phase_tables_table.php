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
        Schema::create('phase_tables', function (Blueprint $table) {
            $table->id();
            $table->decimal('water_l', 8, 2);
            $table->decimal('phase1_tipo00', 8, 2);
            $table->decimal('phase2_tipo00', 8, 2);
            $table->decimal('phase2_tipo1', 8, 2);
            $table->decimal('first_15min', 8, 2);
            $table->decimal('second_8min', 8, 2);
            $table->decimal('third_8min', 8, 2);
            $table->decimal('fourth_8min', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phase_tables');
    }
};
