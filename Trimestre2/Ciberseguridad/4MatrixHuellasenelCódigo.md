# 4. [HACKING SERVICIOS] - Matrix: Huellas en el Código

## FASE 1 — Generación de identidades (crear usuarios)

### Creamos tres usuarios sin privilegios y uno con ellos
sudo adduser trinity
sudo adduser apoc
sudo adduser switch
sudo adduser neo

sudo usermod -aG sudo neo

## FASE 2 — Crear grupos: Escuadrones de la Resistencia

## Creamos dos grupos y les asignamos los usuarios
sudo groupadd zion
sudo groupadd matrix

sudo usermod -aG zion neo
sudo usermod -aG zion trinity

sudo usermod -aG matrix neo
sudo usermod -aG matrix apoc
sudo usermod -aG matrix switch

## FASE 3 — Estructura de directorios: Los Distritos de Matrix

### Crearemos tres carpetas diferentes
1 (/mission-data) -> Para el grupo zion
2 (/simulacion) -> Recibira archivos falsos del "mundo simulado", con acceso libre para matrix y solo lectura para zion
3 (/backdoor) -> Solo para neo

sudo mkdir /mission-data
sudo mkdir /simulacion
sudo mkdir /backdoor

sudo chown :zion /mission-data
sudo chmod 770 /mission-data

sudo chown :matrix /simulacion
sudo chmod 775 /simulacion

sudo chown neo:neo /backdoor
sudo chmod 700 /backdoor

## FASE 4 — Pruebas de acceso: El Oráculo y los obstáculos

## Para poder entrar en /mission-data
ls -ld /mission-data
Lo comprobamos con (Solo debería valer con zion):
touch /mission-data/prueba.txt

## Para poder entrar en /mission-data
ls -ld /simulacion
En matrix no hay problema y en zion solo leer
touch /simulacion/fake.txt

## Para poder entrar en /backdoor
ls -ld /backdoor
Si hacemos este comando con los de neo debería denegarlos

## FASE 5 — Mini misión final: Neutralizar al Agente Smith

### Creamos un usuario smith fuera de todo
sudo adduser smith
sudo chmod 700 /home/smith
Con el ls -ld nos daría algo como esto: drwx------

## FASE 6 — La lluvia de código (logs en tiempo real)

### Veremos los mensajes del sistema en tiempo real
sudo journalctl -f

### Al abrir con su -trinity y luego exit veremos:
session opened for user trinity
session closed for user trinity
Y posiblemente algún fallo con PAM si hay algún tipo de error

## FASE 7 — El rastro de entrada y salida

¿Trinity accedió ayer? Se utilizaría el comando last
¿Apoc intentó entrar varias veces y falló? Usaremos: sudo zgrep -i "failed" /var/log/auth.log*
¿Smith existe en los logs aunque no debería moverse por el sistema? Se volvería a usar: sudo zgrep -i "failed" /var/log/auth.log*

## FASE 8 — Sudo: quién ha manipulado Matrix

### Ver qué comandos se han ejecutado con sudo
sudo journalctl -u sudo

### Identificar el usuario que los ejecutó
Para neo: sudo journalctl -u sudo | grep neo
Para smith: sudo journalctl -u sudo | grep neo

### Detectar intentos de sudo fallidos
sudo journalctl -u sudo | grep -i fail

### BONUS
sudo journalctl | grep sudo
Esto es una mezcla para poder sacar los errores, el uso correcto, intencion de elevacion y cambios de sesion

## FASE 9 — Huellas en el historial: El Código dejado atrás

### ¿Quién creó un archivo dentro de /simulacion fuera de su hora de trabajo?
ls -l --time-style=full-iso /simulacion

### ¿Quién intentó entrar a /backdoor aunque no tenía permisos?
sudo grep -i backdoor /home/*/.bash_history

### ¿Qué usuario ejecutó comandos sospechosos como sudo su?
sudo journalctl -u sudo | grep -i "sudo su"

### ¿Acaso Smith intentó copiar algo desde mission-data?
sudo grep -i mission-data /home/smith/.bash_history

## FASE 10 — Localizar la intrusión: reconstrucción forense

### Localizar esos eventos
sudo zgrep -i "failed password" /var/log/auth.log*

### Determinar qué usuario lo hizo
sudo zgrep -i "failed password" /var/log/auth.log* | awk '{print $(NF-5)}'

### Extraer las líneas exactas de log donde ocurrió
sudo zgrep -i "failed password" /var/log/auth.log* | awk '{print $1, $2, $3}'
