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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->string('place')->nullable();
            $table->unsignedBigInteger('assign_by')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->enum('day_time', ['morning', 'evening','daily'])->default('morning');
            $table->enum('work_side', ['front', 'back'])->nullable()->default(null);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
