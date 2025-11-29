# Snap: el súperpoder de instalar cosas sin romper el sistema (casi siempre) Sergio Chamba
# 1. Instalar snap 
Lo primero sera actualizar la maquina: sudo apt update && sudo apt -y upgrade
Lo instalaremos con el siguiente comando en ubuntu: sudo apt -y install snapd
# También descargaremos wekan para mas adelante con snap
sudo snap install wekan

# 2. Fijar el puerto y la URL raíz
Elegiremos un puerto librey fijamos la root_url con la ip: sudo snap set wekan port='3001'
sudo snap set wekan root_url="http://192.168.1.123:3001"

# 3. Reiniciar los servidores de snap
Despues de una modificacion en la configuracion reiniciaremos los servicios Wekan y Mongo DB
sudo systemctl restart snap.wekan.mongodb
sudo systemctl restart snap.wekan.wekan

# 4. Abrir el firewall si usas UFW
Si tenemos activo el UFW debemos darle los permisos al puerto:
sudo ufw allow 3001/tcp
sudo ufw reload

# 5. Comprobaciones rápidas
Comprobamos los estados de los servicios:
systemctl status snap.wekan.wekan --no-pager
systemctl status snap.wekan.mongodb --no-pager

Verificamos que el puerto este activo, aparecera LISTEN si funciona correctamente:
ss -tunelp | grep 3001

# 6. Accedemos desde nuestro navegador
http://192.168.1.123:3001

Nos pedira crear la cuenta de administrador si es la primera vez que ingresamos