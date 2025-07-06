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
        if (!Schema::hasColumn('users', 'currency')) {
            $table->string('currency', 3)->default('NIO');
        }

        if (!Schema::hasColumn('users', 'dark_mode')) {
            $table->boolean('dark_mode')->default(false);
        }
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        // Opcional: Eliminar las columnas al revertir
        // $table->dropColumn(['currency', 'dark_mode']);
    });
}
};
