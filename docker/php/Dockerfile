FROM php:8.2-fpm-alpine AS builder

# Install build dependencies
RUN apk add --no-cache \
    build-base \
    autoconf \
    g++ \
    make \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    libxml2-dev

# Configure GD extension with optimizations
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Install PHP extensions in builder stage
RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    pgsql \
    mbstring \
    zip \
    fileinfo \
    exif \
    pcntl \
    bcmath \
    tokenizer

# Install DOM and XML extensions separately (memory intensive)
RUN docker-php-ext-install dom xml xmlreader xmlwriter

# Install GD extension (most memory intensive)
RUN docker-php-ext-install gd

# Production stage
FROM php:8.2-fpm-alpine AS production

# Set arguments
ARG USER_ID=1000
ARG GROUP_ID=1000

# Install only runtime dependencies
RUN apk add --no-cache \
    libpng \
    libjpeg-turbo \
    freetype \
    libzip \
    zip \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    vim \
    unzip \
    git \
    curl \
    oniguruma \
    postgresql-libs \
    libxml2 \
    supervisor \
    nodejs \
    npm

# Copy PHP extensions from builder stage
COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create application user
RUN addgroup -g ${GROUP_ID} penjuriandemo.bellukstudio.my.id && \
    adduser -D -s /bin/sh -u ${USER_ID} -G penjuriandemo.bellukstudio.my.id penjuriandemo.bellukstudio.my.id

# Set working directory
WORKDIR /var/www/penjuriandemo.bellukstudio.my.id

# Copy application files
COPY --chown=penjuriandemo.bellukstudio.my.id:penjuriandemo.bellukstudio.my.id . .

# Switch to application user for composer and npm
USER penjuriandemo.bellukstudio.my.id

# Install dependencies
RUN composer install --optimize-autoloader --no-dev --no-interaction

# Install NPM dependencies and build assets
RUN npm ci --only=production && npm run build && npm cache clean --force

# Switch back to root to set permissions
USER root

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
