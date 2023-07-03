FROM php:8.1-alpine
RUN docker-php-ext-install mysqli
WORKDIR /usr/src/myapp
EXPOSE 80
COPY . .
CMD [ "php", "-S", "0.0.0.0:8000"]