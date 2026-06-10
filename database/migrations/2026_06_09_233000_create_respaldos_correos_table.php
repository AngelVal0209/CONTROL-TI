<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respaldos_correos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('archivo')->nullable();
            $table->string('tamano')->nullable();
            $table->dateTime('fecha_respaldo');
            $table->string('responsable')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('respaldos_bd', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('archivo')->nullable();
            $table->string('tamano')->nullable();
            $table->dateTime('fecha_respaldo');
            $table->string('responsable')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respaldos_bd');
        Schema::dropIfExists('respaldos_correos');
    }
};
