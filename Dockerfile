FROM php:8.2-fpm

# Instalação de dependências necessárias para a extensão MySQLi
RUN apt-get update && apt-get install -y \
	libonig-dev \
	libxml2-dev \
	&& docker-php-ext-install mysqli

# Instalação de outras extensões e configurações adicionais, se necessário
RUN pecl install redis-5.3.7 \
	&& pecl install xdebug-3.2.1 \
	&& docker-php-ext-enable redis xdebug

# Copia o código-fonte do aplicativo para o contêiner
COPY . /var/www/html
WORKDIR /var/www/html

# Comando de inicialização do PHP-FPM
CMD ["php-fpm"]
