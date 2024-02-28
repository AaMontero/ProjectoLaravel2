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
        Schema::create('paquetes', function (Blueprint $table) {
            $table->id();
            $table->string('message'); 
            $table->string('nombre_paquete'); 
            $table->unsignedInteger('num_dias');
            $table->unsignedInteger('num_noches'); 
            $table->string('imagen_paquete'); 
            $table->double('precio_afiliado',6,2); 
            $table->double('precio_no_afiliado',6,2); 
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paquetes');
    }
    
};
