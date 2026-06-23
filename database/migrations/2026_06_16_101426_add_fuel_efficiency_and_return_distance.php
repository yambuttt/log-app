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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->decimal('fuel_efficiency', 8, 2)->default(10.00)->after('vehicle_type');
        });

        Schema::table('delivery_trips', function (Blueprint $table) {
            $table->decimal('return_distance_km', 10, 2)->nullable()->after('total_estimated_distance_km');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('fuel_efficiency');
        });

        Schema::table('delivery_trips', function (Blueprint $table) {
            $table->dropColumn('return_distance_km');
        });
    }
};
