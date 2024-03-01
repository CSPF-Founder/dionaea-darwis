FROM debian:bookworm-slim

# Update source.list
RUN echo 'deb  http://deb.debian.org/debian bookworm main' > /etc/apt/sources.list
RUN echo 'deb-src  http://deb.debian.org/debian bookworm main' >> /etc/apt/sources.list
RUN echo 'deb  http://deb.debian.org/debian bookworm-updates main' >> /etc/apt/sources.list
RUN echo 'deb-src  http://deb.debian.org/debian bookworm-updates main' >> /etc/apt/sources.list

RUN apt-get update && apt-get -y upgrade 

RUN useradd -ms /bin/bash appuser

RUN apt install nano curl wget -y

# Debian Repository for latest PHP
# RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN curl -sSL https://packages.sury.org/php/README.txt | bash -x
RUN echo "deb https://packages.sury.org/php/ bookworm main" >> /etc/apt/sources.list

RUN DEBIAN_FRONTEND=noninteractive apt-get install -y apt-transport-https

RUN apt update && apt-get upgrade -y

# PHP & apache
RUN DEBIAN_FRONTEND=noninteractive apt-get -y install apache2 php8.2 php8.2-fpm \ 
    php8.2-mysql php8.2-zip php8.2-curl php8.2-mbstring php8.2-gd php8.2-imagick

# RUN apt-get -y install php8.2-pear php8.2-dev
RUN apt-get install php8.2-xml -y

RUN apt-get install php8.2-mongodb -y

#apache Configurations
RUN a2enmod proxy_fcgi setenvif
RUN a2enconf php8.2-fpm

ENV TZ=Asia/Kolkata
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Enable apache mods.
RUN a2enmod proxy_fcgi setenvif
RUN a2enconf php8.2-fpm
RUN a2enmod rewrite
RUN a2enmod ssl

# Replace Apache & PHP user
RUN sed -i 's/export APACHE_RUN_USER=www-data/export APACHE_RUN_USER=appuser/' /etc/apache2/envvars
RUN sed -i 's/export APACHE_RUN_GROUP=www-data/export APACHE_RUN_GROUP=appuser/' /etc/apache2/envvars
RUN sed -i 's/www-data/appuser/g' /etc/php/8.2/fpm/pool.d/www.conf

# supervisor installation && 
# create directory for child images to store configuration in
RUN apt-get -y install supervisor && \
    mkdir -p /var/log/supervisor && \
    mkdir -p /etc/supervisor/conf.d

ADD docker_resources/ssl_certs/server.key /etc/ssl/server.key
ADD docker_resources/ssl_certs/server.crt /etc/ssl/server.crt
ADD docker_resources/apache/panel.conf /etc/apache2/sites-enabled/panel.conf 

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US.UTF-8
ENV LC_ALL en_US.UTF-8

# supervisor base configuration
ADD docker_resources/supervisor/supervisor.conf /etc/supervisor.conf
ADD docker_resources/supervisor/webserver.conf /etc/supervisor/conf.d/
ADD docker_resources/supervisor/pythonservice.conf /etc/supervisor/conf.d/

RUN mkdir -p /app/dt/data/tmp_hash_checked/ && \
    mkdir -p /processed_files/

RUN apt-get install python3-requests -y

RUN chmod -R 777 /app/dt/data/ && \
    chmod -R 777 /processed_files/

COPY ./threat_intel/ /app/dt/threat_intel/
RUN mkdir /app/dt/threat_intel/config/
RUN mkdir /app/dt/threat_intel/logs/
RUN chown -R appuser:appuser /app/dt/threat_intel/

CMD ["supervisord", "-c", "/etc/supervisor.conf"]
