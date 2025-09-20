<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom shift_id setelah division_id
            $table->foreignId('shift_id')->nullable()->after('division_id')->constrained()->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cara menghapus foreign key
            $table->dropForeign(['shift_id']);
            $table->dropColumn('shift_id');
        });
    }
};