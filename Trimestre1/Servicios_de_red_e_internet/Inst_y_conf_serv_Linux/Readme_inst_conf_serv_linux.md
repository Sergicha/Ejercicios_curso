# Guía de Instalación y Configuración de un Servidor Web LAMP en Ubuntu Server de Juan Luis Bermejo y Sergio Chamba

##  1. Sistema operativo:

## Se instala el .iso de Ubuntu Server

En este caso se le ha puesto el usuario juaro

------------------------------------------------------------------------------------------------

## 2. Configuración de acceso remoto (SSH)

## Instalar el servicio SSH

sudo apt install openssh-server

### Para verificar el estado del servicio se usa el comando

sudo systemctl status ssh

## Debe mostrarse como active

### Obtener la IP del servidor

ip a 

### Conexión desde cliente (Windows, Linux o macOS)

Desde un equipo externo se intenta hacer la conexion abriendo la terminal y escribiendo el siguiente comando

ssh 'usuario'@'IP_DEL_SERVIDOR'

### Si es un entorno cerrado probablemente sea una ip  de la red 192.168.1.0
------------------------------------------------------------------------

## 3. Instalación y configuración del servidor web Apache2

### Instalar Apache2 con el comando 

sudo apt install apache2 -y

## -y se una para que a las preguntas se responda sí

### Habilitar e iniciar el servicio con los comandos 

sudo systemctl enable apache2

sudo systemctl start apache2

### Verificar instalación

Abre un navegador y accede a:

http://'IP_DEL_SERVIDOR'

Deberías ver la página de bienvenida de Apache2.

## Si no muestra la pagina de bienvenida

Lo mas comun es que sea por una ip erronea o el firewall de la red o del mismo navegador, chrome suele dar problemas.
En caso de fallo revisa la ip y cambia de navegador.


------------------------------------------------------------------------

## 🗄️ 4. Instalación y configuración de MySQL

### Instalar MySQL Server

sudo apt install mysql-server -y

### Asegurar la instalación

sudo mysql_secure_installation

Durante el secure instalation te hara una serie de preguntas.
Respondalas segun vea necesario o no.

### Crear un usuario y base de datos

Accede al cliente de MySQL:

sudo mysql -u root -p

Dentro del prompt de MySQL:

CREATE DATABASE ejemplo;
CREATE USER 'usuario'@'%' IDENTIFIED BY 'ContraseñaSegura123!';
GRANT ALL PRIVILEGES ON ejemplo_db.* TO 'usuario'@'%';
FLUSH PRIVILEGES;
EXIT;

### Permitir acceso remoto a MySQL

Editar el archivo de configuración:

sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf

Modificar la línea:

bind-address = 127.0.0.1

para que sea 

bind-address = 0.0.0.0

# Así el usuario se podra conectar desde cualquier ip

Reiniciar el servicio:

sudo systemctl restart mysql

### Conectar desde MySQL Workbench

Usa la IP del servidor, el puerto `3306`, el usuario y la contraseña
configurados.

------------------------------------------------------------------------

## 5. Instalación y configuración de VSFTPD

### Instalar el servidor FTP

sudo apt install vsftpd -y

### Lo siguiente que tenemos que hacer es modificar el archivo de configuración original. Se edita el archivo

sudo nano /etc/vsftpd.conf

# Copie la siguiente configuración en el archivo vsftpd.conf , guarda y cierra el archivo.

listen=NO
listen_ipv6=YES
anonymous_enable=NO
local_enable=YES
write_enable=YES
local_umask=022
dirmessage_enable=YES
use_localtime=YES
xferlog_enable=YES
connect_from_port_20=YES
chroot_local_user=YES
secure_chroot_dir=/var/run/vsftpd/empty
pam_service_name=vsftpd
rsa_cert_file=/etc/ssl/certs/ssl-cert-snakeoil.pem
rsa_private_key_file=/etc/ssl/private/ssl-cert-snakeoil.key
ssl_enable=NO
pasv_enable=Yes
pasv_min_port=10000
pasv_max_port=10100
allow_writeable_chroot=YES
sudo ufw allow from any to any port 20,21,10000:10100 proto tcp
sudo systemctl restart vsftpd

## Instalacio de UFW para abrir algunos puertos que estan cerrados por defecto

sudo apt install ufw -y

## Comprobar el estatus cuando acabe la istalacion

sudo ufw status

## si responde con 'status: inactive' escriba el comando 

sudo ufw enable 

## escriba el siguiente comando para abrir los puertos necesarios

sudo ufw allow from any to any port 20,21,80,3306,10000:10100 proto tcp

## Para acabar reinicie el servicio

sudo systemctl restart vsftpd

## Creamos un usuario 

sudo useradd -m ftpuser
sudo passwd ftpuser

# -m hace que cree una carpeta para el usuario
# Por ultimo le damos permisos al resto de usuarios 

sudo chmod -R 775 www

------------------------------------------------------------------------

## 6. Instalación de PHP y prueba de funcionamiento

### Instalar PHP

sudo apt install php libapache2-mod-php php-mysql -y

### Para comprobar que ha sido instalado y la version instalada 

php -v

### Crear archivo de prueba

sudo nano /var/www/html/info.php

Agregar el siguiente contenido:

php
<?php
phpinfo();
?>


### Probar en el navegador

Accede a:

    http://IP_DEL_SERVIDOR/info.php

Deberia mostrar una página con la información del sistema PHP.

------------------------------------------------------------------------

## 7 Conexion remota desde VSCode mediante ssh

### Verifica que esté activo en el host

sudo systemctl status ssh


# En el cliente que tiene el visual studio code


## Descarga la extension 'Remote - SSH'

### Seleccione el botón de abrir conexión remota en la esquina izquierda inferior.
### Despues en el cuadro de opciones selecciona, connect to host.

### Añada una nueva conecxion
### Introduzca un nombre y el sistema operativo del host (si es que lo pregunta)

### En la nueva ventana que se abre introdusca los datos de la conexion

Host Mi_conexion
 HostName ip_server
 User ubuntu

# La conexion deberia estar completa
------------------------------------------------------------------------