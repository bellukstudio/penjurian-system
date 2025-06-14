FROM php:8.2-fpm-alpine

# Set arguments
ARG USER_ID=1000
ARG GROUP_ID=1000

# Install system dependencies
RUN apk add --no-cache \
    build-base \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    oniguruma-dev \
    postgresql-dev \
    supervisor \
    libxml2-dev \
    autoconf \
    g++ \
    make

# Configure GD extension
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Install PHP extensions (corrected names)
RUN docker-php-ext-install \
    dom \
    fileinfo \
    pdo \
    pdo_pgsql \
    pgsql \
    mbstring \
    zip \
    exif \
    pcntl \
    gd \
    bcmath \
    xml \
    xmlreader \
    xmlwriter \
    tokenizer

# Install Redis extension (uncomment if needed)
# RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js & NPM
RUN apk add --no-cache nodejs npm

# Create application user
RUN addgroup -g ${GROUP_ID} penjuriandemo.bellukstudio.my.id && \
    adduser -D -s /bin/sh -u ${USER_ID} -G penjuriandemo.bellukstudio.my.id penjuriandemo.bellukstudio.my.id

# Set working directory
WORKDIR /var/www/penjuriandemo.bellukstudio.my.id

# Copy application files
COPY --chown=penjuriandemo.bellukstudio.my.id:penjuriandemo.bellukstudio.my.id . .

# Install dependencies
RUN composer install --optimize-autoloader --no-dev --no-interaction

# Install NPM dependencies and build assets
RUN npm ci && npm run build

# Set proper permissions
RUN chown -R penjuriandemo.bellukstudio.my.id:penjuriandemo.bellukstudio.my.id /var/www/penjuriandemo.bellukstudio.my.id && \
    chmod -R 755 /var/www/penjuriandemo.bellukstudio.my.id && \
    chmod -R 775 /var/www/penjuriandemo.bellukstudio.my.id/storage && \
    chmod -R 775 /var/www/penjuriandemo.bellukstudio.my.id/bootstrap/cache

# Switch to non-root user
USER penjuriandemo.bellukstudio.my.id

# Expose port 9000
EXPOSE 9000

# Start php-fpm
CMD ["php-fpm"]
