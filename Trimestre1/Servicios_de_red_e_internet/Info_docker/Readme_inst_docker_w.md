# Guía de Instalación de Docker en windows Sergio Chamba
# Requisitos previos
Tener windows 10 (Pro, enterprise o Education) o 11 de 64 bit
Virtualización habilitada en la BIOS Intel o AMD
4GB de RAM o superior

# 1. Descargar Docker Desktop
Entrar a la pagina de Docker: https://www.docker.com/products/docker-desktop/
Y hacer click en Download for Windows (Desktop)
Se descargara un archivo .exe

# 2. Instalar Docker Desktop
Lo primero es ejecutar el instalador que es el .exe
Aceptar los permisos de administrador
Dejar seleccionadas las opciones por defecto
Darle click en install
Una vez completado puede que tengas que reiniciar el ordenador

# 3. Primer inicio de Docker 
Abre Docker desktop desde el menú de inicio
Espera a que termine de cargar
Cuando aparezca Docker engine is running, significa que todo esta bien
Lo siguiente sería registrarse o iniciar sesion

# 4. Comprobación de que funciona correctamente
En tu CMD ejecuta: docker --version y docker run hello-world

Y ya estaría funcionando correctamente docker