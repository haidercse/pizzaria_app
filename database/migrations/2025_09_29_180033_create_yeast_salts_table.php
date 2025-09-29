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
        Schema::create('yeast_salts', function (Blueprint $table) {
            $table->id();
            $table->integer('water_l');
            $table->decimal('y07', 5, 1);
            $table->decimal('y08', 5, 1);
            $table->decimal('y09', 5, 1);
            $table->decimal('y10', 5, 1);
            $table->decimal('y11', 5, 1);
            $table->decimal('y12', 5, 1);
            $table->decimal('y13', 5, 1);
            $table->decimal('salt38', 6, 0);
            $table->decimal('salt39', 6, 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yeast_salts');
    }
};
