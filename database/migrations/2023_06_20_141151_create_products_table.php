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
            $table->id();
            $table->integer('bisnis_id');
            $table->integer('cabang_id');
            $table->integer('category_id');
            $table->string('name');
            $table->integer('modal');
            $table->integer('price');
            $table->integer('stock');
            $table->enum('satuan',['pcs','bundle'])->default('pcs');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_jual')->default(true);
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
