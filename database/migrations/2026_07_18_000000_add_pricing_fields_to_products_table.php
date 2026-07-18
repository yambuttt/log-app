<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('harga_modal', 12, 2)->default(0)->after('weight_kg');
            $table->decimal('harga_jual', 12, 2)->default(0)->after('harga_modal');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['harga_modal', 'harga_jual']);
        });
    }
};
