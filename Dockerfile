FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl nodejs npm libicu-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-interaction --no-progress

RUN npm install -g @symfony/webpack-encore
RUN if [ -f package.json ]; then npm install && npm run build || npm run dev; fi

WORKDIR /var/www/html

EXPOSE 9000

CMD bash -c "until php -r 'try { new PDO(\"mysql:host=db;port=3306;dbname=zoo\", \"symfony\", \"symfony\"); } catch (Exception \$e) { exit(1); }'; do echo 'Waiting for database...'; sleep 3; done; echo 'Database is ready'; php bin/console doctrine:migrations:migrate --no-interaction || true; php-fpm"
