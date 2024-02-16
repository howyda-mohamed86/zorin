<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('address_books', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->foreignId("user_id");
            $table->foreignId("zone_id");
            $table->string("state");
            $table->text("street");
            $table->text("building_number")->nullable();
            $table->string('floor')->nullable();
            $table->string("phone");
            $table->json("map_location")->nullable();
            $table->longText("notes")->nullable();
            $table->boolean("primary")->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('address_books');
    }
};
