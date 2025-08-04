<?php

namespace App\Enums;

enum TransactionSubtype: string
{
    case DOC_TED = 'DOC_TED';
    case BOLETO = 'BOLETO';
    case CAMBIO = 'CAMBIO';
    case EMPRESTIMO = 'EMPRESTIMO';
    case DEPOSITO = 'DEPOSITO';
    case TRANSFERENCIA = 'TRANSFERENCIA';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
