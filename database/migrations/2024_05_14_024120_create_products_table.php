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
        Schema::create('products', function (Blueprint $table) {
            $table->id()->primary()->autoIncrement();
            $table->integer('product_id')->unique();
            $table->string('product_name');
            $table->enum('category', ['Makanan', 'Minuman', 'Kesehatan', 'Elektronik', 'Fashion', 'Perawatan Tubuh', 'Lainnya']);
            $table->decimal('price', 10, 2);
            $table->integer('stock');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
