FROM php:8-fpm

COPY ./ /var/www/html
WORKDIR /var/www/html

RUN apt-get update -y \
    && apt-get install -y \
        zip \
        git \
        curl \
        libpq-dev \
    && docker-php-ext-install \
        pgsql pdo_pgsql \
        mysqli \
        pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN usermod -u 1000 www-data

EXPOSE 9000

CMD ["php-fpm"]