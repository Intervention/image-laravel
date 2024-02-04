FROM php:8.1-cli

# install dependencies
RUN apt update \
        && apt install -y \
            libmagickwand-dev \
            git \
            zip \
        && docker-php-ext-configure gd --with-jpeg \
        && docker-php-ext-install gd \
        && apt-get clean

# install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer
