# 11 [LINUX] - IA en la terminal

## Instalación

### Lo instalaremos en Linux
curl -sSL https://raw.githubusercontent.com/aandrew-me/tgpt/main/install | bash -s /usr/local/bin

### Para comprobar si a funcionado correctamente
tgpt --help

### Para el primer uso en Linux lo podremos probar con:
tgpt "Explícame el comando descargar curl con un ejemplo"

### Para hacer varias consultas seguidas con el mismo contexto:
tgpt -c

### Para elegir un modelo
tgpt -m gpt-3.5-turbo "Dame un ejemplo de escaneo pasivo"

### Para una respuesta corta y directa
tgpt -s "Como puedo mirar mi IP de Linux"

### Para solo respuesta sin información extra e innecesaria
tgpt -q "comando para ver usuarios conectados"
