@echo off

start cmd /k "npm run dev"
start cmd /k "php artisan reverb:start"
start cmd /k "php artisan queue:work"
start cmd /k "php artisan serve"