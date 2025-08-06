<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Enums\TransactionSubtype;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionSubtypeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa se todos os subtypes estão disponíveis no enum
     */
    public function test_all_subtypes_are_available(): void
    {
        $expectedSubtypes = [
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
        ];

        $availableSubtypes = TransactionSubtype::values();

        foreach ($expectedSubtypes as $subtype) {
            $this->assertContains($subtype, $availableSubtypes);
        }

        $this->assertCount(11, $availableSubtypes);
    }

    /**
     * Testa se os novos subtypes podem ser usados em uma transação
     */
    public function test_new_subtypes_can_be_used_in_transaction(): void
    {
        $newSubtypes = [
            'RESTAURANTE',
            'TRANSPORTE',
            'SALARIO',
            'REEMBOLSO',
            'CASHBACK'
        ];

        foreach ($newSubtypes as $subtype) {
            $this->assertTrue(in_array($subtype, TransactionSubtype::values()));
        }
    }
}
