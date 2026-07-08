FROM php:8.3-apache

WORKDIR /var/www/html

COPY . .

RUN chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
