@echo off
title Laravel + Vite Dev ⚽
echo Iniciando Laravel SaaS...
start "Laravel Serve" cmd /k "cd /d C:\paseo_espana && php artisan serve --port=8001"
timeout /t 2 /nobreak >nul
start "Vite Dev" cmd /k "cd /d C:\paseo_espana && npm install && npm run dev"
echo ✅ Ambas terminales abiertas!
echo 1. http://127.0.0.1:8001
echo 2. http://127.0.0.1:8001/admin
pause
