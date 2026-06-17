# Stage 1: Build & Optimization (Optional for this project as there are no build steps)
FROM php:8.2-fpm-alpine AS builder

# Set working directory
WORKDIR /var/www/html

# Copy source code
COPY . .

# In a real project, we would run:
# RUN composer install --no-dev --optimize-autoloader
# RUN npm install && npm run build

# Stage 2: Production Runtime
FROM php:8.2-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd opcache pdo_mysql zip

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Set PHP configuration (opcache)
RUN { \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=4000'; \
    echo 'opcache.revalidate_freq=2'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.enable_cli=1'; \
} > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Set working directory
WORKDIR /var/www/html

# Copy from builder stage
COPY --from=builder --chown=www-data:www-data /var/www/html /var/www/html

# Set permissions for the JSON "database" directory and uploads
RUN mkdir -p /var/www/html/data /var/www/html/uploads && \
    chmod -R 775 /var/www/html/data /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html/data /var/www/html/uploads

# Ensure db.json exists
RUN touch /var/www/html/data/db.json && \
    chown www-data:www-data /var/www/html/data/db.json

# Expose port 9000
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
