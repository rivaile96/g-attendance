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
        Schema::create('overtime_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('overtime_event_id')->constrained()->onDelete('cascade');

            // Ini adalah kolom untuk polymorphic relationship
            $table->unsignedBigInteger('assignable_id');
            $table->string('assignable_type'); // Cth: App\Models\Division

            // Index untuk performa query yang lebih cepat
            $table->index(['assignable_id', 'assignable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtime_assignments');
    }
};