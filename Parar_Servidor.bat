@echo off
title Encerrar Servidor FaPet
color 0C
cd /d "%~dp0"

echo =============================================================
echo               ENCERRANDO SERVIDOR INVISIVEL
echo =============================================================
echo.
echo [INFO] Procurando e derrubando processos na porta 8000...

for /f "tokens=5" %%a in ('netstat -aon ^| findstr :8000 ^| findstr LISTENING') do (
    taskkill /F /PID %%a >nul 2>&1
)

echo [OK] Servidor encerrado com sucesso e porta 8000 liberada!
ping 127.0.0.1 -n 3 >nul
