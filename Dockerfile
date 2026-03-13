# Usar la imagen oficial de PHP 8.2 con Apache
FROM php:8.2-apache

# Instalación de extensiones necesarias para MySQL (mysqli y pdo_mysql)
RUN docker-php-ext-install mysqli pdo_mysql

# Habilitar el módulo rewrite de Apache (comunmente usado para enrutamiento)
RUN a2enmod rewrite

# Configurar el directorio de trabajo
WORKDIR /var/www/html

# Copiar el contenido del proyecto al contenedor
COPY . /var/www/html/

# Configurar permisos para Apache
# Ajustamos la propiedad de la carpeta al usuario de apache (www-data)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exponer el puerto 80 (puerto por defecto de Apache)
EXPOSE 80

# El comando de inicio por defecto de la imagen php:apache ya inicia el servidor
