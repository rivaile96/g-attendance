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
        Schema::create('overtime_events', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nama atau judul event lembur');
            $table->text('description')->nullable()->comment('Deskripsi pekerjaan lembur');
            $table->date('start_date')->comment('Tanggal mulai periode lembur');
            $table->date('end_date')->comment('Tanggal selesai periode lembur');
            $table->time('start_time')->comment('Jam mulai lembur setiap harinya');
            $table->time('end_time')->comment('Jam selesai lembur setiap harinya');
            $table->foreignId('created_by')->constrained('users')->comment('Admin yang membuat event');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtime_events');
    }
};