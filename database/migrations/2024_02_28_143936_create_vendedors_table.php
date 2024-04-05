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
        Schema::create('vendedors', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->String("nombres");
            $table->String("rol");
            $table->boolean("activo")->default(true);
            $table->decimal('saldo_pendiente', 10, 2)->nullable();
            $table->foreignId('user_vend_id')->constrained('users')->default(1);
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendedors');
    }
};
