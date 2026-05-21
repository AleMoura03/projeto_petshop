@echo off
title FaPet Banho e Tosa - Inicializador
color 0B

:: Forcar a pasta de trabalho a ser a pasta do proprio script
cd /d "%~dp0"

:MENU
cls
echo.
echo =============================================================
echo             FAPET BANHO E TOSA - INICIALIZADOR
echo =============================================================
echo          SISTEMA DE GERENCIAMENTO - BANHO E TOSA
echo =============================================================
echo.
echo  Selecione como deseja iniciar o sistema:
echo.
echo   [1] Iniciar Localmente (Apenas neste computador)
echo   [2] Iniciar e Compartilhar na Internet (Acessar de qualquer PC/Celular)
echo   [3] Apenas fechar qualquer servidor rodando na porta 8000
echo   [4] Sair
echo.
echo =============================================================
set opcao=
set /p opcao="Escolha uma opcao (1-4): "

if "%opcao%"=="1" goto LOCAL
if "%opcao%"=="2" goto INTERNET
if "%opcao%"=="3" goto APENAS_FECHAR
if "%opcao%"=="4" goto SAIR
goto MENU

:LOCAL
cls
echo =============================================================
echo               INICIANDO MODO LOCAL (PORTA 8000)
echo =============================================================
echo.
call :VERIFICAR_REQUISITOS
if errorlevel 1 goto FIM

echo [INFO] Iniciando o servidor local do Laravel...
start "Servidor FaPet" /MIN php artisan serve --host=0.0.0.0 --port=8000

echo [INFO] Aguardando o servidor iniciar...
ping 127.0.0.1 -n 3 >nul

:: Tentar abrir no Chrome se estiver instalado, senao usar o padrao
set "chrome_path="
if exist "C:\Program Files\Google\Chrome\Application\chrome.exe" (
    set "chrome_path=C:\Program Files\Google\Chrome\Application\chrome.exe"
) else if exist "C:\Program Files (x86)\Google\Chrome\Application\chrome.exe" (
    set "chrome_path=C:\Program Files (x86)\Google\Chrome\Application\chrome.exe"
) else if exist "%LocalAppData%\Google\Chrome\Application\chrome.exe" (
    set "chrome_path=%LocalAppData%\Google\Chrome\Application\chrome.exe"
)

if defined chrome_path (
    echo [INFO] Abrindo o sistema no Google Chrome...
    start "" "%chrome_path%" "http://localhost:8000"
) else (
    echo [INFO] Google Chrome nao encontrado. Abrindo no navegador padrao...
    start http://localhost:8000
)

cls
echo =============================================================
echo    [OK] O sistema FaPet esta rodando localmente!
echo =============================================================
echo    - Endereco local: http://localhost:8000
echo.
echo    - Credenciais de acesso:
echo      * Administrador:
echo          E-mail: admin@fapet.com
echo          Senha: admin
echo      * Cliente de Teste:
echo          E-mail: cliente@fapet.com
echo          Senha: cliente
echo =============================================================
echo    [ATENCAO] NAO FECHE ESTA JANELA ENQUANTO ESTIVER USANDO!
echo =============================================================
echo.
    echo Pressione qualquer tecla para ENCERRAR o sistema e fechar o servidor...
    pause nul
    goto MENU

:INTERNET
cls
echo =============================================================
echo            INICIANDO E COMPARTILHANDO NA INTERNET
echo =============================================================
echo.
call :VERIFICAR_REQUISITOS
if errorlevel 1 goto FIM

    where npm >nul 2>&1
    if errorlevel 1 (
        color 0C
        echo [ERRO] O Node.js/NPM nao foi encontrado no sistema.
        echo O modo de compartilhamento precisa do Node.js instalado.
        echo Por favor, instale o Node.js ou use a opcao 1 (Modo Local).
        echo.
        pause
        color 0B
        goto MENU
    )
    :: Verify npx is available
    where npx >nul 2>&1
    if errorlevel 1 (
        echo [ERRO] O npx nao foi encontrado. Instale o pacote npm que fornece npx.
        pause
        goto MENU
    )

echo [INFO] Iniciando o servidor local do Laravel em segundo plano...
start "Servidor FaPet" /MIN php artisan serve --port=8000
ping 127.0.0.1 -n 3 >nul

echo [INFO] Abrindo o tunel publico em uma nova janela...
    start "" "%~dp0run_tunnel.bat"

cls
echo =============================================================
echo    [OK] O sistema FaPet esta rodando com compartilhamento!
echo =============================================================
echo    - Endereco local: http://localhost:8000
echo    - O link publico (URL externa) sera gerado na outra janela
echo      aberta com o titulo "Localtunnel FaPet".
echo.
echo    - Ao acessar o link externo pela primeira vez, se o site pedir
echo      o IP publico do computador para liberar o acesso, descubra-o
echo      acessando https://api.ipify.org ou buscando "meu ip" no Google.
echo =============================================================
echo    - Credenciais de acesso:
echo      * Administrador:
echo          E-mail: admin@fapet.com
echo          Senha: admin
echo      * Cliente de Teste:
echo          E-mail: cliente@fapet.com
echo          Senha: cliente
echo =============================================================
echo    [ATENCAO] NAO FECHE ESTA JANELA ENQUANTO ESTIVER USANDO!
echo =============================================================
echo.
echo Pressione qualquer tecla para ENCERRAR o sistema e fechar os servidores...
pause >nul
goto MENU

:APENAS_FECHAR
cls
goto FECHAR_SERVIDOR

:FECHAR_SERVIDOR
echo [INFO] Encerrando o servidor local...
for /f "tokens=5" %%a in ('netstat -aon ^| findstr :8000 ^| findstr LISTENING') do (
    taskkill /F /PID %%a >nul 2>&1
)
echo [INFO] Fechando a janela do Localtunnel...
    taskkill /F /FI "WINDOWTITLE eq Localtunnel FaPet" >nul 2>&1
echo [INFO] Tudo limpo!
ping 127.0.0.1 -n 2 >nul
goto MENU

:SAIR
exit

:VERIFICAR_REQUISITOS
:: 1. Verificar se o PHP esta no PATH
where php >nul 2>&1
if errorlevel 1 goto ERR_PHP

:: 2. Verificar se o arquivo .env existe
if not exist ".env" copy .env.example .env >nul

:: 3. Configurar o banco de dados SQLite caso nao exista ou esteja vazio
if not exist "database\database.sqlite" type nul > database\database.sqlite

:: Verificar o tamanho do arquivo de forma linear para evitar erros de parenteses do CMD
set "precisa_migrar=0"
for %%I in ("database\database.sqlite") do if %%~zI lss 100 set "precisa_migrar=1"

if "%precisa_migrar%"=="1" echo [INFO] Banco de dados vazio. Criando tabelas e semeando dados...
if "%precisa_migrar%"=="1" php artisan migrate:fresh --force --seed
if "%precisa_migrar%"=="0" php artisan migrate --force

:: 4. Verificar dependencias do Composer
if not exist "vendor" echo [AVISO] Pasta vendor nao existe. Executando composer install...
if not exist "vendor" call composer install

:: 5. Garantir arquivos compilados (Vite)
if not exist "public\build" echo [INFO] Compilando assets...
if not exist "public\build" call npm run build
exit /b 0

:ERR_PHP
color 0C
echo [ERRO] O PHP nao foi encontrado no sistema.
echo Certifique-se de que o PHP esta instalado e no PATH do Windows.
echo.
pause
color 0B
exit /b 1

:FIM
echo [ERRO] Ocorreu uma falha ao verificar os requisitos.
pause
goto MENU
