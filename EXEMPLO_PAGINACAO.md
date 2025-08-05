# Exemplo de Uso da Paginação de Transações

## Endpoint
```
GET /api/accounts/{account_id}/transactions
```

## Parâmetros de Query
- `page` (opcional): Página atual (padrão: 1)
- `per_page` (opcional): Itens por página (padrão: 10)
- `type` (opcional): Filtrar por tipo (INCOME ou EXPENSE)
- `subtype` (opcional): Filtrar por subtipo
- `start_date` (opcional): Data inicial (YYYY-MM-DD)
- `end_date` (opcional): Data final (YYYY-MM-DD)

## Exemplos de Uso

### 1. Listar todas as transações (primeira página)
```bash
curl -X GET "http://localhost:8000/api/accounts/1/transactions" \
  -H "Authorization: Bearer {seu_token}" \
  -H "Accept: application/json"
```

### 2. Listar com paginação personalizada
```bash
curl -X GET "http://localhost:8000/api/accounts/1/transactions?page=2&per_page=5" \
  -H "Authorization: Bearer {seu_token}" \
  -H "Accept: application/json"
```

### 3. Filtrar por tipo
```bash
curl -X GET "http://localhost:8000/api/accounts/1/transactions?type=INCOME&page=1&per_page=10" \
  -H "Authorization: Bearer {seu_token}" \
  -H "Accept: application/json"
```

### 4. Filtrar por período
```bash
curl -X GET "http://localhost:8000/api/accounts/1/transactions?start_date=2024-01-01&end_date=2024-12-31" \
  -H "Authorization: Bearer {seu_token}" \
  -H "Accept: application/json"
```

## Resposta Esperada

```json
{
  "transactions": [
    {
      "id": 1,
      "type": "INCOME",
      "subtype": "DEPOSITO",
      "amount": 1000.00,
      "description": "Depósito em dinheiro",
      "document": "DOC-12345",
      "account_id": 1,
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  ],
  "pagination": {
    "currentPage": 1,
    "nextPage": 2,
    "prevPage": null,
    "totalPages": 5,
    "totalItems": 50
  }
}
```

## Uso no Frontend (JavaScript)

```javascript
// Função para buscar transações com paginação
const getTransactions = async (accountId, page = 1, perPage = 10, filters = {}) => {
  try {
    const params = new URLSearchParams({
      page,
      per_page: perPage,
      ...filters
    });
    
    const response = await api.get(`/accounts/${accountId}/transactions?${params}`);
    
    return {
      transactions: response.data.transactions.map(Transaction.fromJSON),
      pagination: {
        currentPage: response.data.pagination.currentPage,
        nextPage: response.data.pagination.nextPage,
        prevPage: response.data.pagination.prevPage,
        totalPages: response.data.pagination.totalPages,
        totalItems: response.data.pagination.totalItems,
      },
    };
  } catch (error) {
    throw error.response.data;
  }
};

// Exemplo de uso
const loadTransactions = async () => {
  const result = await getTransactions(1, 1, 10, { type: 'INCOME' });
  
  console.log('Transações:', result.transactions);
  console.log('Paginação:', result.pagination);
  
  // Verificar se há próxima página
  if (result.pagination.nextPage) {
    console.log('Há mais páginas disponíveis');
  }
};
```

## Componente React/Vue de Exemplo

```jsx
// React Component
const TransactionList = ({ accountId }) => {
  const [transactions, setTransactions] = useState([]);
  const [pagination, setPagination] = useState({});
  const [currentPage, setCurrentPage] = useState(1);
  const [loading, setLoading] = useState(false);

  const loadTransactions = async (page = 1) => {
    setLoading(true);
    try {
      const result = await getTransactions(accountId, page);
      setTransactions(result.transactions);
      setPagination(result.pagination);
      setCurrentPage(page);
    } catch (error) {
      console.error('Erro ao carregar transações:', error);
    } finally {
      setLoading(false);
    }
  };

  const nextPage = () => {
    if (pagination.nextPage) {
      loadTransactions(pagination.nextPage);
    }
  };

  const prevPage = () => {
    if (pagination.prevPage) {
      loadTransactions(pagination.prevPage);
    }
  };

  useEffect(() => {
    loadTransactions();
  }, [accountId]);

  return (
    <div>
      {loading ? (
        <p>Carregando...</p>
      ) : (
        <>
          <div className="transactions">
            {transactions.map(transaction => (
              <div key={transaction.id}>
                <h3>{transaction.description}</h3>
                <p>Valor: R$ {transaction.amount}</p>
                <p>Tipo: {transaction.type}</p>
              </div>
            ))}
          </div>
          
          <div className="pagination">
            <button 
              onClick={prevPage} 
              disabled={!pagination.prevPage}
            >
              Anterior
            </button>
            
            <span>
              Página {pagination.currentPage} de {pagination.totalPages}
            </span>
            
            <button 
              onClick={nextPage} 
              disabled={!pagination.nextPage}
            >
              Próxima
            </button>
          </div>
        </>
      )}
    </div>
  );
};
```

## Testando com Dados Reais

Para testar a paginação, você pode:

1. **Criar um usuário e conta:**
```bash
curl -X POST "http://localhost:8000/api/register" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Teste Usuário",
    "email": "teste@example.com",
    "password": "123456"
  }'
```

2. **Fazer login para obter o token:**
```bash
curl -X POST "http://localhost:8000/api/login" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "teste@example.com",
    "password": "123456"
  }'
```

3. **Criar algumas transações:**
```bash
curl -X POST "http://localhost:8000/api/accounts/1/transactions" \
  -H "Authorization: Bearer {seu_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "type": "INCOME",
    "subtype": "DEPOSITO",
    "amount": 1000.00,
    "description": "Salário",
    "document": "DOC-001"
  }'
```

4. **Testar a paginação:**
```bash
curl -X GET "http://localhost:8000/api/accounts/1/transactions?page=1&per_page=5" \
  -H "Authorization: Bearer {seu_token}" \
  -H "Accept: application/json"
``` 