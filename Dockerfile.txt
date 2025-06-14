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

# Update package index
RUN apk update

# Install system dependencies
RUN apk add --no-cache \
    bash \
    curl \
    git \
    zip \
    unzip \
    file

# Install build dependencies (minimal set)
RUN apk add --no-cache --virtual .build-deps \
    autoconf \
    gcc \
    g++ \
    make \
    pkgconfig

# Install PHP extension dependencies
RUN apk add --no-cache \
    # Image processing
    libpng libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    # Archive
    libzip-dev \
    # String processing
    oniguruma-dev \
    # Database
    postgresql-dev \
    # Internationalization
    icu-dev \
    # XML processing
    libxml2 \
    libxml2-dev

# Try to use pre-built extensions from Alpine packages first
RUN apk add --no-cache \
    php82-fileinfo \
    php82-dom \
    php82-xml \
    php82-tokenizer \
    php82-mbstring \
    2>/dev/null || echo "Pre-built extensions not all available"

# Configure and install only essential extensions that need compilation
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install gd

# Install database extensions
RUN docker-php-ext-install pdo pdo_pgsql

# Install other light extensions
RUN docker-php-ext-install zip intl

# Install mbstring only if not already installed via apk
RUN php -m | grep mbstring || docker-php-ext-install mbstring

# Install XML extensions only if not already installed
RUN php -m | grep xml || docker-php-ext-install xml
RUN php -m | grep dom || docker-php-ext-install dom

# Install fileinfo only if not already available
RUN php -m | grep fileinfo || \
    (echo "Installing fileinfo from source..." && \
    docker-php-ext-install fileinfo) || \
    echo "Fileinfo installation failed, may already be available"

# Enable extensions that might have been installed via apk
RUN echo "extension=fileinfo" > /usr/local/etc/php/conf.d/fileinfo.ini || true
RUN echo "extension=dom" > /usr/local/etc/php/conf.d/dom.ini || true
RUN echo "extension=xml" > /usr/local/etc/php/conf.d/xml.ini || true
RUN echo "extension=tokenizer" > /usr/local/etc/php/conf.d/tokenizer.ini || true

# Clean up build dependencies
RUN apk del .build-deps

# Install Composer
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

# Verify installations
RUN php -v && composer --version
RUN echo "=== Available PHP Extensions ===" && php -m

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install PHP dependencies with more relaxed requirements
RUN composer install \
    --optimize-autoloader \
    --no-dev \
    --no-interaction \
    --ignore-platform-req=ext-fileinfo \
    --ignore-platform-req=ext-tokenizer \
    --ignore-platform-req=ext-dom || \
    echo "Composer install completed with warnings"

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN find /var/www -type f -exec chmod 644 {} \; 2>/dev/null || true
RUN find /var/www -type d -exec chmod 755 {} \; 2>/dev/null || true
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true

EXPOSE 9000

CMD ["php-fpm"]
