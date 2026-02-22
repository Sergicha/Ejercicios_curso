# 25. Tor como herramienta de privacidad (no como escondite)

## Paso 1 - Descargar Tor Browser (fuente oficial)

Hay que abrir el navegador normal y entrar en la web oficial de TOR, yo por ejemplo lo hice en ubuntu (firefox):
https://www.torproject.org/download/

## Paso 2 - Instalar y ejecutar el navegador

El archivo que nos haya dado lo abrimos para terminar de instalarlo,  lo abrimos y nos conectamos

## Actividad 1 — Comparativa (Normal vs Tor)

### Lo primero es la descarga:
Hay que abrir el navegador normal y entrar en la web oficial de TOR, yo por ejemplo lo hice en ubuntu (firefox):
https://www.torproject.org/download/

### Comparar IP (Normal vs Tor)
     IP                 Pais
192.168.1.145          España
185.220.101.45        Alemania

### Comparar idioma detectado
Si nos metemos en wiki pedia las principales diferencias son las siguientes:
Navegador normal                                                    Navegador Tor
Pone directamente el español                                        No aparece siemore el español
Detecta donde estoy sin preguntar                                   Pide elegir el idioma
Aparecen sugerencias de cosas cercanas                              Se muestra en inglés por defecto

### Fingerprint básico (huella del navegador)
Navegador normal
Me considera único poniendome: Your browser has a unique fingerprint
Sí nos detecta bloqueadores como el bloqueador de anuncios
Si tienen datos recogidos

Tor
No me considera único poniendome: Your browser is not unique among tested browsers
Tiene los plugins totalmente deshabilitados
Tiene muchos menos datos recogidos

## Actividad 2 — Análisis de cabeceras HTTP (Headers)

### 2.1
a) JSON:
{
  "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) Firefox/122.0",
  "Accept-Language": "es-ES,es;q=0.9,en;q=0.8",
  "DNT": "1"
}

b) JSON
{
  "User-Agent": "Mozilla/5.0 (Windows NT 10.0; rv:102.0) Gecko/20100101 Firefox/102.0",
  "Accept-Language": "en-US,en;q=0.5"
}

Campo              Navegador Normal    Tor Browser              
IP                 Real                La de Tor   
User-Agent         Real y específico   Es genérico y estandarizado 
Accept-Language    es-ES               en-US                    
Fingerprint        Único               Es poco común
Región detectada   Sí                  No siempre

