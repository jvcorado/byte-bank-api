<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Para SQLite, precisamos recriar a tabela com os novos valores do enum
        Schema::table('transactions', function (Blueprint $table) {
            // Primeiro, vamos dropar a coluna subtype
            $table->dropColumn('subtype');
        });

        Schema::table('transactions', function (Blueprint $table) {
            // Agora recriar com todos os valores
            $table->enum('subtype', [
                'DOC_TED',
                'BOLETO',
                'CAMBIO',
                'EMPRESTIMO',
                'DEPOSITO',
                'TRANSFERENCIA',
                'RESTAURANTE',
                'TRANSPORTE',
                'SALARIO',
                'REEMBOLSO',
                'CASHBACK'
            ])->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('subtype');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('subtype', [
                'DOC_TED',
                'BOLETO',
                'CAMBIO',
                'EMPRESTIMO',
                'DEPOSITO',
                'TRANSFERENCIA'
            ])->nullable()->after('type');
        });
    }
};
