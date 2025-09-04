FROM php:8.4-cli

ENV APP_HOME=/github/workspace

RUN apt-get update && apt-get install -y \
    unzip \
    libxml2 \
    libxml2-dev \
    libzip-dev \
    wget \
    openssl \
    nginx \
    supervisor \
    gettext-base \
    libicu-dev \
    curl \
    libxrender1 \
    libjpeg62-turbo \
    fontconfig \
    libxtst6 \
    xfonts-75dpi \
    xfonts-base \
    xz-utils \
    libfreetype6 \
    libpng16-16 \
    libx11-6 \
    libxcb1 \
    libxext6 \
    libssl3 \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && rm -rf /tmp/* \
    && rm -rf /var/list/apt/* \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get clean

RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl zip
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-install bcmath

WORKDIR $APP_HOME

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install xdebug \
	&& docker-php-ext-enable xdebug \
    && echo "xdebug.mode=develop,debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
	&& echo "xdebug.discover_client_host=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
	&& echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "error_reporting=E_ALL" >> /usr/local/etc/php/conf.d/error_reporting.ini

ENTRYPOINT ["/bin/sh", "-c"]
CMD ["bash"]