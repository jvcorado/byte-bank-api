#!/bin/bash

echo "🧪 Testando build do Docker para Byte Bank API..."

# Limpar containers e imagens antigas
echo "🧹 Limpando containers e imagens antigas..."
docker-compose down --remove-orphans
docker system prune -f

# Fazer build da imagem
echo "🔨 Fazendo build da imagem..."
docker-compose build --no-cache

# Verificar se o build foi bem-sucedido
if [ $? -eq 0 ]; then
    echo "✅ Build realizado com sucesso!"
    echo "🚀 Iniciando containers..."
    docker-compose up -d
    
    # Aguardar um pouco para os serviços iniciarem
    sleep 10
    
    # Verificar status dos containers
    echo "📊 Status dos containers:"
    docker-compose ps
    
    echo "🌐 Testando API..."
    curl -f http://localhost:8000/api/health || echo "❌ API não está respondendo"
    
else
    echo "❌ Erro no build do Docker"
    exit 1
fi 