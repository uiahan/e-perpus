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
        Schema::create('book_loan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('book_loan_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('book_id')->constrained()->cascadeOnDelete();
            $table->date('return_date')->nullable();
            $table->integer('penalty')->nullable();
            $table->enum('status', ['dipinjam', 'dikembalikan', 'telat', 'hilang'])->default('dipinjam');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_loan_items');
    }
};
