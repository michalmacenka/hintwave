FROM php:8.1-apache
RUN docker-php-ext-install pdo pdo_mysql 
RUN apt-get update && apt-get install -y \
    libwebp-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp
RUN docker-php-ext-install -j$(nproc) gd
RUN a2enmod rewrite

# Enable Apache modules
RUN a2enmod proxy
RUN a2enmod proxy_http
RUN a2enmod headers

# Copy Apache configuration
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
COPY ports.conf /etc/apache2/ports.conf

# Script to update Apache configuration with actual PORT
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
