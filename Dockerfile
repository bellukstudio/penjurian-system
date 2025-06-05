FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    libpq-dev

RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/penjuriandemo.bellukstudio.my.id

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN sudo chown -R www-data.www-data /var/www/penjuriandemo.bellukstudio.my.id/storage
RUN sudo chown -R www-data.www-data /var/www/penjuriandemo.bellukstudio.my.id/bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
