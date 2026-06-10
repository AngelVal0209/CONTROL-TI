<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('serie');
            $table->string('nombre_equipo');
            $table->text('descripcion')->nullable();
            $table->string('tipo');
            $table->string('marca');
            $table->string('modelo')->nullable();
            $table->string('propietario')->nullable();
            $table->string('area');
            $table->string('puesto_trabajo')->nullable();
            $table->string('estado')->default('operativo');
            $table->string('condicion')->default('bueno');
            $table->date('fecha_adquisicion')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
