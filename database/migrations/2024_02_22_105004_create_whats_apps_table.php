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
        Schema::create('whats_apps', function (Blueprint $table) {
            $table->id();
            $table->string('id_numCliente', 15);
            $table->dateTime('fecha_hora')->default(now());
            $table->string('mensaje_enviado', 1000)->default('');
            $table->string('id_wa', 1000)->default('');
            $table->bigInteger('timestamp_wa')->nullable();
            $table->string('telefono_wa', 50)->default('');
            $table->boolean("visto")->default(false); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whats_apps');
    }
};
