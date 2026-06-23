<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->decimal('estimated_distance_km', 10, 2)->nullable()->after('vehicle_id');
            $table->integer('estimated_duration_minutes')->nullable()->after('estimated_distance_km');
            $table->text('google_maps_url')->nullable()->after('estimated_duration_minutes');
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn([
                'estimated_distance_km',
                'estimated_duration_minutes',
                'google_maps_url',
            ]);
        });
    }
};