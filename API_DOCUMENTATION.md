# API Documentation - Byte Bank

## Base URL
```
http://localhost:8000/api
```

## Autenticação
A API utiliza Laravel Sanctum para autenticação. Após o login, você receberá um token Bearer que deve ser incluído no header `Authorization` de todas as requisições protegidas.

```
Authorization: Bearer {seu_token_aqui}
```

---

## 🔐 Rotas de Autenticação

### 1. Registrar Usuário
**POST** `/register`

**Parâmetros:**
```json
{
  "name": "string (obrigatório, máx 255 caracteres)",
  "email": "string (obrigatório, email válido, único)",
  "password": "string (obrigatório, mín 6 caracteres)"
}
```

**Resposta de Sucesso (201):**
```json
{
  "user": {
    "id": 1,
    "name": "João Silva",
    "email": "joao@email.com",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  },
  "account": {
    "id": 1,
    "name": "joão silva",
    "user_id": 1,
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  },
  "message": "Usuário e conta criados com sucesso!"
}
```

### 2. Login
**POST** `/login`

**Parâmetros:**
```json
{
  "email": "string (obrigatório)",
  "password": "string (obrigatório)"
}
```

**Resposta de Sucesso (200):**
```json
{
  "user": {
    "id": 1,
    "name": "João Silva",
    "email": "joao@email.com",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  },
  "access_token": "1|token_string_aqui",
  "token_type": "Bearer",
  "message": "Login realizado com sucesso!"
}
```

**Resposta de Erro (401):**
```json
{
  "message": "Credenciais inválidas"
}
```

### 3. Logout
**POST** `/logout`
*Requer autenticação*

**Resposta de Sucesso (200):**
```json
{
  "message": "Logout realizado com sucesso!"
}
```

### 4. Dados do Usuário Logado
**GET** `/me`
*Requer autenticação*

**Resposta de Sucesso (200):**
```json
{
  "user": {
    "id": 1,
    "name": "João Silva",
    "email": "joao@email.com",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  },
  "accounts": [
    {
      "id": 1,
      "name": "joão silva",
      "user_id": 1,
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  ]
}
```

---

## 💰 Rotas de Contas

### 1. Listar Contas
**GET** `/accounts`
*Requer autenticação*

**Resposta de Sucesso (200):**
```json
[
  {
    "id": 1,
    "name": "conta principal",
    "balance": 1500.50,
    "transactions_count": 10,
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
]
```

### 2. Criar Conta
**POST** `/accounts`
*Requer autenticação*

**Parâmetros:**
```json
{
  "name": "string (obrigatório)"
}
```

**Resposta de Sucesso (201):**
```json
{
  "id": 2,
  "name": "nova conta",
  "balance": 0,
  "created_at": "2024-01-01T00:00:00.000000Z",
  "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

### 3. Visualizar Conta Específica
**GET** `/accounts/{id}`
*Requer autenticação*

**Resposta de Sucesso (200):**
```json
{
  "id": 1,
  "name": "conta principal",
  "balance": 1500.50,
  "transactions": [
    {
      "id": 1,
      "type": "INCOME",
      "amount": 2000.00,
      "account_id": 1,
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    },
    {
      "id": 2,
      "type": "EXPENSE",
      "amount": 500.00,
      "account_id": 1,
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  ],
  "created_at": "2024-01-01T00:00:00.000000Z",
  "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

### 4. Atualizar Conta
**PUT** `/accounts/{id}`
*Requer autenticação*

**Parâmetros:**
```json
{
  "name": "string (obrigatório)"
}
```

**Resposta de Sucesso (200):**
```json
{
  "id": 1,
  "name": "nome atualizado",
  "balance": 1500.50,
  "created_at": "2024-01-01T00:00:00.000000Z",
  "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

### 5. Deletar Conta
**DELETE** `/accounts/{id}`
*Requer autenticação*

**Resposta de Sucesso (204):** Sem conteúdo

---

## 💸 Rotas de Transações

### 1. Criar Transação
**POST** `/accounts/{account_id}/transactions`
*Requer autenticação*

**Parâmetros:**
```json
{
  "type": "string (obrigatório, valores: INCOME ou EXPENSE)",
  "subtype": "string (opcional, valores: DOC_TED, BOLETO, CAMBIO, EMPRESTIMO, DEPOSITO, TRANSFERENCIA)",
  "amount": "number (obrigatório, mínimo 0.01)"
}
```

**Resposta de Sucesso (201):**
```json
{
  "id": 3,
  "type": "INCOME",
  "subtype": "DEPOSITO",
  "amount": 1000.00,
  "account_id": 1,
  "created_at": "2024-01-01T00:00:00.000000Z",
  "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

**Resposta de Erro (422):**
```json
{
  "errors": {
    "amount": ["Saldo insuficiente para esta operação"]
  }
}
```

### 2. Atualizar Transação
**PUT** `/transactions/{id}`
*Requer autenticação*

**Parâmetros:**
```json
{
  "type": "string (obrigatório, valores: INCOME ou EXPENSE)",
  "subtype": "string (opcional, valores: DOC_TED, BOLETO, CAMBIO, EMPRESTIMO, DEPOSITO, TRANSFERENCIA)",
  "amount": "number (obrigatório, mínimo 0.01)"
}
```

**Resposta de Sucesso (200):**
```json
{
  "id": 3,
  "type": "EXPENSE",
  "subtype": "BOLETO",
  "amount": 800.00,
  "account_id": 1,
  "created_at": "2024-01-01T00:00:00.000000Z",
  "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

### 3. Deletar Transação
**DELETE** `/transactions/{id}`
*Requer autenticação*

**Resposta de Sucesso (204):** Sem conteúdo

---

## 📝 Códigos de Status HTTP

- **200** - OK: Requisição bem-sucedida
- **201** - Created: Recurso criado com sucesso
- **204** - No Content: Recurso deletado com sucesso
- **401** - Unauthorized: Token inválido ou ausente
- **404** - Not Found: Recurso não encontrado
- **422** - Unprocessable Entity: Dados de validação inválidos
- **500** - Internal Server Error: Erro interno do servidor

---

## 🔧 Exemplos de Uso com JavaScript

### Configuração do Axios
```javascript
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  }
});

// Interceptor para adicionar token automaticamente
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});
```

### Exemplos de Requisições

#### Login
```javascript
const login = async (email, password) => {
  try {
    const response = await api.post('/login', { email, password });
    const { access_token } = response.data;
    localStorage.setItem('auth_token', access_token);
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
};
```

#### Buscar Contas
```javascript
const getAccounts = async () => {
  try {
    const response = await api.get('/accounts');
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
};
```

#### Criar Transação
```javascript
const createTransaction = async (accountId, type, subtype, amount) => {
  try {
    const response = await api.post(`/accounts/${accountId}/transactions`, {
      type,
      subtype,
      amount
    });
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
};
```

---

## 🚀 Fluxo de Integração Recomendado

1. **Autenticação:**
   - Implementar tela de login/registro
   - Armazenar token no localStorage/sessionStorage
   - Configurar interceptors para incluir token automaticamente

2. **Dashboard:**
   - Buscar dados do usuário com `/me`
   - Listar contas com `/accounts`
   - Exibir saldo total e resumo

3. **Gestão de Contas:**
   - Criar, editar e deletar contas
   - Visualizar detalhes de cada conta

4. **Transações:**
   - Adicionar receitas e despesas
   - Editar e deletar transações
   - Filtrar por tipo ou período

5. **Tratamento de Erros:**
   - Implementar interceptors para capturar erros 401 (token expirado)
   - Exibir mensagens de erro amigáveis
   - Redirecionar para login quando necessário

---

## 📱 Estrutura de Estados Recomendada (React/Vue)

```javascript
// Estado global da aplicação
const appState = {
  user: null,
  accounts: [],
  selectedAccount: null,
  transactions: [],
  loading: false,
  error: null
};

// Ações principais
const actions = {
  login,
  logout,
  fetchAccounts,
  createAccount,
  selectAccount,
  fetchTransactions,
  createTransaction,
  updateTransaction,
  deleteTransaction
};
```

Esta documentação fornece tudo o que você precisa para integrar seu frontend com a API do Byte Bank. Todas as rotas estão funcionais e seguem padrões REST com autenticação via Bearer Token. 