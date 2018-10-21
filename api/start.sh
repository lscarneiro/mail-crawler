#!/bin/bash

# Giving some time to the DB container
sleep 20s

php artisan migrate --force
php artisan db:seed --force

apache2-foreground