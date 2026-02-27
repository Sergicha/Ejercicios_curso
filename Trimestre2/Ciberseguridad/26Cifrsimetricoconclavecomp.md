# 26. Cifrado Simetrico con Clave Compartida - Juan Luis Bermejo

## FASE 1 - PREPARAR EL SISTEMA

### Verificar módulo criptográfico OpenSSL.

openssl version

### Esta es mi versión y lo que me devuelve

openSSL 3.0.13 30 Jan 2024

## FASE 2 - CREAR MENSAJE SENSIBLE

### Simular credenciales del sistema de la nave.

echo "Credenciales núcleo warp: acceso nivel comandante." > mensaje.txt
cat mensaje.txt

Esto nos devuelve el mensaje normal, eso significa que no va cifrado

## FASE 3 - GENERAR CLAVE SEGURA

### Generar clave criptográfica:

openssl rand -base64 32

Esto es lo que me devuelve:

I2r8GhtaVuKoWDCDOnG8ps/wZGiKcRV5e/EfwkrOzuI=

Guardamos la clave dentro del gestor seguro no en el archivo

## FASE 4 - CIFRADO AES-256-CBC

### Activar cifrado simétrico

openssl enc -aes-256-cbc -salt -in mensaje.txt -out mensaje.enc

### Comprobamos el resultado:

cat mensaje.enc

### Este es el resultado:

Salted__G(+-��a|�r�]IA�ћ{�l��Ad��AsxQ�\T&U_�Hη��,~�-�ŷX{u      [��G�k�j


## FASE 5 - INTERCEPCIÓN ENEMIGA


### Simular intento de descifrado sin clave correcta.

openssl enc -aes-256-cbc -d -in mensaje.enc

### Lo que nos devuelve:

bad decrypt
4077FAFBDC750000:error:1C800064:Provider routines:ossl_cipher_unpadblock:bad decrypt:../providers/implementations/ciphers/ciphercommon_block.c:124:
28��brc�����������**JL̳]2�
                        ���H%␦~ݣ�_b���

Esto nos deja claro que sin la contraseña, es prácticamente imposible recuperar los datos

## FASE 6 - DESCIFRADO AUTORIZADO

### Recuperar mensaje con clave correcta.

openssl enc -aes-256-cbc -d -in mensaje.enc -out mensaje_recuperado.txt
diff mensaje.txt mensaje_recuperado.txt

### Si en esto hacemos:

cat mensaje_recuperado.txt

### Lo que nos devolverá será esto:

Credenciales núcleo warp: acceso nivel comandante.


## FASE 7 - CIFRADO MODERNO (AES-GCM)

openssl enc -aes-256-gcm -salt -in mensaje.txt -out mensaje_gcm.enc

### Lo que nos devuleve es lo siguiente:

enc: AEAD ciphers not supported