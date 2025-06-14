# FROM dunglas/frankenphp:1-php8.3

# # Install system dependencies
# RUN apt-get update && apt-get install -y \
#     build-essential \
#     libpng-dev \
#     libjpeg62-turbo-dev \
#     libfreetype6-dev \
#     libonig-dev \
#     libxml2-dev \
#     libpq-dev \
#     libcurl4-openssl-dev \
#     libzip-dev \
#     libbz2-dev \
#     libicu-dev \
#     zip \
#     unzip \
#     curl \
#     git \
#     locales \
#     && rm -rf /var/lib/apt/lists/*

# # Configure GD extension
# RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# # Configure PostgreSQL extension
# RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

# # Install PHP extensions
# RUN docker-php-ext-install \
#     pdo \
#     pdo_pgsql \
#     pgsql \
#     mbstring \
#     exif \
#     pcntl \
#     bcmath \
#     gd \
#     ctype \
#     curl \
#     zip \
#     intl \
#     xml

# # Install Composer
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# # Set working directory
# WORKDIR /app

# # Copy application files
# COPY . .

# # Install PHP dependencies
# RUN composer install --no-dev --optimize-autoloader

# # Set permissions for Laravel
# RUN chown -R www-data:www-data /app/storage \
#     && chown -R www-data:www-data /app/bootstrap/cache \
#     && chmod -R 775 /app/storage \
#     && chmod -R 775 /app/bootstrap/cache

# # Create Caddyfile for FrankenPHP
# RUN echo ':80 {\n\
#     root * /app/public\n\
#     encode gzip\n\
#     php_fastcgi unix//var/run/php/php-fpm.sock\n\
#     file_server\n\
#     \n\
#     @php {\n\
#         path *.php\n\
#     }\n\
#     \n\
#     handle @php {\n\
#         php\n\
#     }\n\
#     \n\
#     handle {\n\
#         try_files {path} {path}/ /index.php?{query}\n\
#     }\n\
#     \n\
#     header {\n\
#         X-Frame-Options "SAMEORIGIN"\n\
#         X-XSS-Protection "1; mode=block"\n\
#         X-Content-Type-Options "nosniff"\n\
#         Referrer-Policy "no-referrer-when-downgrade"\n\
#     }\n\
#     \n\
#     @static {\n\
#         path *.js *.css *.png *.jpg *.jpeg *.gif *.ico *.svg *.woff *.woff2 *.ttf *.eot\n\
#     }\n\
#     \n\
#     header @static {\n\
#         Cache-Control "public, max-age=31536000, immutable"\n\
#     }\n\
# }' > /etc/caddy/Caddyfile

# # Expose port
# EXPOSE 80

# # Start FrankenPHP
# CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]

FROM php:8.2.28-fpm-alpine3.22

# Install dependencies dan build tools
RUN apk add --no-cache \
    bash \
    libpng libpng-dev libjpeg-turbo-dev libwebp-dev freetype-dev \
    libzip-dev zip unzip \
    oniguruma-dev postgresql-dev icu-dev libxml2-dev git curl \
    file \
    # Build tools untuk kompilasi
    autoconf gcc g++ make \
    # Tools untuk tokenizer jika diperlukan
    re2c bison

# Configure dan install extensions (TANPA tokenizer)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        zip \
        intl \
        gd \
        mbstring \
        xml \
        dom \
        fileinfo

# Alternatif install tokenizer jika benar-benar diperlukan:
# Cara 1: Install via package manager (recommended)
RUN apk add --no-cache php82-tokenizer 2>/dev/null || echo "php82-tokenizer not available"

# Cara 2: Jika cara 1 gagal, coba manual install
# RUN cd /usr/src/php/ext/tokenizer && phpize && ./configure && make && make install \
#     && docker-php-ext-enable tokenizer

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-dev || true

# Set permissions
RUN chown -R www-data:www-data /var/www

# # Expose port (optional, untuk dokumentasi)
# EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
