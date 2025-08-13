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
        Schema::table('book_loans', function (Blueprint $table) {
            $table->enum('status', ['Pengajuan', 'Dalam Masa Pinjaman', 'Sudah Dikembalikan', 'Melebihi Tenggat Waktu'])->default('Dalam Masa Pinjaman')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_loans', function (Blueprint $table) {
            //
        });
    }
};
