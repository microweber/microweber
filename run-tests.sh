#!/bin/bash

set -o errexit

docker-compose build && docker-compose up -d
docker-compose exec -T fpm composer install --dev
docker-compose exec -T fpm vendor/bin/phpunit
