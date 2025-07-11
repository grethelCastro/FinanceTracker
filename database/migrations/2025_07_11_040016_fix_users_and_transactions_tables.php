<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Verificar y agregar columnas faltantes a users
        if (!Schema::hasColumn('users', 'email_verified_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            });
        }

        if (!Schema::hasColumn('users', 'currency')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('currency', 3)->default('NIO')->after('password');
            });
        }

        // Verificar y agregar account_id a transactions si no existe
        if (!Schema::hasColumn('transactions', 'account_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->foreignId('account_id')->after('user_id')
                      ->constrained()->cascadeOnDelete();
            });
        }
    }

    public function down()
    {
        // No revertir para evitar problemas
    }
};