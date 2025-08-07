#!/bin/bash

echo "Instalando dependencias..."
composer install

echo "Copiando .env y generando clave..."
cp .env.example .env
php artisan key:generate

echo "Creando base de datos..."
mysql -u root -e "CREATE DATABASE IF NOT EXISTS cryptotrade;"

echo "Ejecutando migraciones..."
php artisan migrate

echo "Listo. Ejecuta php artisan serve para iniciar el servidor."
