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
        Schema::create('caracteristica_paquetes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('paquete_id')->constrained()->cascadeOnDelete(); 
            $table->string('descripcion'); 
            $table->string('lugar')->default("Incluye"); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caracteristica_paquetes');
    }
};
