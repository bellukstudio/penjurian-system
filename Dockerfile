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

# Set working directory sesuai dengan nginx config
WORKDIR /var/www/penjuriandemo.bellukstudio.my.id

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set proper permissions
RUN chown -R www-data:www-data /var/www/penjuriandemo.bellukstudio.my.id/storage 
RUN chown -R www-data:www-data /var/www/penjuriandemo.bellukstudio.my.id/bootstrap/cache

# Generate application key if .env exists
RUN if [ -f .env ]; then php artisan key:generate --no-interaction; fi

# Cache Laravel configurations (optional, bisa error jika database belum ready)
RUN php artisan config:cache --no-interaction || true

# Configure PHP-FPM to listen on TCP instead of socket
RUN echo "listen = 8000" >> /usr/local/etc/php-fpm.d/zz-docker.conf

# Expose port untuk PHP-FPM
EXPOSE 8000

# Start PHP-FPM
CMD ["php-fpm"]
