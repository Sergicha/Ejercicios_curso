# 1 - [Análisis Forense] - TRÁFICO HTTP

## Cargar el PCAP y análisis general del tráfico

### ¿Cuántos paquetes contiene el PCAP?

Se pueden observar un total de 6 paquetes, 3 que son de peticiones GET y 3 respuestas HTTP

### ¿Entre qué direcciones IP se produce la comunicación?  

Entre el 192.168.1.60 (cliente) y el 203.113.20.48 (servidor), los cuales tienen de puertos de origen 12345, 12346 y 12347, y el puerto de destino es el 80 HTTP.

## Identificar las tres conversaciones HTTP

### Indica qué puertos de origen (del cliente) corresponden a cada petición GET.

12345 -> 200 OK
12346 -> 404 Not Found
12347 -> 500 Internal Server Error

## Conversación 1 – Respuesta 200 OK

### ¿Qué tipo de contenido (`Content-Type`) devuelve el servidor?

Devuelve una URL completa de una página html: http://www.example.local/index.html, en esta linea se puede ver claro que es un archivo html: Content-Type: text/html; charset=UTF-8

### ¿El HTML devuelto es completo o parcial?

Es uno totalmente completo básico con una estructura cerrada: <html><body>Pagina OK</body></html>

## Conversación 2 – Respuesta 404 Not Found

### ¿Qué recurso intentaba solicitar el cliente?

Estaba intentando entrar en un archivo que no existe: GET /noexiste.html

### ¿Qué mensaje proporciona el servidor al usuario en el cuerpo de la respuesta?

Nos da este mensaje: <html><body>Error 404: Recurso no encontrado</body></html>
El cual es un error que trata de decirnos que el servidor funciona correctamente, pero algo del recurso solicitado no existe, suele ser un error del lado del cliente.

## Conversación 3 – Respuesta 500 Internal Server Error

### ¿Qué ruta intenta acceder el cliente? 

La ruta que solicita es: GET /causar_error

### ¿Cuál es la causa general de un error **500** en un servidor web? 

El error 500 nos trata de decir dos cosas, que el servidor recibió la petición, pero por algún motivo ocurrio un fallo interno, suele ser por error en código PHP, error en base de datos, mala configuración...
Nos devuelve: <html><body>Error 500: Falla interna del servidor</body></html>, el codigo 500, un mini mensaje que nos explica y un HTML muy simple.

## Análisis técnico del comportamiento del servidor

### ¿Cuáles son las diferencias más relevantes entre las cabeceras del servidor para los códigos 200, 404 y 500?  

En el server y content-type no hay cambios.
Las diferencias principales son:
Código      Content-length      Date
200               46          10:00:00
404               58          10:00:05
500               60          10:00:10

## Reflexión final

### Reconstruir la actividad de un usuario en la web.

El analista podrá ver las URLs que visitó, saber a que intentó acceder, saber si hizo alguna exploración sospechosa o no y ordenar las coasas por el timepo.

### Determinar fallos de configuración en un servidor.  

Nos revelará los errores internos del servidor, las malas gestiones que se puedan haber hecho y que podría estar filtrando información posible

### Identificar rutas sensibles o errores inesperados.

Las posibles rutas sensibles o errores inesperados son: /causar_error, /noexiste.html, las cuales nos podrían indicar la enumeración de rutas, el test de vulnerabilidades y fuzzing.