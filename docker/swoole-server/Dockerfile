FROM phpswoole/swoole:latest

WORKDIR /var/www/html

# Install SQLite if needed
RUN apt-get update && apt-get install -y sqlite3 libsqlite3-dev

COPY . /var/www/html
