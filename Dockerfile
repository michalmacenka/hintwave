FROM php:8.0-apache
RUN docker-php-ext-install pdo pdo_mysql 
RUN apt-get update && apt-get upgrade -y 
RUN a2enmod rewrite


# RUN apt-get update && apt-get install -y \
#   imagemagick \
#   libfreetype6-dev \
#   libjpeg62-turbo-dev \
#   libmagickwand-dev --no-install-recommends \
#   libpng-dev \
#   && rm -rf /var/lib/apt/lists/* \
#   && a2enmod rewrite \
#   && docker-php-ext-install exif \
#   && docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install -j$(nproc) gd \
#   && pecl install imagick && docker-php-ext-enable imagick \
