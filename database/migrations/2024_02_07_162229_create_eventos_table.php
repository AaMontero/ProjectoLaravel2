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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->enum('title', ['prereservado', 'reservado', 'disponible']);// Titular o persona que reserva
            $table->dateTime('start_date'); // Fecha y hora de inicio
            $table->dateTime('end_date'); // Fecha y hora de fin
            $table->string('author'); // Estado del evento
            $table->text('note')->nullable(); // Comentario
            $table->unsignedBigInteger('user_id'); // ID del usuario que registra la reserva
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
