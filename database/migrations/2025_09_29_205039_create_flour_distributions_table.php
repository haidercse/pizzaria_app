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
        Schema::create('flour_distributions', function (Blueprint $table) {
            $table->id();
            $table->decimal('water_l', 8, 2);
            $table->decimal('total_flour', 8, 2);
            $table->decimal('tipo_00', 8, 2);
            $table->decimal('tipo_1', 8, 2);
            $table->decimal('dough_kg', 8, 2);
            $table->decimal('cajas', 8, 2);
            $table->decimal('divide_boxes',8,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flour_distributions');
    }
};
