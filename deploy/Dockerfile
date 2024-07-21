# Pull base image.

FROM php:7.3-fpm
RUN apt-get update \
 && apt-get install -y --no-install-recommends ca-certificates
RUN update-ca-certificates
RUN docker-php-ext-install mysqli && docker-php-ext-install pdo_mysql
RUN apt-get clean && apt-get update && apt-get install --fix-missing wget apt-transport-https lsb-release gnupg2 -y
RUN echo "deb http://packages.dotdeb.org jessie all" >> /etc/apt/sources.list
RUN echo "deb-src http://packages.dotdeb.org jessie all" >> /etc/apt/sources.list
RUN cd /tmp && wget https://www.dotdeb.org/dotdeb.gpg && apt-key add dotdeb.gpg
RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list


RUN apt-get clean && apt-get update && apt-cache search php-mysql && apt-get install --fix-missing -y \
  ruby-dev \
  rubygems \
  imagemagick \
  graphviz \
  sudo \
  git \
  vim \
  memcached \
  libmemcached-tools \
  libmemcached-dev \
  libpng-dev \
  libjpeg62-turbo-dev \
  libmcrypt-dev \
  default-mysql-client \
  libxml2-dev \
  libxslt1-dev \
  zip \
  wget \
  linux-libc-dev \
  libyaml-dev \
  zlib1g-dev \
  libicu-dev \
  g++ \
  libpq-dev \
  bash-completion \
  htop \
  libldap2-dev \
  libzip-dev \
  libssl-dev

COPY docker-php-ext-install /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-php-ext-install
RUN docker-php-ext-configure gd --with-jpeg-dir=/usr/include/
RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/
RUN docker-php-ext-install gd
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install zip
RUN docker-php-ext-install soap
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install xsl
RUN docker-php-ext-install opcache
RUN docker-php-ext-install calendar
RUN docker-php-ext-install exif
RUN docker-php-ext-install pgsql
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install ftp
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install ldap

RUN pecl install mcrypt-1.0.2 && \
    docker-php-ext-enable mcrypt

#RUN apk --no-cache add php7-pear php7-dev gcc musl-dev make

# Install YAML extension
#RUN pecl install yaml-2.0.2 && echo "extension=yaml.so" > /usr/local/etc/php/conf.d/ext-yaml.ini

# Install APCu extension
#RUN pecl install apcu-5.1.8

# Install sass and gem dependency
RUN apt-get install --fix-missing automake ruby2.5-dev libtool -y

# installation of ssmtp
#RUN DEBIAN_FRONTEND=noninteractive apt-get install --fix-missing -y ssmtp && rm -r /var/lib/apt/lists/*
#ADD ssmtp.conf /etc/ssmtp/ssmtp.conf
#ADD php-smtp.ini /usr/local/etc/php/conf.d/php-smtp.ini

# Installation of APCu cache
RUN ( \
  echo "extension=apcu.so"; \
  echo "apc.enabled=1"; \
  ) > /usr/local/etc/php/conf.d/ext-apcu.ini

# Set timezone to Europe/Paris
RUN echo "Asia/Hong_Kong" > /etc/timezone && dpkg-reconfigure -f noninteractive tzdata

# Install Imagick.
RUN export CFLAGS="$PHP_CFLAGS" CPPFLAGS="$PHP_CPPFLAGS" LDFLAGS="$PHP_LDFLAGS" \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
        libmagickwand-dev \
    && rm -rf /var/lib/apt/lists/* \
    && pecl install imagick-3.4.3 \
    && docker-php-ext-enable imagick

# Install dependencies
# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get clean && apt-get update && apt-get install --fix-missing -y \
  jpegoptim \
  optipng \
  pngquant \
  gifsicle