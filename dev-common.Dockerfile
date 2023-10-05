FROM ubuntu:22.04

LABEL maintainer="Media Interactiva <jmalgarin@mediainteractiva.com>"

ARG WWWGROUP
ARG NODE_VERSION=18
ARG POSTGRES_VERSION=15

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin libpng-dev python2 dnsutils librsvg2-bin \
    && curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c' | gpg --dearmor | tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update \
    && apt-get install -y php8.1-cli php8.1-dev \
       php8.1-pgsql php8.1-sqlite3 php8.1-gd php8.1-imagick \
       php8.1-curl \
       php8.1-imap php8.1-mysql php8.1-mbstring \
       php8.1-xml php8.1-zip php8.1-bcmath php8.1-soap \
       php8.1-intl php8.1-readline \
       php8.1-fpm \
       php8.1-ldap \
       php8.1-msgpack php8.1-igbinary php8.1-redis php8.1-swoole \
       php8.1-memcached php8.1-pcov php8.1-xdebug \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    && curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor | tee /etc/apt/keyrings/yarn.gpg >/dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/yarn.gpg] https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && curl -sS https://www.postgresql.org/media/keys/ACCC4CF8.asc | gpg --dearmor | tee /etc/apt/keyrings/pgdg.gpg >/dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/pgdg.gpg] http://apt.postgresql.org/pub/repos/apt jammy-pgdg main" > /etc/apt/sources.list.d/pgdg.list \
    && apt-get update \
    && apt-get install -y nginx \
    && apt-get install -y yarn \
    && apt-get install -y mysql-client \
    && apt-get install -y postgresql-client-$POSTGRES_VERSION \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN setcap "cap_net_bind_service=+ep" /usr/bin/php8.1

# Install mongodb
RUN pecl channel-update pecl.php.net
RUN pecl -q install mongodb && \
    sed -i '$a extension=mongodb.so' /etc/php/8.1/cli/php.ini && \
    sed -i '$a extension=mongodb.so' /etc/php/8.1/fpm/php.ini

# RUN groupadd --force -g 1000 sail
# RUN useradd -ms /bin/bash --no-user-group -g 1000 -u 1337 sail
COPY ./ /var/www/html/

#SET UP ITALENT
RUN composer install
RUN npm install
RUN cp .env.example .env
RUN npm run build

# Setting up environment
COPY php/default.conf /etc/nginx/sites-enabled/
COPY web/nginx.conf /etc/nginx/
COPY ./public/ /var/www/html/public/

# Set directory permissions
RUN chown -R www-data: /var/www/html && \
    chmod -R 2775 /var/www/html && \
    chown -R www-data: /var/lib/nginx

COPY php/start-container /usr/local/bin/start-container
COPY php/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY php/php.ini /etc/php/8.1/cli/conf.d/99-sail.ini
COPY php/php.ini /etc/php/8.1/fpm/conf.d/99-sail.ini
COPY php/www.conf /etc/php/8.1/fpm/pool.d/www.conf
RUN chmod +x /usr/local/bin/start-container
RUN rm /etc/nginx/sites-enabled/default

EXPOSE 8000
EXPOSE 80

ENTRYPOINT ["/bin/sh", "-c"]
CMD ["/usr/bin/php /var/www/html/artisan optimize; /usr/sbin/php-fpm8.1 --nodaemonize --fpm-config /etc/php/8.1/fpm/php-fpm.conf & /usr/sbin/nginx -g 'daemon off;'"]
