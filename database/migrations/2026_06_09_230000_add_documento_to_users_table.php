<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('documento', 20)->unique()->nullable()->after('name');
            $table->string('telefono', 20)->nullable()->after('email');
            $table->boolean('activo')->default(true)->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['documento']);
            $table->dropColumn(['documento', 'telefono', 'activo']);
        });
    }
};
