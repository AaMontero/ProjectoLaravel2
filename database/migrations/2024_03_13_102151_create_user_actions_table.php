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
        Schema::create('user_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // Acción realizada (editar, insertar, etc.)
            $table->string('entity_type'); // Tipo de entidad (cliente, paquete, vendedor, etc.)
            $table->unsignedBigInteger('entity_id')->nullable(); // ID de la entidad afectada
            $table->json('modified_data')->nullable(); // Datos modificados
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_actions');
    }
};
