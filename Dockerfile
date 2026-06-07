# Use an official PHP image with Apache
FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql
# Copy your project files into the container's web directory
COPY . /var/www/html/

# Expose port 80 for web traffic
EXPOSE 80