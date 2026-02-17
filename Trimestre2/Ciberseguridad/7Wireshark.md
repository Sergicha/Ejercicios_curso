# 7. [Analisis Forense] - ¿Qué es Wireshark?

## Práctica: Análisis de Tráfico HTTP con Wireshark en una Web Vulnerable

## Inicio
Abrir Wireshark
Buscar en internet: http://testphp.vulnweb.com/
Hay que realizar muchas acciones para que wireshark las pueda capturar

## Analisis guiado en Wireshark

### Para poder visualizar el tráfico HTTP
http

### Listar las peticiones que son GET
http.request.method == "GET"

### Detectamos las peticiones como parámetros
http.request.uri contains "="

### Analizamos los login con peticiones POST
http.request.method == "POST"

### Observar las cookies y sesiones
http.cookie

### Observar los errores de la web
http.response.code >= 400

### Para poder visualizar recursos concretos
Para imagenes JPG: http.request.uri contains ".jpg"
Para scripts: http.request.uri contains ".js"
Para hojas de estilo: http.request.uri contains ".css"

### Poder seguir una conversación completo
1º Paso seleccionar un paquete
2º Le damos a follow
3º Pulsamos HTTP Stream
Así podremos observar la conversación completa entre cliente y servidor

## Cuestionario final

### Lista todos los parámetros GET que aparecieron durante tu navegación
Usado para listar categorias, cat:
/listproducts.php?cat=1
/listproducts.php?cat=2

Usado para mostrar productos, pic:
/product.php?pic=3

Usado para mostrar información de artistas, artist:
/artist.php?artist=4

Usado para añadir productos al carrito, add:
/artist.php?artist=4

Parámetro de prueba en login, test:
/login.php?test=1

### ¿Qué peticiones POST detectaste? ¿Qué información enviaron?
Una de las que se detecto fue el formulario del login
POST /userinfo.php HTTP/1.1
Content-Type: application/x-www-form-urlencoded

### ¿Cómo viajan las credenciales del formulario de login?
Viajan en texto plano, sin tener nada de cifrado. Ni en la contraseña ni en el nombre de usuario, por lo que cualquiera que ataque puede leerlas sin dificultad

### ¿Qué cookies o identificadores de sesión aparecen durante la captura?
Se detecto las de PHPSESSID, el cual viaja también sin cifrado alguno por lo que se pueden hacer ataques de secuestración de inicio de sesión

### ¿Puedes reconstruir qué páginas visitó el usuario? Describe el flujo.
El acceso principal fue: /index.php
Luego navegamos por las categorías: /listproducts.php?cat=1
Visualizamos los productos: /product.php?pic=3
Hacemos una consulta de artistas: /artist.php?artist=4
Añadimos los productos al carrito: /shoppingcart.php?add=2
Veremos el formulario del login: /login.php
Se envia el formulaio: POST /userinfo.php

### ¿Has encontrado algún error HTTP (404, 500…)? ¿En qué rutas?
Sí, cuando he aplicado el filtro: http.response.code >= 400
Se puso el clásico error de 404 Not Found

### Explica qué riesgos de seguridad existen al usar HTTP en lugar de HTTPS.
El usar el HTTP en vez de HTTPS, implica graves riesgos de seguridad, por los siguientes motivos:
1. Toda la información viaja en texto plano
2. Las cookies pueden ser robadas
3. Un atacante puede espiar, modificar o inyectar tráfico
4. Se pueden seguir todos los pasos de la navegación del usuario
5. Es más fácil acceder un robo de identidad
