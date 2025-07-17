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
            $table->string('loan_num')->unique();
            $table->foreignUuid('member_id')->constrained()->cascadeOnDelete();
            $table->date('due_date');
            $table->enum('status', ['Dalam Masa Pinjaman', 'Sudah Dikembalikan', 'Melebihi Tenggat Waktu'])->default('Dalam Masa Pinjaman'); 
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
