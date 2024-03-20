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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('hotel_nombre');
            $table->string('imagen_hotel');
            $table->string('pais');
            $table->string('provincia')->nullable();
            $table->string('ciudad');
            $table->unsignedInteger('num_h');
            $table->unsignedInteger('num_camas');
            $table->double('precio',6,2);
            $table->string('servicios');
            $table->string('tipo_alojamiento');
            $table->string('opiniones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
