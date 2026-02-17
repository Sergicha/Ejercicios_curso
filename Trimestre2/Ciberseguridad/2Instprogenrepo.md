# 2. [IAW] instalación de programa desde un repositorio.

## El primer paso será el actualizar el servidor
sudo apt update
sudo apt upgrade

## Necesitaremos instalar Git, Node.js y npm
sudo apt install git -y
curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash - sudo apt install -y nodejs

### Comprobaremos si funciona correctamente node y npm
node -v
npm -v

## Clonaremos el repositorio FossFLOW
git clone https://github.com/stan-smith/FossFLOW.git cd FossFLOW

## Instalaremos las dependencias que se necesiten en el proyecto
npm install

## Construiremos el proyecto
npm run build:lib

## Ejecutaremos la app, para ver su correcto funcionamiento

### Entraremos dentro de la carpeta por si no estamos dentro ya
cd FossFLOW

### Ejecutaremos la app en modo desarrollo
npm run dev
Podremos observar: Server running on http://localhost:3000

### Para acceder desde nuestro PC en la terminal ejecutaremos este comando:
ssh -L 3000:localhost:3000 tunombreusuario@ipdelservidor
ssh -L 3000:localhost:3000 sergio@192.168.1.50

### Para acceder desde el navegador solo hay que buscarlo por internet:
En el buscador pegaremos esto y luego le daremos enter: http://localhost:3000

## Probando la aplicación

### Crear un diagrama isométrico
Buscaremos desde el navegador: http://localhost:3000
Seleccionamos la opción de nuevo diagrama y elegimos el tipo de vista isométrico
Probaremos aquí el motor gráfico, el crear un nuevo proyecto y el rendimiento isometrico
Y la prueba funciono correctamente sin errores

### Añadir varios elementos
Dentro del panel de elementos elegimos tres de ellos: Servidor, base de datos y conexiones. Siendo colocados en diferentes posiciones del diagrama, funcionando correctamente el sistema de arrastre y el de posicionamiento

### Guardar o exportar el proyecto
Buscaremos la opción de Guardar o exportar, guardamos el archivo como un .json y se descarga correctamente en el PC local sin ningún tipo de perdida de información

### Importarlo nuevamente para verificar funcionamiento
Cerraremos para este punto el diagrama actual que tengamos, usamos la opción de importar, seleccionamos el archivo que hemos importado antes y el diagrama se restauró correctamente, lo cual nos dice que funciona correctamente el funcionamiento de guardado y de la carga

## Conclusión
Durante el proceso de este ejercicio hemos podido instalar, configurar y ejecutar correctamente la aplicación en el servidor de Ubuntu que ya teníamos instalado previamente. Hemos ido haciendo comprobaciones de que funcionaba correctamente todo, haciendo el diagrama, añadiendo diferentes elementos, exportandolo y importando el proyecto comprobando que no hubiera perdida de datos. En el ejercicio pudimos aprender más sobre servidores de Linux, Git y la gestión de dependencias de Node.js entre otras cosas, funciona correctamente FossFLOW y cumple todas las exigencias puestas en el proyecto.