#!/bin/bash

# Giving some time to the DB and API containers
sleep 30s

php artisan crawl:now