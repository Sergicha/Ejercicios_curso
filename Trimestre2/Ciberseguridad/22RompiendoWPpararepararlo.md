# 22. [Hacking Etico] Rompiendo WordPress para Aprender a Repararlo - Juan Luis Bermejo

## 1 Requisitos previos

## 1.1 Verificar que Docker está instalado

docker --version docker ps

### Si el comando anterior da error de permiso, tienes que añadir tu usuario al grupo de docker:
 
sudo usermod -aG docker $USER 
newgrp docker 
docker ps

## 2 Crear la red para comunicar contenedores

### Creamos la red llamada wp-net para que WP encuentra MySQL, la cual nos dará todas las networks

docker network create wp-net 
docker network ls

## 3 Crear volúmenes para persistencia

### Guardamos los datos de MySQL y archivos WP, los cuales nos devolvera dos volumenres

docker volume create wp-db 
docker volume create wp-html 
docker volume ls

## 4 Levantar la base de datos (MySQL)

## 4.1 Ejecutar MySQL con variables de entorno, creando un contenedor llamado wp-mysql:

docker run -d \
  --name wp-mysql \
  --network wp-net \
  -v wp-db:/var/lib/mysql \
  -e MYSQL_DATABASE=wordpress \
  -e MYSQL_USER=wpuser \
  -e MYSQL_PASSWORD=wp-pass-123 \
  -e MYSQL_ROOT_PASSWORD=root-pass-123 \
  mysql:8.0

## 4.2 Comprobar que está vivo

docker ps 
docker logs wp-mysql --tail 30

### Esto es lo que devuelve:

2026-02-22T05:39:21.059758Z 0 [System] [MY-034468] [Server] X Plugin ready for connections. Socket: /var/run/mysqld/mysqlx.sock
2026-02-22T05:39:21.059436Z 0 [System] [MY-030752] [Server] /usr/sbin/mysqld: ready for connections. Version: '8.0.45'  socket: '/var/run/mysqld/mysqld.sock'  port: 0  MySQL Community Server - GPL.

## 5 Levantar WordPress

## 5.1 Ejecutar WordPress apuntando a la DB

### Creando el contenedor wp-web y publicandolo en el puerto 8080:

docker run -d \
  --name wp-web \
  --network wp-net \
  -p 8080:80 \
  -v wp-html:/var/www/html \
  -e WORDPRESS_DB_HOST=wp-mysql:3306 \
  -e WORDPRESS_DB_NAME=wordpress \
  -e WORDPRESS_DB_USER=wpuser \
  -e WORDPRESS_DB_PASSWORD=wp-pass-123 \
  wordpress:latest

## 5.2 Verificar logs y nos devuelva el successfully copied en /var/www/html

docker logs wp-web --tail 30

## 6 Acceso por navegador y asistente de instalación

### Abrir nuestra página en google poniendo en el navegador:

http://192.168.1.145:8080

### Completa todo el instalador:

Le ponemos su idioma, el título, usuario admin, password y el email

## 7 Verificación técnica (para alumnos curiosos)

## 7.1 Ver la red y quién está conectado, la cual nos devolverá un .json con toda la info de la red

docker network inspect wp-net

## 7.2 Ver volúmenes y dónde se usan

docker inspect wp-mysql | grep -i mount -n docker inspect wp-web | grep -i mount -n

## 8 Parar, arrancar y reiniciar (operaciones típicas)

## 8.1 Parar todo, lo cual apaga todo de verdad

docker stop wp-web wp-mysql

## 8.2 Arrancar todo, lo cual enciende todo de verdad

docker start wp-mysql wp-web

## 8.3 Reiniciar WordPress, lo cual reinicia todo de verdad

docker restart wp-web

## 9 Limpieza (sin perder datos)

## 9.1 Borrar contenedores

docker rm -f wp-web wp-mysql

## 10 Limpieza total (borrado completo)

### Esto sirve para eliminar la web y la base de datos.

docker rm -f wp-web wp-mysql 2>/dev/null docker volume rm wp-db wp-html 2>/dev/null docker network rm wp-net 2>/dev/null

### Para comprobarlo ponemos:

docker ps 

### Y no nos devuelve nada, es un docker completamente vacio