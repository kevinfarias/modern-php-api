FROM php:8.1.6-bullseye

WORKDIR /var/www/app

COPY . .

EXPOSE 80

ENV X_LISTEN=0.0.0.0:80

CMD ["php", "./src/Infrastructure/Api/Run.php"]