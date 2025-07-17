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
        Schema::create('book_loans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('loan_num');
            $table->date('due_date');
            $table->integer('penalty')->nullable();
            $table->date('return_date')->nullable();
            $table->enum('status', ['dipinjam', 'dikembalikan', 'telat', 'hilang'])->default('dipinjam');
            $table->foreignUuid('member_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('book_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_loans');
    }
};
