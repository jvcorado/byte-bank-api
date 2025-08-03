# Configuração Docker - Byte Bank API

## Pré-requisitos
- Docker
- Docker Compose

## Configuração Inicial

1. **Copie o arquivo de ambiente:**
```bash
cp .env.example .env
```

2. **Configure as variáveis de ambiente no arquivo `.env`:**
```env
APP_NAME="Byte Bank API"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=byte_bank
DB_USERNAME=byte_bank_user
DB_PASSWORD=byte_bank_password
```

## Executando o Projeto

1. **Construir e iniciar os containers:**
```bash
docker-compose up -d --build
```

2. **Gerar chave da aplicação:**
```bash
docker-compose exec app php artisan key:generate
```

3. **Executar migrações:**
```bash
docker-compose exec app php artisan migrate
```

4. **Executar seeders (opcional):**
```bash
docker-compose exec app php artisan db:seed
```

## Acessos

- **API Laravel:** http://localhost:8000
- **PHPMyAdmin:** http://localhost:8080
  - Usuário: `byte_bank_user`
  - Senha: `byte_bank_password`

## Comandos Úteis

```bash
# Parar containers
docker-compose down

# Ver logs
docker-compose logs -f app

# Acessar container da aplicação
docker-compose exec app sh

# Executar testes
docker-compose exec app php artisan test

# Limpar cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
```

## Estrutura de Arquivos Docker

- `Dockerfile` - Configuração da imagem da aplicação
- `docker-compose.yml` - Orquestração dos serviços
- `docker/nginx.conf` - Configuração do Nginx
- `docker/default.conf` - Virtual host do Nginx
- `docker/supervisord.conf` - Gerenciamento de processos
- `.dockerignore` - Arquivos excluídos do build

## Troubleshooting

### Problemas com permissões
```bash
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache
```

### Rebuild completo
```bash
docker-compose down -v
docker-compose up -d --build
``` 