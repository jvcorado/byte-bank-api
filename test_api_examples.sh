#!/bin/bash

# Exemplos de teste da API Byte Bank
# Execute o servidor primeiro: php artisan serve

BASE_URL="http://localhost:8000/api"

echo "=== TESTANDO API BYTE BANK ==="
echo

# 1. Registrar usuário
echo "1. Registrando usuário..."
curl -X POST "$BASE_URL/register" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "João Silva",
    "email": "joao@email.com",
    "password": "123456"
  }'
echo -e "\n"

# 2. Login
echo "2. Fazendo login..."
LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/login" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "joao@email.com",
    "password": "123456"
  }')

echo $LOGIN_RESPONSE
echo -e "\n"

# Extrair token (funciona no Linux/Mac - no Windows use jq ou outro método)
TOKEN=$(echo $LOGIN_RESPONSE | grep -o '"access_token":"[^"]*' | cut -d'"' -f4)
echo "Token extraído: $TOKEN"
echo

# 3. Verificar informações do usuário
echo "3. Verificando informações do usuário..."
curl -X GET "$BASE_URL/me" \
  -H "Authorization: Bearer $TOKEN"
echo -e "\n"

# 4. Listar contas
echo "4. Listando contas..."
curl -X GET "$BASE_URL/accounts" \
  -H "Authorization: Bearer $TOKEN"
echo -e "\n"

# 5. Criar nova conta
echo "5. Criando nova conta..."
curl -X POST "$BASE_URL/accounts" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Conta Poupança"
  }'
echo -e "\n"

# 6. Criar transação de entrada
echo "6. Criando transação de entrada..."
curl -X POST "$BASE_URL/accounts/1/transactions" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "type": "INCOME",
    "amount": 1000.00
  }'
echo -e "\n"

# 7. Criar transação de saída
echo "7. Criando transação de saída..."
curl -X POST "$BASE_URL/accounts/1/transactions" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "type": "EXPENSE",
    "amount": 250.50
  }'
echo -e "\n"

# 8. Ver detalhes da conta (com transações)
echo "8. Verificando detalhes da conta..."
curl -X GET "$BASE_URL/accounts/1" \
  -H "Authorization: Bearer $TOKEN"
echo -e "\n"

# 9. Logout
echo "9. Fazendo logout..."
curl -X POST "$BASE_URL/logout" \
  -H "Authorization: Bearer $TOKEN"
echo -e "\n"

echo "=== TESTES CONCLUÍDOS ===" 