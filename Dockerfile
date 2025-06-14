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

# Install dependency dasar
RUN apk add --no-cache \
    bash \
    libpng libpng-dev libjpeg-turbo-dev libwebp-dev freetype-dev \
    libzip-dev zip unzip \
    oniguruma-dev postgresql-dev icu-dev libxml2-dev git curl

# Install ekstensi ringan terlebih dahulu
RUN docker-php-ext-install pdo pdo_pgsql zip mbstring xml dom tokenizer

# Install ekstensi berat satu per satu
RUN docker-php-ext-install fileinfo
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd
RUN docker-php-ext-install intl

# Tambahkan Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project file
COPY . .

# Composer install
RUN composer install --optimize-autoloader --no-dev || true

# Atur permission
RUN chown -R www-data:www-data /var/www

# Jalankan PHP-FPM
CMD ["php-fpm"]
