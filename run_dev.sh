#!/bin/bash

bash -c "npm run dev" &
bash -c "php artisan reverb:start" &
bash -c "php artisan queue:work" &
bash -c "php artisan serve" &

wait