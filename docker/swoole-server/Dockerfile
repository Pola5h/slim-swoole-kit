FROM phpswoole/swoole:latest

WORKDIR /var/www/html

# Install SQLite and MySQL if needed
RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_mysql

COPY . /var/www/html
