<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained()->cascadeOnDelete();
            $table->string('sistema_operativo')->nullable();
            $table->string('version')->nullable();
            $table->string('office')->nullable();
            $table->string('antivirus')->nullable();
            $table->string('cpu')->nullable();
            $table->string('ram')->nullable();
            $table->string('disco')->nullable();
            $table->date('fecha_actualizacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuraciones');
    }
};
