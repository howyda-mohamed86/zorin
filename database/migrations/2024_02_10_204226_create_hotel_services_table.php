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
        Schema::create('hotel_services', function (Blueprint $table) {
            $table->id();
            $table->json('service_name');
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade')->onUpdate('cascade');
            $table->json('description_service')->nullable();
            $table->decimal('price_night', 10, 2)->nullable();
            $table->string('area')->nullable();
            $table->integer('number_of_rooms')->nullable();
            $table->integer('number_of_beds')->nullable();
            $table->integer('number_of_children')->nullable();
            $table->integer('number_of_adults')->nullable();
            $table->json('public_utilities')->nullable();
            $table->json('location')->nullable();
            $table->boolean('status')->default(1);
            $table->foreignId('created_by')->constrained('users')->nullable()->onDelete('cascade')->onUpdate('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_services');
    }
};
