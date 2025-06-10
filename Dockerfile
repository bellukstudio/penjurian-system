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
    locales

# Configure GD extension
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Configure PostgreSQL extension
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

# Install PHP extensions (semua sekaligus, tidak duplikasi)
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


# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/penjuriandemo.bellukstudio.my.id

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/penjuriandemo.bellukstudio.my.id/storage \
    && chown -R www-data:www-data /var/www/penjuriandemo.bellukstudio.my.id/bootstrap/cache

# Expose port
EXPOSE 8000

# Start command
CMD ["php-fpm"]
