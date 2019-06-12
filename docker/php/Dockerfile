FROM ubuntu:18.04

## Install tools
RUN apt update --fix-missing \
    && apt upgrade -y \
    && apt dist-upgrade -y \
    && apt install -y software-properties-common language-pack-en-base

# Add PHP PPA
RUN apt-key adv --keyserver keyserver.ubuntu.com --recv-keys E5267A6C
RUN LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php


## Install tools
RUN apt update \
    && apt install -y \
       cron sudo msmtp \
       php7.2 \
       php7.2-cli \
       php7.2-bcmath \
       php7.2-curl \
       php7.2-fpm \
       php7.2-gd \
       php7.2-intl \
       php7.2-imap \
       php7.2-json \
       php7.2-ldap \
       php7.2-mbstring \
       php7.2-mysql \
       php7.2-soap \
       php7.2-xml \
       php7.2-xsl \
       php7.2-xmlrpc \
       php7.2-zip \
       php-xdebug \
       graphicsmagick \
    && apt clean

COPY files/pool.conf /etc/php/7.2/fpm/pool.d/zzz.conf
COPY files/xdebug.ini /etc/php/7.2/mods-available/xdebug.ini
COPY files/php.ini /etc/php/7.2/fpm/conf.d/30-user.ini
COPY files/startup.sh /usr/local/bin/startup

RUN chmod +x /usr/local/bin/startup
RUN echo "TLS_REQCERT never" > /etc/ldap/ldap.conf
RUN usermod -u 1000 www-data

CMD ["/usr/local/bin/startup"]

#RUN apt-get install curl
#RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -
#RUN  apt-get update && apt-get install yarn
#RUN yarn install
