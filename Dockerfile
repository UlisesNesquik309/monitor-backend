FROM php:8.2-apache

# Extensiones de PHP para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Python3 + psutil, para que PHP pueda ejecutar monitor.py
RUN apt-get update && \
    apt-get install -y python3 python3-pip && \
    pip3 install psutil --break-system-packages && \
    apt-get clean

# Habilitar CORS/headers si hace falta reescritura de URLs
RUN a2enmod headers rewrite
