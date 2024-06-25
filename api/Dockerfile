FROM php:7.4.33-apache
RUN docker-php-ext-install mysqli
RUN a2enmod rewrite
RUN a2enmod ssl
COPY . /var/www/html/
# Expose port 80 to the outside world
EXPOSE 80