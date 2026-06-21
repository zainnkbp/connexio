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
        Schema::create('devices', function (Blueprint $table) {
            $table->string('serial_number')->primary();
            $table->string('jenis_merek'); // STB Huawei, STB ZTE, Modem ZTE, Modem Huawei, etc.
            $table->string('tipe_perangkat'); // huawei790, zte6108, etc.
            $table->enum('status_kondisi', ['Terpasang', 'Rusak', 'Dismantling'])->nullable();
            $table->text('alasan_rusak')->nullable();
            $table->dateTime('tanggal_pasang_awal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
