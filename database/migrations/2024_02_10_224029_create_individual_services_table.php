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
        Schema::create('individual_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('service_provider_id')->constrained('users')->nullable();
            $table->json('service_name');
            $table->json('service_description');
            $table->decimal('price_night', 10, 2);
            $table->string('area');
            $table->integer('number_of_rooms');
            $table->integer('number_of_beds');
            $table->integer('number_of_adults');
            $table->integer('number_of_children');
            $table->json('public_utilities')->nullable();
            $table->json('location');
            $table->json('address');
            $table->json('notes')->nullable();
            $table->boolean('status')->default(1);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individual_services');
    }
};
