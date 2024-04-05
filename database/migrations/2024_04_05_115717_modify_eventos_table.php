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
        Schema::table('eventos', function (Blueprint $table) {
            // Eliminar la columna 'author'
            $table->dropColumn('author');

            // Agregar la columna 'cliente_id' como clave foránea
            $table->unsignedBigInteger('cliente_id')->after('id');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            // Revertir los cambios: recrear la columna 'author' y eliminar la columna 'cliente_id'
            $table->string('author')->nullable();
            $table->dropForeign(['cliente_id']);
            $table->dropColumn('cliente_id');
        });
    }
};
