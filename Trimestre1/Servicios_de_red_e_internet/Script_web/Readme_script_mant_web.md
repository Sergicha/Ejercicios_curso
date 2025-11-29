# Para hecer que funcione el script de forma diaria

## Crontab 

Crontab es una servicio de ubuntu/devian para hacer que se ejecuten cosas de forma periodica

### Entrar en crontab

sudo crontab -e

### Añadir la nueva regla a crontab para que lo ejecute cada dia

0 0 * * * /ruta/backup.sh

## Darle permisos al script

sudo chmod +x /var/www/copias_de_seguridad/backup.sh

## Aun queda poder que pueda entrar en mysql

### crear un nano que guarde la contraseña de root

sudo nano /root/.my.cnf

### copia esto dentro

[client]
user=root
password=TU_PASSWORD

### y dale permisos

sudo chmod 600 /root/.my.cnf

#!/bin/bash

carpeta_backups="/var/www/copias_de_seguridad"
nombre_db="test"

date=$(date +%Y%m%d_%H%M%S)

copia_db="${nombre_db}_${date}.sql.gz"
copia_html="web_html_${date}.tar.gz"

# Crear carpeta si no existe
mkdir -p "$carpeta_backups"

# Backup web
tar -czf "${carpeta_backups}/${copia_html}" /var/www/html

# Backup base de datos
mysqldump "$nombre_db" | gzip > "${carpeta_backups}/${copia_db}"