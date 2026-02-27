# 27. Cifrado Asimétrico con Clave Pública y Privada

## 1 Identidad Criptográfica

### 1.1 Crear la carpeta de la misión
mkdir -p mision_canal_seguro
cd mision_canal_seguro

### 1.2 Generar clave privada del oficial Bob (2048 bits)

openssl genrsa -out bob_privada.pem 2048

Esta es la comprobación:

openssl rsa -in bob_privada.pem -check -noout

Nos devuelve:

***RSA key ok***

### 1.3 Obtener la clave pública de Bob

openssl rsa -in bob_privada.pem -pubout -out bob_publica.pem

Nos devuelve:

***writing RSA key***

Para que podamos observarla:

openssl rsa -pubin -in bob_publica.pem -text -noout

Nos aparece:

***Public-Key: (2048 bit)

Modulus:
    00:94:41:81:ee:0a:7a:bf:a5:3d:f2:c4:ea:c1:89:
    c4:b2:07:9b:e7:be:fb:9e:6f:61:05:06:f8:44:d8:
    03:c9:34:23:01:ff:58:fd:5f:7d:b1:d5:e1:4c:41:
    7a:83:68:ee:28:3f:fc:ea:80:27:7a:8f:18:fb:8a:
    47:0a:63:6f:f4:24:4a:4c:8e:6c:7e:b2:0c:2b:8e:
    78:5d:82:ba:0c:b2:75:f4:71:a1:f4:cd:65:c1:50:
    4e:79:5c:46:f5:04:49:ff:73:97:9a:85:00:19:37:
    7c:0c:e4:85:f7:9f:1a:9c:ce:c4:6b:31:04:13:66:
    7f:c4:60:42:73:49:e5:1c:55:3d:5b:9a:06:59:39:
    9c:07:25:be:03:f4:41:6d:0b:2e:fe:f0:ee:60:b6:
    a8:a9:af:e6:f4:aa:7a:2a:2a:0d:3b:d9:84:fd:a7:
    ac:a5:d1:86:e1:7d:be:cf:7b:39:ce:d0:80:66:cf:
    44:c6:97:cc:e5:fc:8d:4b:e8:39:4a:4e:23:12:56:
    0e:98:fe:fa:bc:2b:34:4f:6f:84:64:91:e8:9c:19:
    69:f0:a6:f1:e2:fd:cb:28:6d:70:91:3c:6a:7d:a6:
    b9:10:f1:d4:95:2b:b9:fb:e4:bf:6e:d7:d0:e4:f7:
    32:bd:b2:ab:cc:41:39:e0:03:5b:50:d4:61:fa:10:
    96:fd
Exponent: 65537 (0x10001)***

## 2 CIFRADO CON CLAVE PÚBLICAç

### 2.1 Crear mensaje secreto de Alice

echo "Coordenadas del punto de encuentro: Sector 7G. Acceso nivel Alfa." > mensaje_alice.txt
cat mensaje_alice.txt

### 2.2 Cifrar para Bob usando su clave pública

Ponemos un mensaje corto.

openssl pkeyutl -encrypt -pubin -inkey bob_publica.pem -in mensaje_alice.txt -out mensaje_para_bob.bin

Nos aseguramos de que se pueda leer:

xxd mensaje_para_bob.bin | head

Este es el resultado:
	00000000: 833c 5114 5d80 0f86 0c32 efa3 f4ee 8ab4  .<Q.]....2......
	00000010: a5b5 fb9d 75bd 1068 538f 1792 2b09 1938  ....u..hS...+..8
	00000020: ad7a b6d9 1add cb58 be82 7f87 7b15 f4a9  .z.....X....{...
	00000030: 8020 a7d9 5cac 9806 3287 15f5 42f0 9eb3  . ..\...2...B...
	00000040: eb60 1311 5e5d a142 a1e7 51de 6d30 f0ad  .`..^].B..Q.m0..
	0000050: beb2 c72a 88de 47d5 4048 58c4 14c8 516c  .....G.@HX...Ql
	00000060: b313 24a8 5a00 d4d1 0188 a12e 9346 b49f  ..$.Z........F..
	00000070: 11d4 bb1b 7aba 8198 2c00 2d1e d739 9c8b  ....z...,.-..9..
	00000080: 6282 3f56 c4b3 c586 3824 30c6 1fe5 8aa8  b.?V....8$0.....
	00000090: dc8b 1dca d6ca f114 cefd 673d adc2 9586  ..........g=....

### 2.3 Descifrar con la clave privada de Bob

openssl pkeyutl -decrypt -inkey bob_privada.pem -in mensaje_para_bob.bin -out mensaje_descifrado.txt
cat mensaje_descifrado

Nos devuelve:
Coordenadas del punto de encuentro: Sector 7G. Acceso nivel Alfa.

## 3 FIRMA DIGITAL

### 3.1 Alice firma un mensaje

Alice generara sus claves:

openssl genrsa -out alice_privada.pem 2048
openssl rsa -in alice_privada.pem -pubout -out alice_publica.pem

El mensaje es este:
echo "Hola mundo" > orden.txt

### 3.2 Crear el hash y firmarlo

openssl dgst -sha256 -sign alice_privada.pem -out orden.firma orden.txt

### 3.3 Verificar la firma con la clave pública

openssl dgst -sha256 -verify alice_publica.pem -signature orden.firma orden.txt

Esto nos dará un ok, así:
Verified OK

### 3.4 Simular sabotaje

echo "Orden de misión: desactivar escudos y abrir bahía de carga." > orden.txt
openssl dgst -sha256 -verify alice_publica.pem -signature orden.firma orden.txt

Al cambiar el mensaje nos dara un fallo, algo como esto:
	Verification failure
	4097B983E5790000:error:02000068:rsa routines:ossl_rsa_verify:bad signature:../crypto/rsa/rsa_sign.c:430:
	4097B983E5790000:error:1C880004:Provider routines:rsa_verify:RSA lib:../providers/implementations/signature/rsa_sig.c:774:

## 4 CERTIFICADOS

### 4.1 Crear la CA de la Flota Estelar

La CA es la autoridad de certificación
Clave privada de la CA:
openssl genrsa -out starfleet_ca_privada.pem 4096
El certificado autofirmado de la CA (válido 365 días):
openssl req -x509 -new -key starfleet_ca_privada.pem -sha256 -days 365 -out starfleet_ca.crt \
-subj "/C=ES/O=Starfleet Academy/OU=Security Division/CN=Starfleet Root CA"
Para poder ver nuestro certificado haremos este comando:
openssl x509 -in starfleet_ca.crt -text-noout | head -n40
Y si esta bien no dará esto:
	-----BEGIN CERTIFICATE-----
	MIIFozCCA4ugAwIBAgIUYNEh56oG79xK3KzGru2Hb946GjAwDQYJKoZIhvcNAQEL
	BQAwYTELMAkGA1UEBhMCRVMxGjAYBgNVBAoMEVN0YXJmbGVldCBBY2FkZW15MRow
	GAYDVQQLDBFTZWN1cml0eSBEaXZpc2lvbjEaMBgGA1UEAwwRU3RhcmZsZWV0IFJv
	b3QgQ0EwHhcNMjYwMjIyMTkyNzEzWhcNMjcwMjIyMTkyNzEzWjBhMQswCQYDVQQG
	EwJFUzEaMBgGA1UECgwRU3RhcmZsZWV0IEFjYWRlbXkxGjAYBgNVBAsMEVNlY3Vy
	aXR5IERpdmlzaW9uMRowGAYDVQQDDBFTdGFyZmxlZXQgUm9vdCBDQTCCAiIwDQYJ
	KoZIhvcNAQEBBQADggIPADCCAgoCggIBAKcYkhc/xJfHr+dOyJdKRULjOrJtiD+x
	8IB9ZqtSwdAF+FYEpXog/mrV6pDyUrXEZYovbu+FHRcLf3kyKiRPJvI8rn5xxWoe
	6oL/UVYmIcOSUXP3OSl/h9AyPceRu1F+aA+wvwV9R9E/4KIyRiDT0XsosI8Mon+H
	mx5dZw7zMg6a+IR/T4CEWAgjzh20tECh1S86z1VdUmGMyENi2nJ1q5BpJoZTNTsF
	penwkZKJVomCnxYccCpyjeyClrSU6O4RFDtmFcVYLQmUMQBphXGMz4iax7JkQRNe
	8BWAMIZtemmcg0Zoev9kaIcwKlABFV2OTqPdCbXcdgbUdZR9RS6wYlEkqmG9KAGx
	LSKl5OtCN8C5k/fXRc27b/Y40Os1FAz9zEIMZ8YUze6u9it9qpLub6TQ1D7Q9Tle
	HMlwpO1urumCkOKnk5hZz5e0v/fkRp9dWVQXZ3lcXfA3MlVgAkSzAcSuMBOThnvL
	JxwpD9/6668hM7BO75za4Xhcvqgig70Du98Pegg6abblN09mhj/0sNXyMxidYQER
	zukns9glAwoQkXnylqZKCe4P7GT36FS9S+G8WrUr60zVcoJRGiL/dqM3Zd/Mh1PV
	E6ZzaO5pfzRfE8SPsApTar/yPTtLyG95gOuLFNV8XHkr8slbUxOt1f9KR9abeODO
	7YuQjOZAvak3AgMBAAGjUzBRMB0GA1UdDgQWBBSuy7YQXayHCuMENL2xdhiZQHMf
	BTAfBgNVHSMEGDAWgBSuy7YQXayHCuMENL2xdhiZQHMfBTAPBgNVHRMBAf8EBTAD
	AQH/MA0GCSqGSIb3DQEBCwUAA4ICAQAyNasew6fH/723oVb4NIvnYb6yi4oO552Y
	3BZeOht1EoQMmSXmiB3sWvkl2Jw1DE8cN6dLsVmOPyYqnfrepJkDzBNphp7IrKt/
	yLwpK/CL1uwU8D9C0PdkJ/8BoYw42i3m2Q/cURqqaMW64KtmNirIeoCylLoTiK3q
	JiUueA78ESsZfC4kzz7qdQNk2fNxN4IPOT0L7vl5UnKWOrSVeOkDGgPIeLBlXe2m
	PcebE0WLFYyklzbjiWauPA97rDK2VJl7Tjz0xW8MESO+NOzSfHwHyFh8RYtGIkNx
	UHKFIq7dTsn3MptTOsmIa+uRc/eOlDbDpIL8ZCmrr/UTq1J9gAp9EHX7/aFd5H4a
	aALLLhfQfAoMCjfSf6M2UAU1b3PdW7wrdoIstvGJaaRZiGo7hAiMW3wBEIUAKhib
	ZFW5ZBselAVp5nc15F34eEtKhyXD57nsyclGILQjk8OaxcOyiECrcykj2AhIfeQP
	BeicLOdbKyIaG+6O1SerSGxUSxGcjwAdkL6LPEhJkRU4cD1UUwj+9qi1E1mct/BZ
	Rp5fk2Nv0Hzf0W4k9bhQWD3KoIR1uVWlIMYAgdVJENLLuRSe0BG6KErBiXQ5UfxK
	w+lhdULLVZjUIxPHFG59t29nZZyiel4s0ucrmegeBjuTKtN9/inZO9e/khnnuGD+
	mqZFGFVf+g==
	-----END CERTIFICATE-----

### 4.2 Alice solicita un certificado (CSR)

El SCR es el Certificate Signing Request en inglés, lo cual significa petición de certificado

openssl req -x509 -new -key starfleet_ca_privada.pem -sha256 -days 365 -out starfleet_ca.crt \
-subj "/C=ES/O=Starfleet Academy/OU=Security Division/CN=Starfleet Root CA"

### 4.3 La CA firma el certificado de Alice

Hacemos una clave privada para Alice:

openssl genrsa -out alice_privada.pem 2048

Esto creara sus GSR

openssl req -new -key alice_privada.pem -out alice.csr \
-subj "/C=ES/O=Starfleet Academy/OU=Security Division/CN=Alice"

La CA que hemos hecho antes confirmara el certificado de Alice:

openssl x509 -req -in alice.csr -CA starfleet_ca.crt -CAkey starfleet_ca_privada.pem \
-CAcreateserial -out alice_cert.crt -days 365 -sha256

Nos dará esto:
Certificate request self-signature ok
subject=C = ES, O = Starfleet Academy, OU = Security Division, CN = Alice

Para poder ver que es lo que hay ponemos este comando:
openssl x509 -in alice_cert.crt -text-noout | head -n60

Y esto es todo lo que aparece:
	-----BEGIN CERTIFICATE-----
	MIIEPTCCAiUCFEU0nvA0CRh2OHb13hOz5XtebPJ7MA0GCSqGSIb3DQEBCwUAMGEx
	CzAJBgNVBAYTAkVTMRowGAYDVQQKDBFTdGFyZmxlZXQgQWNhZGVteTEaMBgGA1UE
	CwwRU2VjdXJpdHkgRGl2aXNpb24xGjAYBgNVBAMMEVN0YXJmbGVldCBSb290IENB
	MB4XDTI2MDIyMjE5MzYxNloXDTI3MDIyMjE5MzYxNlowVTELMAkGA1UEBhMCRVMx
	GjAYBgNVBAoMEVN0YXJmbGVldCBBY2FkZW15MRowGAYDVQQLDBFTZWN1cml0eSBE
	aXZpc2lvbjEOMAwGA1UEAwwFQWxpY2UwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAw
	ggEKAoIBAQDOx9NfCGEPbkhp5yyTLAQ1wfynuKrueLaP67gNtIn9s54yRcI+OM5V
	ZD0J8SJ9g4cbNIWCijINCixTHVXJoQoSrxZEZbpv8T+PTvF/prU0iC8ne263byiS
	fUULHT8gPT5WCzOYNvXEe9H7BXlGYfuHXnxthPhNmrkHR9k++muyeHrRHVfVJlu5
	6v8qto6Y39xbKZvrQcxBQX5u4stKGx6PqjNOwtQN5KBL9AGzv2eeQXlbNadUy8KE
	KW+VfWolBo8MyAMDZPkPARPHDqs8Q4YhQAt1aD1mfzDPx1jDM0G0D0nC36SxWK6p
	uXEjpb9ZuoSEzGlrzMSgpj93n2AvYS9TAgMBAAEwDQYJKoZIhvcNAQELBQADggIB
	AKTKG+f4IN5rrb9ivGPLs2/piAMHA3b5EEbTBNnLzYZX9HyZR5a3ygFnQ/Ewartx
	brBsGL6/bFy5QH4Ys4/A+XmpHXLrNq73jVKmESXPLKlrXOB+anDTjbbNkBRv4oyV
	TEVrkJPPGFuVQWyy2DYUuyk4NFxe+hAvk5t7ZlpVaLZnckMYQXOlQdTD5GxkgNbC
	RTB2ZehtrM6xrXW42RSnot9cYV+oJ3xANV7g0BB/AyrkXOtsi95P/1fI8ub2HEyA
	IwMLp+U+Av1envcBqmQ486lCbQ54POW/Uom6RkaOl92c0s+wSv4XTzsXmXofl9Wm
	306728XEYQF1czqtVcHo+wL8olp5Cb4B1CwcE3l88Ma5gV3p5fPx+E8Th2HKfGZO
	Iw2sKz1aDTZX99Hay3FO+35M8Ak5Bwy+oAkyvh8v1TCezOXn+QDBPapsfua2Cxm/
	pfLAvYVXOxObPgJfg6NP8O/qlHYhxUDKGIzAbUTgZbbRHg1qNajROA5Q22pW1J3L
	qNyFYp1q7ZwEAaCAgHBwL7GyinWV9vAEuoBk03IKrkyrSmaXWnjG0GusdToZgBpu
	Em4HA6LM/4CvweofBG2Jr+ChS31/+6c+yqNezB5CfjVNBNDAnPfvJcKuSJDKKUCl
	Y6MGJDdBNFJ4FiWmGGcISLsjeW+7+1McgcXRN2GGMYk9
	-----END CERTIFICATE-----

### 4.4 Verificar que el certificado de Alice “cuelga” de la CA

openssl verify -CAfile starfleet_ca.crt alice_cert.crt

Esto es lo que nos devuelve:
alice_cert.crt: OK