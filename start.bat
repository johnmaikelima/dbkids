@echo off
REM Script para iniciar o servidor DbKids usando Laragon PHP

echo.
echo ========================================
echo   DbKids - Servidor Local
echo ========================================
echo.

REM Usar o PHP do Laragon
set PHP_PATH=C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe

REM Verificar se PHP existe
if not exist "%PHP_PATH%" (
    echo [ERRO] PHP nao encontrado em: %PHP_PATH%
    echo.
    echo Verifique a versao do PHP instalada em C:\laragon\bin\php\
    echo.
    pause
    exit /b 1
)

echo Usando PHP de: %PHP_PATH%
echo.
"%PHP_PATH%" -v
echo.
echo Iniciando servidor em http://localhost:8000
echo.
echo Pressione CTRL+C para parar o servidor
echo.

REM Iniciar o servidor com router.php para servir arquivos estáticos
"%PHP_PATH%" -S localhost:8000 router.php

pause
