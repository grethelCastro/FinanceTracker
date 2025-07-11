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
        // Verificar si la columna ya existe antes de agregarla
        if (!Schema::hasColumn('users', 'currency')) {
            $table->string('currency', 3)->default('NIO');
        }
        
        // Agrega aquí otros campos de perfil que necesites
        // Ejemplo:
        // if (!Schema::hasColumn('users', 'phone')) {
        //     $table->string('phone', 20)->nullable();
        // }
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        // Solo elimina las columnas si existen
        if (Schema::hasColumn('users', 'currency')) {
            $table->dropColumn('currency');
        }
        // Agrega aquí la reversión de otros campos
    });
}
};
