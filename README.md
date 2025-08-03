# 🏦 ByteBank API - Backend Laravel

## 📋 Descrição

A ByteBank API é o backend robusto e escalável do sistema bancário, desenvolvido em Laravel 11 com autenticação JWT, oferecendo endpoints RESTful para todos os micro-frontends.

## 🏗️ Arquitetura

### Tecnologias
- **Laravel 11** - Framework PHP moderno
- **MySQL 8.0** - Banco de dados relacional
- **Laravel Sanctum** - Autenticação JWT
- **Eloquent ORM** - Mapeamento objeto-relacional
- **Laravel Migrations** - Controle de versão do banco
- **Laravel Seeders** - Dados iniciais

### Estrutura do Projeto
```
app/
├── Http/
│   └── Controllers/
│       ├── AuthController.php      # Autenticação
│       ├── AccountController.php   # Gestão de contas
│       └── TransactionController.php # Gestão de transações
├── Models/
│   ├── User.php                   # Modelo de usuário
│   ├── Account.php                # Modelo de conta
│   └── Transaction.php            # Modelo de transação
├── Providers/
│   ├── AppServiceProvider.php     # Provider principal
│   └── RouteServiceProvider.php   # Provider de rotas
├── database/
│   ├── migrations/                # Migrações do banco
│   ├── seeders/                   # Seeders de dados
│   └── factories/                 # Factories para testes
└── routes/
    └── api.php                    # Rotas da API
```

## 🚀 Instalação

### Pré-requisitos
- PHP 8.1+
- Composer 2.0+
- MySQL 8.0+
- Node.js (para assets)

### Configuração

```bash
# Clone o repositório
git clone <url-do-repositorio>
cd byte-bank-api

# Instalar dependências
composer install

# Copiar arquivo de ambiente
cp .env.example .env

# Configurar variáveis de ambiente
# Edite o arquivo .env com suas configurações

# Gerar chave da aplicação
php artisan key:generate

# Configurar banco de dados
# Edite as configurações de DB no .env

# Executar migrações
php artisan migrate

# Executar seeders (opcional)
php artisan db:seed

# Iniciar servidor
php artisan serve
```

### Variáveis de Ambiente

```env
APP_NAME=ByteBank
APP_ENV=local
APP_KEY=base64:sua-chave-aqui
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bytebank
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

CORS_ALLOWED_ORIGINS=http://localhost:3000,http://localhost:3001,http://localhost:3002
```

## 🔐 Autenticação

### Sistema JWT com Sanctum

```php
// Configuração no config/sanctum.php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
    env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : ''
))),
```

### Endpoints de Autenticação

#### Login
```http
POST /api/login
Content-Type: application/json

{
    "email": "usuario@exemplo.com",
    "password": "senha123"
}
```

**Resposta:**
```json
{
    "user": {
        "id": 1,
        "name": "João Silva",
        "email": "usuario@exemplo.com",
        "created_at": "2024-01-01T00:00:00.000000Z"
    },
    "access_token": "1|token-jwt-aqui"
}
```

#### Registro
```http
POST /api/register
Content-Type: application/json

{
    "name": "João Silva",
    "email": "joao@exemplo.com",
    "password": "senha123"
}
```

**Resposta:**
```json
{
    "user": {
        "id": 1,
        "name": "João Silva",
        "email": "joao@exemplo.com",
        "created_at": "2024-01-01T00:00:00.000000Z"
    },
    "access_token": "1|token-jwt-aqui"
}
```

#### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

#### Dados do Usuário
```http
GET /api/me
Authorization: Bearer {token}
```

**Resposta:**
```json
{
    "user": {
        "id": 1,
        "name": "João Silva",
        "email": "usuario@exemplo.com",
        "created_at": "2024-01-01T00:00:00.000000Z"
    },
    "accounts": [
        {
            "id": 1,
            "name": "Conta Principal",
            "balance": 1000.00,
            "user_id": 1
        }
    ]
}
```

## 💰 Gestão de Contas

### Endpoints de Contas

#### Listar Contas
```http
GET /api/accounts
Authorization: Bearer {token}
```

#### Criar Conta
```http
POST /api/accounts
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Conta Poupança"
}
```

## 💸 Gestão de Transações

### Tipos de Transação
- **deposit** - Depósito
- **withdrawal** - Saque
- **transfer** - Transferência

### Endpoints de Transações

#### Listar Transações
```http
GET /api/transactions?page=1&per_page=10&type=deposit&start_date=2024-01-01&end_date=2024-12-31
Authorization: Bearer {token}
```

**Parâmetros de Filtro:**
- `page` - Página atual
- `per_page` - Itens por página
- `type` - Tipo de transação
- `start_date` - Data inicial
- `end_date` - Data final
- `min_amount` - Valor mínimo
- `max_amount` - Valor máximo

#### Criar Transação
```http
POST /api/transactions
Authorization: Bearer {token}
Content-Type: application/json

{
    "type": "deposit",
    "amount": 100.00,
    "description": "Depósito inicial",
    "account_id": 1
}
```

#### Atualizar Transação
```http
PUT /api/transactions/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "description": "Descrição atualizada"
}
```

#### Excluir Transação
```http
DELETE /api/transactions/{id}
Authorization: Bearer {token}
```

## 🗄️ Banco de Dados

### Migrações Principais

#### Users Table
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->timestamps();
});
```

#### Accounts Table
```php
Schema::create('accounts', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->decimal('balance', 10, 2)->default(0);
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->timestamps();
});
```

#### Transactions Table
```php
Schema::create('transactions', function (Blueprint $table) {
    $table->id();
    $table->enum('type', ['deposit', 'withdrawal', 'transfer']);
    $table->decimal('amount', 10, 2);
    $table->text('description')->nullable();
    $table->foreignId('account_id')->constrained()->onDelete('cascade');
    $table->timestamps();
});
```

### Relacionamentos

```php
// User Model
public function accounts()
{
    return $this->hasMany(Account::class);
}

// Account Model
public function user()
{
    return $this->belongsTo(User::class);
}

public function transactions()
{
    return $this->hasMany(Transaction::class);
}

// Transaction Model
public function account()
{
    return $this->belongsTo(Account::class);
}
```

## 🔒 Segurança

### Middleware de Autenticação
```php
// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('accounts', AccountController::class);
    Route::apiResource('transactions', TransactionController::class);
});
```

### Validação de Dados
```php
// Request Validation
public function rules()
{
    return [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
    ];
}
```

### CORS Configuration
```php
// config/cors.php
return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', '*')),
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
```

## 🧪 Testes

### Executar Testes
```bash
# Todos os testes
php artisan test

# Testes específicos
php artisan test --filter=AuthControllerTest

# Testes com coverage
php artisan test --coverage
```

### Exemplo de Teste
```php
public function test_user_can_login()
{
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password')
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'password'
    ]);

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'user' => ['id', 'name', 'email'],
                 'access_token'
             ]);
}
```

## 📊 Logs e Monitoramento

### Logs Estruturados
```php
Log::info('Transação criada', [
    'user_id' => $user->id,
    'transaction_id' => $transaction->id,
    'amount' => $transaction->amount,
    'type' => $transaction->type
]);
```

### Logs de Auditoria
```php
// Log de tentativas de login
Log::warning('Tentativa de login falhou', [
    'email' => $request->email,
    'ip' => $request->ip(),
    'user_agent' => $request->userAgent()
]);
```

## 🚀 Deploy

### Heroku
```bash
# Criar app no Heroku
heroku create bytebank-api

# Configurar variáveis de ambiente
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false
heroku config:set DB_CONNECTION=mysql
heroku config:set DB_HOST=seu-host
heroku config:set DB_DATABASE=seu-database
heroku config:set DB_USERNAME=seu-username
heroku config:set DB_PASSWORD=sua-senha

# Deploy
git push heroku main

# Executar migrações
heroku run php artisan migrate
```

### Docker
```dockerfile
FROM php:8.2-fpm

# Instalar dependências
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Instalar extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www

# Copiar arquivos do projeto
COPY . .

# Instalar dependências
RUN composer install --no-dev --optimize-autoloader

# Configurar permissões
RUN chown -R www-data:www-data /var/www
```

## 📈 Performance

### Otimizações Implementadas
- **Eager Loading** para evitar N+1 queries
- **Indexes** no banco de dados
- **Cache** de consultas frequentes
- **Rate Limiting** para proteção
- **Compression** de respostas

### Exemplo de Eager Loading
```php
// Evitar N+1 queries
$user = User::with(['accounts.transactions'])->find($id);

// Consulta otimizada
$transactions = Transaction::with('account')
    ->where('account_id', $accountId)
    ->orderBy('created_at', 'desc')
    ->paginate(10);
```

## 🔧 Comandos Artisan

### Comandos Úteis
```bash
# Limpar cache
php artisan cache:clear

# Limpar configurações
php artisan config:clear

# Limpar rotas
php artisan route:clear

# Listar rotas
php artisan route:list

# Criar usuário via CLI
php artisan make:user

# Backup do banco
php artisan backup:run
```

## 📚 Documentação da API

### Swagger/OpenAPI
A documentação completa da API está disponível em:
```
http://localhost:8000/api/documentation
```

### Postman Collection
Importe a collection do Postman para testar todos os endpoints:
```
docs/postman/ByteBank_API.postman_collection.json
```

---

**ByteBank API** - Backend robusto e escalável 🚀
