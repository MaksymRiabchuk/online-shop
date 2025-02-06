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
        Schema::table('property_products', function (Blueprint $table) {
            $table->foreignId('value_id')->nullable()->constrained('property_values')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_products', function (Blueprint $table) {
            $table->dropForeign(['value_id']);
            $table->dropColumn('value_id');
        });
    }
};
