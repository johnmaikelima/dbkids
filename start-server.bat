@echo off
REM Script para iniciar o servidor PHP Built-in

echo.
echo ========================================
echo   DbKids - Servidor Local
echo ========================================
echo.

REM Verificar se PHP está instalado
where php >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERRO] PHP não encontrado no PATH
    echo.
    echo Solução 1: Adicionar PHP ao PATH do Windows
    echo Solução 2: Usar o caminho completo do PHP
    echo.
    pause
    exit /b 1
)

REM Obter a versão do PHP
for /f "tokens=*" %%i in ('php -v') do (
    echo %%i
    goto :found_php
)

:found_php
echo.
echo Iniciando servidor em http://localhost:8000
echo.
echo Pressione CTRL+C para parar o servidor
echo.

REM Iniciar o servidor
php -S localhost:8000

pause
