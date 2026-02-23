#!/bin/bash

# Script para iniciar o servidor PHP Built-in (Linux/Mac)

echo ""
echo "========================================"
echo "   DbKids - Servidor Local"
echo "========================================"
echo ""

# Verificar se PHP está instalado
if ! command -v php &> /dev/null; then
    echo "[ERRO] PHP não encontrado"
    echo ""
    echo "Instale PHP primeiro:"
    echo "  Ubuntu/Debian: sudo apt-get install php-cli"
    echo "  Mac: brew install php"
    exit 1
fi

# Mostrar versão do PHP
php -v | head -n 1

echo ""
echo "Iniciando servidor em http://localhost:8000"
echo ""
echo "Pressione CTRL+C para parar o servidor"
echo ""

# Iniciar o servidor
php -S localhost:8000
