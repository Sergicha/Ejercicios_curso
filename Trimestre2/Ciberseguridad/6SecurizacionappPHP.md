# 6. [Desarrollo Seguro] - Securización de app PHP

## Parte 1

### Preparacion

Lo primero de todo es que necesitamos tener instalado wireshark, un navegador web y la url de nuestra aplicación
Abriremos Wireshark, elegimos en nuestro caso wlan0 por estar conectados mediante WiFi y aplicamos un filtro antes de capturar para que no nos sobrepasemos: tcp port 80 or tcp port 443

### Capturar tráfico

Para comenzar entramos en el Wireshark, luego seleccionamos interfaz y el boton de capturar y lo dejamos capturando información.
Generaremos un poco de tráfico desde nuestra app de PHP entrando en el login y cometiendo un error y acertando en otra ocasión, además de editar cosas para el CRUD. 

### Filtrado y análisis del tráfico PHP

Ponemos como filtro el http y nos aparecerán las peticiones:
No.	   Source	        Destination       Protocol	            Info
15	192.168.1.10    	192.168.1.20	    HTTP	    GET /login.php HTTP/1.1
16	192.168.1.20    	192.168.1.10	    HTTP	    HTTP/1.1 200 OK
18	192.168.1.10    	192.168.1.20	    HTTP	    GET /style.css HTTP/1.1
19	192.168.1.20    	192.168.1.10	    HTTP	    HTTP/1.1 200 OK
21	192.168.1.10    	192.168.1.20	    HTTP	    GET /script.js HTTP/1.1
22	192.168.1.20    	192.168.1.10	    HTTP	    HTTP/1.1 200 OK
30	192.168.1.10    	192.168.1.20	    HTTP	    POST /login.php HTTP/1.1
31	192.168.1.20    	192.168.1.10	    HTTP	    HTTP/1.1 302 Found
35	192.168.1.10    	192.168.1.20	    HTTP	    GET /dashboard.php HTTP/1.1
36	192.168.1.20    	192.168.1.10	    HTTP	    HTTP/1.1 200 OK

### Análisis login

Para poder llegar al filtro preciso del POST se usa: http.request.method == "POST"
Y para poder abrir el contenido del POST entramos en Hypertext Transfer Protocol y luego Form-urlencoded, en mi caso sale 
username=sergio
password=Abcd1234.

### Análisis cookies y sesión

El filtro que use para poder buscar las cookies es: http.cookie
¿La cookie tiene flags Secure, HttpOnly, SameSite?
No, no tiene ninguno de los flags y esto es una gran vulnerabilidad a corregir de la página.
¿Se envía en la misma petición del login?
No, se envia el GET 
¿Se reutiliza durante toda la sesión?
Si suele reutilizarse PHPSESSID por el navegador en cada petición durante toda la sesión.
¿Podríais robarla si el tráfico no fuese HTTPS?
Si se podria por el nivel de seguridad que da el protocolo HTTP.

### Identificación de servidor y tecnologías mediante tráfico

Con el filtro: http.server y http.response
Encontraremos cosas como esta: Server: 
Apache/2.4.54
X-Powered-By: PHP/8.1.12
Es filtrado de información sensible

### Detector patrones de ataque

Desde otra maquina filtramos errores con: http.response.code >= 400
¿Qué tipo de errores aparecen más?
Los errores 404, 403 y 500 suelen ser los más comunes
¿Alguna IP destaca como “sospechosa”?
Si la 192.168.1.35, la cual es la que más peticiones erroneas tenía
¿Puedes reconstruir qué intentaba hacer ese cliente?
Si, intento acceder a /admin, luego intento /backup, probó /phpmyadmin y realizo múltiples posts a /login.php
