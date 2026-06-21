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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->string('id_pelanggan');
            $table->string('id_teknisi');
            $table->string('serial_number')->nullable();
            $table->enum('tipe_alur', ['Pengambilan', 'Pengembalian', 'Dismantling']);
            $table->enum('status_approval', ['Pending', 'In_Hand', 'Approved_by_Admin', 'Rejected'])->default('Pending');
            $table->string('foto_bukti')->nullable();
            $table->text('alasan_rusak')->nullable();
            $table->timestamps();

            // Set up foreign key constraints
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('customers')->onDelete('cascade');
            $table->foreign('id_teknisi')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('serial_number')->references('serial_number')->on('devices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
