FROM php:8.2-apache
# Ustaw katalog roboczy w kontenerze
WORKDIR /var/www
# Instalacja rozszerzeń pdo_mysql
RUN docker-php-ext-install pdo_mysql
# Instaluj potrzebne zależności i rozszerzenia PHP
RUN apt-get update && apt-get install -y \
        libpq-dev \
        default-libmysqlclient-dev \
    && docker-php-ext-install \
        pdo pdo_mysql

# Skopiuj pliki projektu do katalogu roboczego kontenera
COPY . /var/www

# Ustaw odpowiednie uprawnienia dla katalogu
RUN chown -R www-data:www-data /var/www

# Zainstaluj Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_ALLOW_SUPERUSER=1
# Zainstaluj zależności PHP używając Composer
RUN composer install --ignore-platform-req=ext-http

# Ustaw domyślny port na 80
EXPOSE 80

# Uruchom Apache
CMD ["apache2-foreground"]