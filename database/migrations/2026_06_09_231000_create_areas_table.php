<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('puestos_trabajo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->text('descripcion')->nullable();
            $table->foreignId('area_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::table('equipos', function (Blueprint $table) {
            $table->foreignId('usuario_registra_id')->nullable()->after('observaciones')->constrained('users')->nullOnDelete();
            $table->foreignId('area_id')->nullable()->after('area')->constrained()->nullOnDelete();
            $table->foreignId('puesto_id')->nullable()->after('puesto_trabajo')->constrained('puestos_trabajo')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            $table->dropForeign(['usuario_registra_id']);
            $table->dropForeign(['area_id']);
            $table->dropForeign(['puesto_id']);
            $table->dropColumn(['usuario_registra_id', 'area_id', 'puesto_id']);
        });
        Schema::dropIfExists('puestos_trabajo');
        Schema::dropIfExists('areas');
    }
};
