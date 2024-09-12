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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('id_transaksi')->unique();
            $table->foreignId('users_id')->constrained('users');
            $table->decimal('nilai_transfer', 16, 2);
            $table->foreignId('bank_tujuan')->constrained('banks');
            $table->string('atasnama_tujuan');
            $table->foreignId('bank_pengirim')->constrained('banks');
            $table->integer('kode_unik');
            $table->decimal('biaya_admin', 16, 2)->default(0.00);
            $table->decimal('total_transfer', 16, 2);
            $table->foreignId('bank_perantara')->constrained('banks');
            $table->string('status');
            $table->timestamp('masa_berlaku');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
