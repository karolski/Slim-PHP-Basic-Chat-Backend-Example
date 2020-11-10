FROM php:7.4-fpm

RUN apt-get update && apt-get install --yes zip unzip libzip-dev && docker-php-ext-install zip
# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /usr/share/nginx/app
COPY . .

RUN composer install --no-dev && sh setup_db.sh

RUN chown -R www-data:www-data /usr/share/nginx/app/var && \
    chown -R www-data:www-data /usr/share/nginx/app/logs && \
    chmod -R 775 /usr/share/nginx/app/var

