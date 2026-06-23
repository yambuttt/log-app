<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_capacities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('max_qty');
            $table->timestamps();

            $table->unique(['vehicle_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_capacities');
    }
};