<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('description')->nullable();
            $table->boolean('available')->default(0);
            $table->boolean('status')->default(0);
            $table->float('price',8,3);
            $table->foreignId("category_id")
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId("brand_id")
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId("pattern_id")
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('products');
    }
};
