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
        Schema::create('overtime_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('overtime_event_id')->nullable()->constrained()->onDelete('set null');
            $table->dateTime('start_time')->comment('Waktu aktual karyawan mulai lembur');
            $table->dateTime('end_time')->nullable()->comment('Waktu aktual karyawan selesai lembur');
            $table->text('notes')->nullable()->comment('Catatan pekerjaan yang dilakukan saat lembur');
            $table->string('status')->default('Pending')->comment('Status: Pending, Approved, Rejected');
            $table->foreignId('approved_by')->nullable()->constrained('users')->comment('Admin yang memproses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtime_logs');
    }
};