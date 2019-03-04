FROM alpine

RUN apk add --no-cache \
    php7-pdo_sqlite \
    php7-redis \
    php7-json \
    php7-mbstring \
    php7-curl \
    php7-gd \
    php7-iconv \
    php7-fileinfo \
    php7-tokenizer \
    php7-cli \
    php7-dom \
    php7-xmlwriter \
    php7-xml \
    php7-simplexml \
    composer

WORKDIR /app
COPY . /app

RUN composer install && touch database/database.sqlite

CMD ["php", "vendor/bin/phpunit"]
