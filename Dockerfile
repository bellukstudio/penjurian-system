FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libcurl4-openssl-dev \
    libzip-dev \
    libbz2-dev \
    libicu-dev \
    zip \
    unzip \
    curl \
    git \
    locales \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configure GD extension
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Configure PostgreSQL extension
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    ctype \
    curl \
    zip \
    intl \
    xml

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/penjuriandemo.bellukstudio.my.id

# Copy application files first (better for Docker caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy rest of application
COPY . .

# Run composer scripts after copying all files
RUN composer run-script post-install-cmd --no-interaction || true


# Set proper permissions
RUN chown -R www-data:www-data /var/www/penjuriandemo.bellukstudio.my.id/storage
RUN chown -R www-data:www-data /var/www/penjuriandemo.bellukstudio.my.id/bootstrap/cache


# Configure PHP-FPM to listen on port 9000 (standard FPM port)
RUN echo "[www]" > /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo "listen = 9000" >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo "listen.allowed_clients = 127.0.0.1" >> /usr/local/etc/php-fpm.d/zz-docker.conf

# Add PHP configuration
RUN echo "memory_limit = 256M" > /usr/local/etc/php/conf.d/laravel.ini \
    && echo "upload_max_filesize = 64M" >> /usr/local/etc/php/conf.d/laravel.ini \
    && echo "post_max_size = 64M" >> /usr/local/etc/php/conf.d/laravel.ini \
    && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/laravel.ini

# Create entrypoint script
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "Waiting for database..."\n\
while ! nc -z postgres_penjurian 5432; do\n\
    sleep 1\n\
done\n\
echo "Database is ready!"\n\
\n\
# Generate key if needed\n\
if [ ! -f .env ] || ! grep -q "APP_KEY=" .env || [ -z "$(grep APP_KEY= .env | cut -d= -f2)" ]; then\n\
    echo "Generating application key..."\n\
    php artisan key:generate --no-interaction\n\
fi\n\
\n\
# Run migrations\n\
echo "Running migrations..."\n\
php artisan migrate --force --no-interaction || true\n\
\n\
# Cache configurations\n\
echo "Caching configurations..."\n\
php artisan config:cache --no-interaction || true\n\
php artisan route:cache --no-interaction || true\n\
php artisan view:cache --no-interaction || true\n\
\n\
echo "Starting PHP-FPM..."\n\
exec php-fpm -F\n\
' > /entrypoint.sh && chmod +x /entrypoint.sh

# Install netcat for database connection check
RUN apt-get update && apt-get install -y netcat-traditional && rm -rf /var/lib/apt/lists/*

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Use entrypoint
ENTRYPOINT ["/entrypoint.sh"]
