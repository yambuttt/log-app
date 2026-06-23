<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_trip_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_trip_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('route_order')->nullable();
            $table->decimal('distance_from_previous_km', 10, 2)->nullable();
            $table->timestamps();

            $table->unique(['delivery_trip_id', 'shipment_id']);
            $table->unique(['delivery_trip_id', 'route_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_trip_shipments');
    }
};