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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('cliente_user');
            $table->string('cedula');
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('numTelefonico')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('email');
            $table->string('provincia');
            $table->string('ciudad');
            $table->boolean('activo')->default(true);
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
