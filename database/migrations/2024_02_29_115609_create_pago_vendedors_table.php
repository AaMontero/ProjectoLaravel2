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
        Schema::create('pago_vendedors', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->decimal('valor_pago', 10, 2)->nullable();
            $table->date("fecha_pago"); 
            $table->string("concepto"); 
            $table->string("estado");  
            $table->foreignId("vendedor_id")->constrained("vendedors"); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago_vendedors');
    }
};
