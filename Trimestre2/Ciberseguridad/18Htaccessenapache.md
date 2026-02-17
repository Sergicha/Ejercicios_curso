# 18. [Hardening Web + OWASP] htaccess EN APACHE (Control, Seguridad y SEO) - Juan Luis Bermejo

## Parte 1: Activación de .htaccess en Apache

### Editar el VirtualHost y localizar:

<Directory /var/www/html>
   AllowOverride None
   Require all granted
</Directory>

### Cambiar None por All

AllowOverride All

### Reiniciar Apache

sudo systemctl restart apache2

### Crear un archivo /var/www/html/.htaccess con este contenido

AddDefaultCharset UTF-8

### Abrir el archivo de sitio por defecto

sudo nano /etc/apache2/sites-available/000-default.conf

### Buscar un bloque <VirtualHost *:80> que contiene algo como

<VirtualHost *:80>
    <Directory /var/www/html>
        Options Indexes FollowSymLinks
        AllowOverride None
        Require all granted
    </Directory>
</VirtualHost>

### Cambia AllowOverride None por AllowOverride All

### Guardar, cerrar y reiniciar Apache

sudo systemctl restart apache2

## Parte 2: Autenticación Básica con .htaccess

### Crear una carpeta /var/www/html/admin/ con un index.html.

### Crear /var/www/html/.htpasswd generando un usuario

htpasswd -c /var/www/html/.htpasswd admin

### Crear /var/www/html/admin/.htaccess

AuthType Basic
AuthName "Zona restringida"
AuthUserFile /var/www/html/.htpasswd
Require valid-user

### Entrar a http://192.168.237.129/admin y probar el acceso, (la ip es la del server de cada uno)


## Parte 3: Gestión de Errores Personalizados

### Crear 404.html y 403.html en la raíz

### Añadir en /var/www/html/.htaccess

ErrorDocument 404 /404.html
ErrorDocument 403 /403.html

### Probar introduciendo una URL inexistente

## Parte 4: Cabeceras de Seguridad

### Añadir en /var/www/html/.htaccess

Header set X-Frame-Options "DENY"
Header set X-Content-Type-Options "nosniff"
Header set X-XSS-Protection "1; mode=block"
Header set Referrer-Policy "no-referrer-when-downgrade"
Header set Permissions-Policy "geolocation=()"

## Parte 5: Redirecciones HTTP

### Redirección permanente (301)

Redirect 301 /viejo.html /nuevo.html
Temporal (302):
Redirect 302 /promo /promo-2026

## Parte 6: Reescritura de URLs con mod_rewrite

### Activación del módulo

### Activar el módulo responsable de la reescritura

sudo a2enmod rewrite
sudo systemctl restart apache2


### Requisito en el VirtualHost

### Dentro del VirtualHost debe estar permitido el uso de .htaccess. En el bloque <Directory> debe existir:

AllowOverride All

### Ejemplo simple: eliminar extensión

### Crear un archivo hola.html en el DocumentRoot (/var/www/html/)

### Crear o editar el .htaccess en el mismo directorio con

RewriteEngine On
RewriteRule ^hola$ hola.html [L]

### Prueba en el navegador:

http://IP_DEL_SERVIDOR/hola

### Aunque el archivo real sea hola.html, el navegador verá una URL sin extensión.

### En el .htaccess del DocumentRoot:

RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.+)$ index.php?route=$1 [L,QSA]

### Crear un archivo index.php con:

<?php
echo "Ruta solicitada: " . ($_GET['route'] ?? 'ninguna');
?>

### Acceder desde el navegador a distintas rutas:

http://IP_DEL_SERVIDOR/productos
http://IP_DEL_SERVIDOR/usuarios/editar/7
http://IP_DEL_SERVIDOR/pepito

### Salida que se espera:

Ruta solicitada: productos
Ruta solicitada: usuarios/editar/7
Ruta solicitada: pepito

## Parte 7: Control del Listado de Directorios

### Para evitar que se muestren archivos:

Options -Indexes

## Parte 8: Forzar HTTPS

### Añadir:

RewriteEngine On
RewriteCond %{HTTPS} !=on
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

### Requiere certificado SSL configurado.

## Parte 9: Cacheo y Compresión

### Cache de archivos (si mod_expires activado)

<IfModule mod_expires.c>
   ExpiresActive On
   ExpiresByType image/jpeg "access plus 30 days"
   ExpiresByType text/css "access plus 7 days"
   ExpiresByType application/javascript "access plus 7 days"
</IfModule>

### Compresión (si mod_deflate activado)

SetOutputFilter DEFLATE

## Parte 10: Interacción con Buscadores (SEO y Indexación)

### Crear en raíz del sitio:

robots.txt

### Y dentro del archivo:

User-agent: *
Disallow: /admin/
Disallow: /backup/

### Evitar indexación con HTTP (X-Robots-Tag)
### En .htaccess:

Header set X-Robots-Tag "noindex, nofollow"

### Bloquear bots por User-Agent

RewriteEngine On
RewriteCond %{HTTP_USER_AGENT} googlebot [NC]
RewriteRule ^ - [R=403]

## Parte 11: Ocultación y Restricción de Carpetas

### Impedir acceso web a una carpeta:

### Dentro de la carpeta crear .htaccess:

Require all denied

### O por archivo:

<FilesMatch "\.(sql|bak|zip|tar)$">
   Require all denied
</FilesMatch>

### Restringir por IP:

Require ip 192.168.1.0/24

### Evitar ejecución de PHP en carpetas de subida:

<FilesMatch "\.(php|phtml)$">
   Require all denied
</FilesMatch>