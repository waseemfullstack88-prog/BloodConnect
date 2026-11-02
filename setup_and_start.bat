@echo off
setlocal enabledelayedexpansion

echo ====================================================================
echo             Blood Donation System - Universal Setup ^& Start
echo ====================================================================
echo.

:: --- 1. Check System Dependencies ---
echo [1/8] Checking System Requirements...

set "USE_PORTABLE=0"
set "PHP_CMD=php"
set "COMPOSER_CMD=composer"
set "NPM_CMD=npm"

where php >nul 2>nul
if %ERRORLEVEL% neq 0 (
    if exist "C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe" (
        set "PHP_CMD=C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe"
        echo [OK] PHP found in Laragon.
    ) else (
        echo [MISSING] PHP is not installed globally.
        set "USE_PORTABLE=1"
    )
) else (
    echo [OK] PHP is installed.
)

where composer >nul 2>nul
if %ERRORLEVEL% neq 0 (
    echo [MISSING] Composer is not installed globally.
    set "USE_PORTABLE=1"
) else (
    echo [OK] Composer is installed.
)

where npm >nul 2>nul
if %ERRORLEVEL% neq 0 (
    echo [MISSING] Node.js is not installed globally.
    set "USE_PORTABLE=1"
) else (
    echo [OK] Node.js is installed.
)

if "!USE_PORTABLE!"=="1" (
    echo.
    echo -------------------------------------------------------------------------
    echo Some required tools are missing. Downloading portable versions...
    echo -------------------------------------------------------------------------
    echo.
    if not exist ".bin" mkdir .bin

    where php >nul 2>nul
    if !ERRORLEVEL! neq 0 (
        if not exist ".bin\php\php.exe" (
            echo Downloading Portable PHP ^(this may take a moment^)...
            powershell -Command "[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12; Invoke-WebRequest -Uri 'https://windows.php.net/downloads/releases/archives/php-8.2.12-nts-Win32-vs16-x64.zip' -OutFile '.bin\php.zip'"
            echo Extracting PHP...
            powershell -Command "Expand-Archive -Path '.bin\php.zip' -DestinationPath '.bin\php' -Force"
            del .bin\php.zip
            copy ".bin\php\php.ini-development" ".bin\php\php.ini" >nul
            powershell -Command "(Get-Content '.bin\php\php.ini') -replace ';extension=pdo_sqlite','extension=pdo_sqlite' | Set-Content '.bin\php\php.ini'"
            powershell -Command "(Get-Content '.bin\php\php.ini') -replace ';extension=mbstring','extension=mbstring' | Set-Content '.bin\php\php.ini'"
            powershell -Command "(Get-Content '.bin\php\php.ini') -replace ';extension=fileinfo','extension=fileinfo' | Set-Content '.bin\php\php.ini'"
            powershell -Command "(Get-Content '.bin\php\php.ini') -replace ';extension=openssl','extension=openssl' | Set-Content '.bin\php\php.ini'"
            powershell -Command "(Get-Content '.bin\php\php.ini') -replace ';extension=curl','extension=curl' | Set-Content '.bin\php\php.ini'"
            powershell -Command "(Get-Content '.bin\php\php.ini') -replace ';extension=pdo_mysql','extension=pdo_mysql' | Set-Content '.bin\php\php.ini'"
        )
        set "PHP_CMD=%CD%\.bin\php\php.exe"
        echo [OK] Portable PHP ready.
    )

    where composer >nul 2>nul
    if !ERRORLEVEL! neq 0 (
        if not exist ".bin\composer.phar" (
            echo Downloading Portable Composer...
            powershell -Command "[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12; Invoke-WebRequest -Uri 'https://getcomposer.org/composer-stable.phar' -OutFile '.bin\composer.phar'"
        )
        set "COMPOSER_CMD=!PHP_CMD! %CD%\.bin\composer.phar"
        echo [OK] Portable Composer ready.
    )

    where npm >nul 2>nul
    if !ERRORLEVEL! neq 0 (
        if not exist ".bin\node\npm.cmd" (
            echo Downloading Portable Node.js ^(this may take a moment^)...
            powershell -Command "[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12; Invoke-WebRequest -Uri 'https://nodejs.org/dist/v20.11.0/node-v20.11.0-win-x64.zip' -OutFile '.bin\node.zip'"
            echo Extracting Node.js...
            powershell -Command "Expand-Archive -Path '.bin\node.zip' -DestinationPath '.bin\extracted_node' -Force"
            move ".bin\extracted_node\node-v20.11.0-win-x64" ".bin\node" >nul
            rd /s /q ".bin\extracted_node"
            del .bin\node.zip
        )
        set "NPM_CMD=%CD%\.bin\node\npm.cmd"
        set "PATH=%CD%\.bin\node;!PATH!"
        echo [OK] Portable Node.js ready.
    )
    echo.
)
echo.

:: --- 2. Environment Setup ---
echo [2/8] Setting up Environment File (.env)...
if not exist .env (
    echo .env not found. Creating from .env.example...
    copy .env.example .env >nul
)

:: ALWAYS check MySQL and fix .env - this fixes the SQLite switching bug on restart
echo Checking database connectivity...
powershell -Command "try{$c=New-Object Net.Sockets.TcpClient;$c.Connect('127.0.0.1',3306);$c.Close();exit 0}catch{exit 1}" >nul 2>&1
set "MYSQL_RUNNING=!ERRORLEVEL!"

if !MYSQL_RUNNING! equ 0 (
    echo [OK] MySQL detected. Setting .env to MySQL...
    powershell -Command "(Get-Content .env) -replace '^DB_CONNECTION=.*','DB_CONNECTION=mysql' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace '^DB_DATABASE=.*','DB_DATABASE=blood_donation_db' | Set-Content .env"
    echo [OK] Database: MySQL / blood_donation_db
) else (
    echo [INFO] MySQL not found. Using SQLite fallback...
    if not exist "database\database.sqlite" type nul > database\database.sqlite
    powershell -Command "(Get-Content .env) -replace ''^DB_CONNECTION=.*'', 'DB_CONNECTION=sqlite' | Set-Content .env"
    powershell -Command "$p=(Get-Location).Path.Replace(''\\''',''/''); (Get-Content .env) -replace ''^DB_DATABASE=.*'', ('DB_DATABASE='+ $p +'/database/database.sqlite') | Set-Content .env"
    echo [OK] Database: SQLite
)
echo.

:: --- 3. Composer Install (skip if vendor exists) ---
echo [3/8] Installing PHP Dependencies (Composer)...
if not exist "vendor\autoload.php" (
    call !COMPOSER_CMD! install --no-interaction
    echo [OK] Composer packages installed.
) else (
    echo [OK] vendor\ exists. Skipping composer install.
)
echo.

:: --- 4. Application Key (only generate if missing) ---
echo [4/8] Checking Application Key...
powershell -Command "if((Get-Content .env) -match 'APP_KEY=base64:.+'){exit 0}else{exit 1}" >nul 2>&1
if !ERRORLEVEL! neq 0 (
    call !PHP_CMD! artisan key:generate
    echo [OK] Application key generated.
) else (
    echo [OK] Application key already set. Skipping.
)
echo.

:: --- 5. NPM Install (skip if node_modules exists) ---
echo [5/8] Installing Node Dependencies (NPM)...
if not exist "node_modules" (
    call !NPM_CMD! install
    echo [OK] Node packages installed.
) else (
    echo [OK] node_modules\ exists. Skipping npm install.
)
echo.

:: --- 6. Database Setup ---
echo [6/8] Setting up Database...
call !PHP_CMD! artisan config:clear >nul

if !MYSQL_RUNNING! equ 0 (
    echo [INFO] Ensuring MySQL database exists...
    !PHP_CMD! -r "try{$p=new PDO(\'mysql:host=127.0.0.1;port=3306\',\'root\',\'\');$p->exec(\'CREATE DATABASE IF NOT EXISTS blood_donation_db\');}catch(PDOException $e){}" >nul 2>&1

    :: Check if DB has data - only import on first run (protects your records)
    set "DB_EMPTY=1"
    !PHP_CMD! -r "try{$p=new PDO(\'mysql:host=127.0.0.1;port=3306;dbname=blood_donation_db\',\'root\',\'\');$r=$p->query(\'SELECT COUNT(*) FROM users\');if($r&&$r->fetchColumn()>0){echo \'HAS_DATA\';}}catch(Exception $e){echo \'NO_TABLE\';}" >".tmp_dbcheck.txt" 2>nul
    findstr /C:"HAS_DATA" ".tmp_dbcheck.txt" >nul 2>nul
    if !ERRORLEVEL! equ 0 set "DB_EMPTY=0"
    del ".tmp_dbcheck.txt" >nul 2>nul

    if !DB_EMPTY! equ 1 (
        echo [INFO] Database empty. Importing SQL backup for first-time setup...
        set "MYSQL_EXE="
        where mysql >nul 2>nul
        if !ERRORLEVEL! equ 0 set "MYSQL_EXE=mysql"
        if "!MYSQL_EXE!"=="" (
            for /d %%I in ("C:\laragon\bin\mysql\*") do (
                if exist "%%I\bin\mysql.exe" set "MYSQL_EXE=%%I\bin\mysql.exe"
            )
        )
        if not "!MYSQL_EXE!"=="" (
            if exist "database\blood_donation_db.sql" (
                !MYSQL_EXE! -u root blood_donation_db < "database\blood_donation_db.sql" 2>nul
                echo [OK] Database imported!
            )
        ) else (
            echo [WARNING] mysql.exe not found. Skipping import.
        )
    ) else (
        echo [OK] Database has data. Skipping import ^(your records are safe^).
    )
)

echo Running migrations...
call !PHP_CMD! artisan migrate --force
echo.

:: --- 7. Clear Cache ---
echo [7/8] Clearing Application Caches...
call !PHP_CMD! artisan optimize:clear
echo.

:: --- 8. Start Servers ---
echo [8/8] Starting Servers...
echo.

set "IP=127.0.0.1"
for /f "tokens=2 delims=:" %%a in ('ipconfig ^| findstr /i "IPv4 Address"') do (
    set "tempIP=%%a"
    set "tempIP=!tempIP:~1!"
    if not "!tempIP:~0,3!"=="169" set "IP=!tempIP!"
)

echo ====================================================================
echo   BloodConnect is RUNNING!
echo.
echo   Open in browser:
echo    - Local:    http://127.0.0.1:8000
echo    - Network:  http://!IP!:8000
echo.
echo   Admin:  waseembhutta723@gmail.com  /  Password
echo ====================================================================
echo.

start "BloodConnect - Frontend" cmd /k "!NPM_CMD! run watch"
echo Backend server starting... Keep this window open.
echo.

!PHP_CMD! artisan serve --host=0.0.0.0 --port=8000

echo.
echo Server stopped. Press any key to close.
pause
