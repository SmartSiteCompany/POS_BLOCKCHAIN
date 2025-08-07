<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('users', function (Blueprint $table) {
        // Agregar las columnas 'kind' y 'status' con valores por defecto
        $table->integer('kind')->default(1); // Tipo de usuario, 1 por defecto
        $table->integer('status')->default(1); // Estado, 1 por defecto
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        // Eliminar las columnas si se revierte la migración
        $table->dropColumn(['kind', 'status']);
    });
}

};
