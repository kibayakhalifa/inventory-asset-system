<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['uniform', 'stationery', 'equipment', 'chemical']);
            $table->integer('quantity_total')->default(0);
            $table->integer('quantity_available')->default(0);
            $table->boolean('issued_once')->default(false);
            $table->integer('reorder_threshold')->default(10);
            $table->unsignedBigInteger('lab_id')->nullable();
            $table->timestamps();

            $table->foreign('lab_id')->references('id')->on('labs')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
