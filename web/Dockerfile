FROM php:8.5-apache

# Устанавливаем системные зависимости
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && docker-php-ext-install pdo_pgsql

# Устанавливаем Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Включаем модули Apache
RUN a2enmod rewrite headers

# Копируем конфиги
COPY php.ini /usr/local/etc/php/php.ini
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Копируем файлы сайта
COPY . /var/www/html/

# Права
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
