FROM registry.service.opg.digital/opg-php-fpm-1604:0.0.318

RUN  cd /tmp && curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer