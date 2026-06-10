<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('usuario_reporta_id')->constrained('users')->cascadeOnDelete();
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('estado')->default('pendiente');
            $table->string('prioridad')->default('media');
            $table->dateTime('fecha_reporte');
            $table->dateTime('fecha_resolucion')->nullable();
            $table->text('evidencias')->nullable();
            $table->text('solucion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidentes');
    }
};
