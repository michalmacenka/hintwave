FROM php:8.0-apache
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
