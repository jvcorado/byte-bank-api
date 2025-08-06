# API de Busca por Subtipo - Documentação

## Visão Geral

A API agora suporta busca de transações por subtipo a partir do 4º caractere. Esta funcionalidade permite buscar transações que contenham um termo específico no campo `subtype`.

## Endpoints Disponíveis

### 1. Busca via Parâmetro `search` (Método Index)

**Endpoint:** `GET /api/accounts/{accountId}/transactions`

**Parâmetros:**
- `search` (string, mínimo 4 caracteres): Termo para buscar no subtipo
- Outros parâmetros existentes: `type`, `subtype`, `start_date`, `end_date`, `page`, `per_page`

**Exemplo:**
```bash
GET /api/accounts/1/transactions?search=depo
```

**Resposta:**
```json
{
  "transactions": [
    {
      "id": 1,
      "type": "INCOME",
      "subtype": "DEPOSITO",
      "amount": 100.00,
      "description": "Depósito inicial",
      "created_at": "2025-08-05T21:30:00.000000Z"
    }
  ],
  "pagination": {
    "currentPage": 1,
    "nextPage": null,
    "prevPage": null,
    "totalPages": 1,
    "totalItems": 1
  }
}
```

### 2. Busca Dedicada (Método Search)

**Endpoint:** `GET /api/accounts/{accountId}/transactions/search`

**Parâmetros:**
- `q` (string, obrigatório, mínimo 4 caracteres): Termo para buscar no subtipo

**Exemplo:**
```bash
GET /api/accounts/1/transactions/search?q=depo
```

**Resposta:**
```json
{
  "transactions": [
    {
      "id": 1,
      "type": "INCOME",
      "subtype": "DEPOSITO",
      "amount": 100.00,
      "description": "Depósito inicial",
      "created_at": "2025-08-05T21:30:00.000000Z"
    }
  ],
  "searchTerm": "DEPO",
  "total": 1
}
```

## Subtipos Disponíveis

Os seguintes subtipos estão disponíveis para busca:

- `DOC_TED`
- `BOLETO`
- `CAMBIO`
- `EMPRESTIMO`
- `DEPOSITO`
- `TRANSFERENCIA`

## Exemplos de Uso

### Frontend (TypeScript)

```typescript
import { TransactionService } from '@/services/TransactionService';

// Exemplo 1: Usando o método searchBySubtype
try {
  const transactions = await TransactionService.searchBySubtype(1, 'depo');
  console.log('Transações encontradas:', transactions);
} catch (error) {
  console.error('Erro na busca:', error.message);
}

// Exemplo 2: Usando o método index com parâmetro search
const response = await fetch('/api/accounts/1/transactions?search=depo', {
  headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json'
  }
});
const data = await response.json();
```

### Exemplos de Busca

| Termo Digitado | Subtipos Encontrados |
|----------------|---------------------|
| `depo` | `DEPOSITO` |
| `trans` | `TRANSFERENCIA` |
| `bole` | `BOLETO` |
| `empre` | `EMPRESTIMO` |
| `camb` | `CAMBIO` |
| `doc` | `DOC_TED` |

## Validações

1. **Mínimo de caracteres**: O termo de busca deve ter pelo menos 4 caracteres
2. **Case insensitive**: A busca é case insensitive (não diferencia maiúsculas/minúsculas)
3. **Busca parcial**: Usa `LIKE` com `%` para buscar correspondências parciais

## Códigos de Erro

- `422`: Termo de busca inválido (menos de 4 caracteres)
- `404`: Conta não encontrada
- `401`: Não autenticado

## Notas Importantes

1. A busca é feita apenas no campo `subtype`
2. O termo é convertido para maiúsculas antes da busca
3. A busca é feita usando `LIKE` com `%` no início e fim
4. Os resultados são ordenados por `created_at` em ordem decrescente
5. A autenticação é obrigatória para ambos os endpoints