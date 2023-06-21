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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('bisnis_id');
            $table->integer('cabang_id');
            $table->integer('user_id');
            $table->integer('client_id');
            $table->string('reff');
            $table->integer('grandtotal');
            $table->integer('amount');
            $table->integer('payment_id');
            $table->enum('tipe',['transaksi','open bill'])->default('transaksi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
