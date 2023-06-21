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
        Schema::create('pay_methods', function (Blueprint $table) {
            $table->id();
            $table->integer('bisnis_id');
            $table->integer('cabang_id');
            $table->string('name');
            $table->enum('tipe',['Transfer Bank','E-Wallet','QRIS','lainnya'])->default('lainnya');
            $table->integer('norek')->default(0);
            $table->boolean('is_active')->default('true');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_methods');
    }
};
