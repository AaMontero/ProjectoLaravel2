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
            Schema::create('contratos', function (Blueprint $table) {
                $table->id('contrato_id');
                $table->string('ubicacion_sala');
                $table->integer('anios_contrato');
                $table->integer('monto_contrato'); 
                $table->boolean('bono_hospedaje_qori_loyalty')->default(false);
                $table->boolean('bono_hospedaje_internacional')->default(false);
                $table->decimal('valor_total_credito_directo', 10, 2)->nullable();
                $table->integer('meses_credito_directo')->nullable();
                $table->decimal('abono_credito_directo', 10, 2)->nullable();
                $table->decimal('valor_pagare', 10, 2)->nullable();
                $table->date('fecha_fin_pagare')->nullable();
                $table->string('comentario')->nullable();
                $table->string('otro_comentario')->nullable();
                $table->decimal('otro_valor', 10, 2)->nullable();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('cliente_id')->constrained()->cascadeOnDelete(); 
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
