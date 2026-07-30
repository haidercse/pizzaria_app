<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employee_availabilities', function (Blueprint $table) {
            $table->decimal('nusle_total_tips', 10, 2)->nullable()->after('place');

            $table->decimal('andel_total_tips', 10, 2)->nullable()->after('nusle_total_tips');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_availabilities', function (Blueprint $table) {
            $table->dropColumn(['nusle_total_tips', 'andel_total_tips']);
        });
    }
};
