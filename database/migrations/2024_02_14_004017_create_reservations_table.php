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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reservation_number')->nullable();
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('service_provider_id')->constrained('users');
            $table->foreignId('category_id')->nullable();
            $table->foreignId('individual_service_id')->nullable();
            $table->foreignId('hotel_id')->nullable();
            $table->foreignId('hotel_service_id')->nullable();
            $table->enum('category_type', ['hotel', 'category'])->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->string('month');
            $table->integer('from');
            $table->integer('to');
            $table->decimal('night_price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('payment_status');
            $table->json('payment_data')->nullable();
            $table->enum('payment_type', ['depit', 'MyFatoorah'])->nullable();
            $table->integer('night_count');
            $table->boolean('status')->default(0);
            $table->longText("notes")->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
