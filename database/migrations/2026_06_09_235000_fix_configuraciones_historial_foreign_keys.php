<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configuraciones_historial', function (Blueprint $table) {
            $table->dropForeign(['configuracion_id']);
            $table->dropForeign(['usuario_id']);
            $table->foreign('configuracion_id')->references('id')->on('configuraciones')->cascadeOnDelete();
            $table->foreign('usuario_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('configuraciones_historial', function (Blueprint $table) {
            $table->dropForeign(['configuracion_id']);
            $table->dropForeign(['usuario_id']);
            $table->foreign('configuracion_id')->references('id')->on('configuraciones')->cascadeOnDelete();
            $table->foreign('usuario_id')->references('id')->on('users')->nullOnDelete();
        });
    }
};
