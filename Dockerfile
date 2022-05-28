FROM php:8.1.6-bullseye

WORKDIR /var/www/app

COPY . .

EXPOSE 80

RUN apt-get update

RUN apt install git -y

# RUN composer install

CMD ["php", "index.php"]