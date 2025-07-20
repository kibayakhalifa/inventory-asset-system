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
            $table->enum('action', ['issued', 'returned']);// defines only the actions that i have listed get that in your head alex
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');// onDeleteCascade -If a row in the items table is deleted, all related rows in your table (where item_id matches the deleted id) will also be deleted automatically.
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
