# ND-PHP Testing
#
# VERSION	0.1

FROM ucodev/base

MAINTAINER Pedro A. Hortas <pah@ucodev.org>
LABEL Description="Installs the a testing environment for ND PHP Framework" Vendor="uCodev" Version="0.1"

# Arguments
ARG MYSQL_ROOT_PASSWORD=password
ARG MYSQL_USER_DATABASE=ndphp
ARG MYSQL_USER_USERNAME=ndphp_user
ARG MYSQL_USER_PASSWORD=password

# Inform about the used ports
EXPOSE 80
EXPOSE 3306

# Install MySQL (noninteractive to prevent installer to ask for password)
RUN /bin/bash -c "DEBIAN_FRONTEND=noninteractive apt-get install -y mysql-server mysql-client"
COPY files/mysqld.cnf /etc/mysql/mysql.conf.d/mysqld.cnf

# Start MySQL Server to allow password change via mysqladmin. Then stop it.
# FIXME: Use debconf-set-selections before apt-get install
RUN /bin/bash -c "service mysql start; mysqladmin password \"${MYSQL_ROOT_PASSWORD}\"; service mysql stop"

# Create database, user/pass and grant access
RUN /bin/bash -c "service mysql start; echo \"CREATE DATABASE \\\`${MYSQL_USER_DATABASE}\\\`; CREATE USER '${MYSQL_USER_USERNAME}' IDENTIFIED BY '${MYSQL_USER_PASSWORD}'; GRANT ALL PRIVILEGES ON \\\`${MYSQL_USER_DATABASE}\\\`.* TO '${MYSQL_USER_USERNAME}'; FLUSH PRIVILEGES;\" | mysql -uroot -p\"${MYSQL_ROOT_PASSWORD}\"; service mysql stop"


# Install Apache2 and PHP
RUN apt-get install -y apache2 php libapache2-mod-php

# Setup the default vhost
COPY files/000-default.conf /etc/apache2/sites-available/

# Setup ND PHP dependencies
RUN apt-get install -y php-mcrypt php-gd php-curl php-memcached php-mbstring php-xml php-mysql
RUN phpenmod mcrypt gd curl memcached mbstring xml

# Fetch latest version
RUN mkdir /var/www/html/nd-php
COPY files/nd-php /var/www/html/nd-php/

# Setup permissions
RUN chown -R www-data:www-data /var/www/html/nd-php

# Copy rc.local
COPY files/rc.local /etc/rc.local
RUN chmod 755 /etc/rc.local

# Init
CMD /etc/rc.local

