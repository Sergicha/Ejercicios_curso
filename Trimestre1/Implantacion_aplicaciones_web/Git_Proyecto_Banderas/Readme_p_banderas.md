# Git - Proyecto Banderas
## Primer paso actualizar el sistema e instalar Gitk
sudo apt update && sudo apt upgrade -y
sudo apt install gitk -y
## PARTE 1: Descargas Apache y probar funcionamiento
sudo apt install apache2
sudo systemctl status apache2
### Comprobamos que también nos aparece en el navegador
### Vamos a la carpeta para modificar la pagina de apache
cd /var/www/html
### Se crea un archivo index.html sobre el que trabajar
nano index.html
### Uniremos git con nuestra terminal
git init
### En el archivo index.html subiremos esto:
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bandera</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        .franja {
            height: 100px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="franja" style="background-color: red;"></div>
    <div class="franja" style="background-color: yellow;"></div>
    <div class="franja" style="background-color: red;"></div>
</body>
</html>

### Haremos un commit
git add index.html
git commit -m "Añade la bandera inicial (España)"

## PARTE 2: Creación de banderas y commits
### Modificaremos el archivo y le cambiaremos los colores para que sea de italia
<div class="franja" style="background-color: green;"></div>
<div class="franja" style="background-color: white;"></div>
<div class="franja" style="background-color: red;"></div>

### Continuaremos haciendo un commit 
git add index.html
git commit -m "Cambia los colores para la bandera de Italia"

### Repetiremos este proceso con los paises de Francia y Alemania

## Parte 3: Uso de ramas
### Crearemos una rama para cada bandera
git checkout -b italia
git checkout -b francia
git checkout -b alemania

### Le hacemos un commit a esa rama
git add index.html
git commit -m "Crea la bandera de Italia en la rama italia"
git add index.html
git commit -m "Crea la bandera de Francia en la rama francia"
git add index.html
git commit -m "Crea la bandera de Alemania en la rama Alemania"

### Refrescaremos el navegador para ver la bandera correspondiente

## Parte 4: Visualización con gitk
### Nos descargamos Gitk
sudo apt install gitk

### Visualizar el repositorio desde Gitk
gitk --all

## Parte 5: Publicación en GitHub
### En Github crea un nuevo repositorio
### Conectar terminal con repositorio y pushear todo el trabajo hecho
git remote add origin https://github.com/tu-usuario/banderas_git.git 
git branch -M main 
git push -u origin nombreRama