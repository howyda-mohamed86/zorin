<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('customer_id');
            $table->timestamp('date');
            $table->string('status');
            $table->string('payment_status');
            $table->json('payment_data')->nullable();
            $table->float('total', 8, 3);
            $table->json('data');
            $table->longText("notes");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
