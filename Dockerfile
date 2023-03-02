FROM composer:2.0 as vendor
COPY composer.* ./
RUN composer install \
        --ignore-platform-reqs \
        --no-interaction \
        --no-plugins \
        --no-scripts \
        --prefer-dist

FROM bruceemmanuel/php7.4-apache
COPY --from=vendor /app/vendor/ /var/www/html/vendor
COPY ./ /var/www/html
RUN mv /var/www/html/.env.example /var/www/html/.env
RUN cd /var/www/html && php artisan key:generate --ansi  \
    && php artisan storage:link 
RUN chmod -R 777 /var/www/html
CMD ["sh","-c","cd /var/www/html && php artisan migrate && apache2-foreground"]