@echo off
echo 🧪 Testando build do Docker para Byte Bank API...

REM Limpar containers e imagens antigas
echo 🧹 Limpando containers e imagens antigas...
docker-compose down --remove-orphans
docker system prune -f

REM Fazer build da imagem
echo 🔨 Fazendo build da imagem...
docker-compose build --no-cache

REM Verificar se o build foi bem-sucedido
if %ERRORLEVEL% EQU 0 (
    echo ✅ Build realizado com sucesso!
    echo 🚀 Iniciando containers...
    docker-compose up -d
    
    REM Aguardar um pouco para os serviços iniciarem
    timeout /t 10 /nobreak >nul
    
    REM Verificar status dos containers
    echo 📊 Status dos containers:
    docker-compose ps
    
    echo 🌐 Testando API...
    curl -f http://localhost:8000/api/health || echo ❌ API não está respondendo
    
) else (
    echo ❌ Erro no build do Docker
    exit /b 1
) 