<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('marcas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::table('equipos', function (Blueprint $table) {
            $table->foreignId('tipo_id')->nullable()->after('tipo')->constrained('tipos')->nullOnDelete();
            $table->foreignId('marca_id')->nullable()->after('marca')->constrained('marcas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            $table->dropForeign(['tipo_id']);
            $table->dropForeign(['marca_id']);
            $table->dropColumn(['tipo_id', 'marca_id']);
        });
        Schema::dropIfExists('marcas');
        Schema::dropIfExists('tipos');
    }
};
