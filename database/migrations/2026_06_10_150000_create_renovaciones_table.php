<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('renovaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('proveedor')->nullable();
            $table->string('tipo'); // licencia, suscripcion, servicio, pago_proveedor, etc
            $table->decimal('monto', 12, 2)->nullable();
            $table->string('moneda')->default('PEN');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_vencimiento');
            $table->string('periodo')->nullable(); // mensual, anual, trimestral
            $table->string('estado')->default('activo'); // activo, vencido, renovado, cancelado
            $table->string('archivo')->nullable();
            $table->text('descripcion')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('renovaciones');
    }
};
