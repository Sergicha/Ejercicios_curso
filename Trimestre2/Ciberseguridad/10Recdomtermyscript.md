# 10 [OSINT] Reconocimiento de dominios por terminal y scripting

## 1. AMASS – Descubrimiento de subdominios (OSINT real)

### Descargar AMASS y comprobar cual es su version

sudo apt install amass
amass version

### Ejemplos de uso
Si quieres un modo de enumeración con solo fuentes OSINT de dominio objetivo:
amass enum -passive -d ejemplo.com
Si buscas uno con salida a archivo:
amass enum -passive -d ejemplo.com -o subdominios.txt
Uno con una enumeración a muchos dominios:
amass enum -passive -df dominios.txt -o resultados.txt

## 2. Subfinder – Enumeración rápida y minimalista

### Lo primero es la descarga 
sudo apt install subfinder

### Hacemos un uso básico
Una lista de subdominios de uno por linea, sin ningún ruido y perfecto para canalizar otros comandos:
subfinder -d ejemplo.com -o subfinder.txt

## 3. Geolocalización – Poniendo los pies en la Tierra

### Para la Geolocalización con whois
whois 8.8.8.8
Esto nos entregara: El pais, organización, ASN y rango de IPs

### Usar geoiplookup
Lo primero sería descarganoslo:
sudo apt install geoip-bin
Nos devuelve el pais y la región aproximada

### Geolocalización de APIs desde terminal

curl ipinfo.io/8.8.8.8
Nos devolverá la ciudad, país, las coordenadas, ASN y la organización
Nos deja tambien aqui introducir el parseo con jq, la automatización y la correlación de subdominios.

## 4. Uniendo piezas: OSINT como proceso

Amass:
amass enum -passive -d ejemplo.com -o amass.txt
www.ejemplo.com
mail.ejemplo.com
api.ejemplo.com
dev.ejemplo.com
Amass consultó múltiples fuentes OSINT públicas certificados, DNS históricos, bases de datos públicas.

Subfinder:
subfinder -d ejemplo.com -o subfinder.txt
www.ejemplo.com
api.ejemplo.com
cdn.ejemplo.com
beta.ejemplo.com
Subfinder fue más rápido y directo, además de que c2omplementa a Amass, a veces encuentra subdominios distintos.

La unificación:
sort amass.txt subfinder.txt | uniq > subdominios_finales.txt
www.ejemplo.com
mail.ejemplo.com
api.ejemplo.com
dev.ejemplo.com
cdn.ejemplo.com
beta.ejemplo.com
Esto elimina duplicados y genera un inventario consolidado

while read sub; do
  host $sub
done < subdominios_finales.txt

www.ejemplo.com has address 34.120.55.12
api.ejemplo.com has address 34.120.55.15
dev.ejemplo.com has address 185.199.110.153

whois 34.120.55.12
Organización: Google Cloud
País: Estados Unidos
ASN: AS15169

curl ipinfo.io/34.120.55.12
{
  "ip": "34.120.55.12",
  "city": "Council Bluffs",
  "region": "Iowa",
  "country": "US",
  "org": "AS15169 Google LLC"
}