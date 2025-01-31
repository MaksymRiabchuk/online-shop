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
        Schema::table('order_products', function (Blueprint $table) {
            // Видаляємо старі зовнішні ключі
            $table->dropForeign(['product_id']);
            $table->dropForeign(['order_id']);

            // Додаємо нові зовнішні ключі з каскадним видаленням
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('order_products', function (Blueprint $table) {
            // Видаляємо нові зовнішні ключі
            $table->dropForeign(['product_id']);
            $table->dropForeign(['order_id']);

            // Додаємо старі зовнішні ключі без каскадного видалення
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }
};
