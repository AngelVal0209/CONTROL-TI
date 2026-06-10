<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respaldos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->nullable()->constrained()->nullOnDelete();
            $table->string('tipo');
            $table->string('ubicacion')->nullable();
            $table->dateTime('fecha_respaldo');
            $table->string('tamano')->nullable();
            $table->string('responsable')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respaldos');
    }
};
