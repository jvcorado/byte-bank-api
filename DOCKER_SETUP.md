# Configuração Docker para Byte Bank API

Este documento descreve como configurar e executar a API Byte Bank usando Docker.

## 📋 Pré-requisitos

- Docker Desktop instalado e rodando
- Docker Compose instalado
- Porta 8000 disponível para a API
- Porta 3306 disponível para o MySQL
- Porta 8080 disponível para o phpMyAdmin

## 🚀 Início Rápido

### 1. Build e Execução

```bash
# Fazer build das imagens
docker-compose build

# Iniciar todos os serviços
docker-compose up -d

# Verificar status dos containers
docker-compose ps
```

### 2. Teste Automatizado (Windows)

```cmd
# Executar script de teste
test-docker-build.bat
```

### 3. Teste Automatizado (Linux/Mac)

```bash
# Executar script de teste
./test-docker-build.sh
```

## 🔧 Serviços Disponíveis

### API Laravel
- **URL**: http://localhost:8000
- **Container**: byte-bank-api
- **Porta**: 8000

### MySQL Database
- **Host**: localhost
- **Porta**: 3306
- **Database**: byte_bank
- **Usuário**: byte_bank_user
- **Senha**: byte_bank_password
- **Container**: byte-bank-mysql

### phpMyAdmin
- **URL**: http://localhost:8080
- **Container**: byte-bank-phpmyadmin
- **Porta**: 8080

## 📁 Estrutura de Arquivos Docker

```
byte-bank-api/
├── Dockerfile                 # Configuração da imagem da API
├── docker-compose.yml         # Orquestração dos serviços
├── .dockerignore             # Arquivos ignorados no build
├── docker/
│   ├── nginx.conf            # Configuração do Nginx
│   ├── default.conf          # Configuração do site Nginx
│   └── supervisord.conf      # Configuração do Supervisor
└── test-docker-build.*       # Scripts de teste
```

## 🔍 Solução de Problemas

### Erro: "composer install" não encontrado
**Problema**: O Composer não estava instalado na imagem base.
**Solução**: Adicionada instalação do Composer via multi-stage build.

### Erro: Dependências Node.js não encontradas
**Problema**: Tentativa de instalar apenas dependências de produção.
**Solução**: Instalação de todas as dependências (incluindo devDependencies) para build.

### Erro: Arquivos não copiados
**Problema**: .dockerignore excluindo arquivos necessários.
**Solução**: Removidas exclusões de node_modules e vendor do .dockerignore.

## 🛠️ Comandos Úteis

```bash
# Ver logs da API
docker-compose logs app

# Ver logs do MySQL
docker-compose logs mysql

# Acessar container da API
docker-compose exec app sh

# Executar comandos Laravel
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed

# Parar todos os serviços
docker-compose down

# Parar e remover volumes
docker-compose down -v

# Rebuild sem cache
docker-compose build --no-cache
```

## 🔒 Variáveis de Ambiente

As seguintes variáveis são configuradas automaticamente:

```env
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=byte_bank
DB_USERNAME=byte_bank_user
DB_PASSWORD=byte_bank_password
```

## 📊 Monitoramento

### Verificar Status dos Containers
```bash
docker-compose ps
```

### Verificar Uso de Recursos
```bash
docker stats
```

### Verificar Logs em Tempo Real
```bash
docker-compose logs -f
```

## 🧹 Limpeza

Para limpar completamente o ambiente Docker:

```bash
# Parar e remover containers
docker-compose down

# Remover imagens
docker rmi byte-bank-api_app

# Remover volumes
docker volume rm byte-bank-api_mysql_data

# Limpeza geral do Docker
docker system prune -a
```

## 📝 Notas Importantes

1. **Primeira execução**: O build pode demorar alguns minutos na primeira vez
2. **Permissões**: Os arquivos de storage e cache são configurados automaticamente
3. **Banco de dados**: O MySQL é inicializado automaticamente com as credenciais configuradas
4. **Assets**: Os assets do frontend são compilados durante o build da imagem

## 🆘 Suporte

Se encontrar problemas:

1. Verifique se o Docker Desktop está rodando
2. Execute `docker-compose logs` para ver logs detalhados
3. Use `docker-compose build --no-cache` para rebuild completo
4. Verifique se as portas necessárias estão disponíveis 