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



# Add PHP configuration
RUN echo "memory_limit = 256M" > /usr/local/etc/php/conf.d/laravel.ini \
    && echo "upload_max_filesize = 64M" >> /usr/local/etc/php/conf.d/laravel.ini \
    && echo "post_max_size = 64M" >> /usr/local/etc/php/conf.d/laravel.ini \
    && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/laravel.ini


# Install netcat for database connection check
RUN apt-get update && apt-get install -y netcat-traditional && rm -rf /var/lib/apt/lists/*

# Expose port 9000 for PHP-FPM
EXPOSE 8000

# Use entrypoint
#ENTRYPOINT ["/entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
