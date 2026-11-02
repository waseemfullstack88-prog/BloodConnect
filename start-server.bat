@echo off
setlocal enabledelayedexpansion
echo ====================================================================
echo             Starting Blood Donation System Server...
echo ====================================================================

:: Clear the cache first
echo Clearing cache...
where php >nul 2>nul
if %ERRORLEVEL% equ 0 (
    php artisan optimize:clear
) else (
    "C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe" artisan optimize:clear
)

:: Dynamically detect the IPv4 Address
set "IP=127.0.0.1"
for /f "tokens=2 delims=:" %%a in ('ipconfig ^| findstr /i "IPv4 Address"') do (
    set "tempIP=%%a"
    :: Strip leading space
    set "tempIP=!tempIP:~1!"
    :: Ignore local loopback/apipa
    if not "!tempIP:~0,3!"=="169" (
        set "IP=!tempIP!"
    )
)

echo.
echo ====================================================================
echo   Server is now running and listening on all network interfaces!
echo.
echo   1. View on this PC:      http://127.0.0.1:8000
echo   2. View on same WiFi:    http://!IP!:8000
echo ====================================================================
echo.
echo   [FIREWALL TROUBLESHOOTING]
echo   If your phone cannot load http://!IP!:8000, Windows Firewall
echo   is likely blocking incoming connections on port 8000.
echo.
echo   To fix this, open PowerShell as Administrator and run:
echo   New-NetFirewallRule -DisplayName "BloodConnect" -Direction Inbound -LocalPort 8000 -Protocol TCP -Action Allow
echo ====================================================================
echo.

:: Start the server
where php >nul 2>nul
if %ERRORLEVEL% equ 0 (
    php artisan serve --host=0.0.0.0 --port=8000
) else (
    "C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe" artisan serve --host=0.0.0.0 --port=8000
)
