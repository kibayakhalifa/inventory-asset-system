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
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('user_id');//(for the staff who issued or returned the stuff)
            $table->enum('action', ['issue', 'return']);// defines only the actions that i have listed get that in your head alex
            $table->integer('quantity');
            $table->enum('condition', ['new', 'good', 'worn', 'damaged'])->nullable();
            $table->unsignedBigInteger('lab_id')->nullable(); 
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');// onDeleteCascade -If a row in the items table is deleted, all related rows in your table (where item_id matches the deleted id) will also be deleted automatically.
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lab_id')->references('id')->on('labs')->onDelete('set null');
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
