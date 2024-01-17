FROM php:8.1-cli

# install dependencies
RUN apt update \
        && apt install -y \
            git \
            zip \
        && apt-get clean

# install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer
