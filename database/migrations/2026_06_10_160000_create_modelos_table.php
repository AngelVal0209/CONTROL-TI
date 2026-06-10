<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modelos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::table('equipos', function (Blueprint $table) {
            $table->foreignId('modelo_id')->nullable()->after('marca_id')->constrained('modelos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            $table->dropForeign(['modelo_id']);
            $table->dropColumn('modelo_id');
        });
        Schema::dropIfExists('modelos');
    }
};
