# 12 - Aletheia un Sistema de Auditoría y Análisis Forense Automatizado

## Fase 1 — Preparación inicial del servidor

### Lo primero es actualizar el sistema

sudo apt update && sudo apt -y upgrade
sudo reboot

### Instalar las dependencias básicas

sudo apt -y install
curl unzip jq ca-certificates gnupg
python3 python3-venv python3-pip

### Configurar el nombre del equipo y verificar IP

sudo hostnamectl set-hostname forense-ai
hostnamectl
ip a

## Fase 2 — Instalación y prueba de Ollama

### Instalar Ollama y comprobarq que funciona

curl -fsSL https://ollama.com/install.sh | sh
sudo systemctl status ollama --no-pager

### Descargar y probar un modelo

ollama pull phi3
ollama run phi3 "Resume en 5 líneas qué es una auditoría de sistemas."

## Fase 3 — Servidor web (Apache + PHP)

### Instalación, configuración y activación de apache

sudo apt -y install apache2 php libapache2-mod-php
sudo systemctl enable --now apache2
sudo ufw allow 'Apache'
sudo ufw status

### Verificar el acceso desde el navegador

http://IP_DEL_SERVIDOR/

## Fase 4 — Estructura del proyecto Forense-AI

### Crear directorios

sudo mkdir -p /var/www/forense-ai/{uploads,results,scripts,logs}
sudo chown -R www-data:www-data /var/www/forense-ai
sudo chmod -R 750 /var/www/forense-ai

### Configurar VirtualHost y activación del sitio

sudo nano /etc/apache2/sites-available/forense-ai.conf

sudo a2ensite forense-ai
sudo a2dissite 000-default
sudo systemctl reload apache2

## Fase 5 — Aplicación web de análisis

### Herramientas para PDFs

sudo apt -y install poppler-utils

### Página principal (`index.php`)

Nos permitirá:

- Procesar todo en local con Ollama
- Elegir tipo de análisis: Auditoría, Forense o Resumen 
- Subir archivos TXT, LOG o PDF (máx. 8 MB)
    

### Script de procesamiento (`process.php`)

Nos permitirá:

- Envía la solicitud a Ollama 
- Guarda el informe en "results"
- Valida el archivo subido
- Genera un prompt según el tipo de análisis 
- Nos registra la actividad en "/logs/activity.log"

### Página de resultados (`result.php`)

Nos muestra el informe generado y nos permite descargarlo.

### Descarga del informe (`download.php`)

Nos entrega el archivo Markdown generado por el sistema.

## Fase 6 — Dar los permisos y la seguridad básica

sudo chown -R www-data:www-data /var/www/forense-ai
sudo find /var/www/forense-ai -type d -exec chmod 750 {} \;
sudo find /var/www/forense-ai -type f -exec chmod 640 {} \;
sudo systemctl reload apache2


## Fase 7 — Pruebas de funcionamiento

### Caso A — Auditoría (auth.log)

- Subir un archivo auth.log
- Seleccionar modo Auditoría
- Describir: _"Revisión de accesos SSH"_
- Verificar que se generen hallazgos y recomendaciones

### Solución de error común (HOME no definido)

Si aparece el error:

panic: $HOME is not defined

Crearemos un HOME para www-data:

sudo mkdir -p /var/lib/ollama
sudo chown -R www-data:www-data /var/lib/ollama

Y probaremos:

sudo -u www-data HOME=/var/lib/ollama ollama list

Modificar en process.php:

$cmd = "HOME=/var/lib/ollama ollama run phi3 " . escapeshellarg($prompt);d

Reiniciaremos Apache:

sudo systemctl reload apache2
