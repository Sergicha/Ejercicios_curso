# Mi vida en un contenedor (Retos Docker) Sergio Chamba
# RETO 1:
## 1.1 Creamos un contenedor Docker en Ubuntu
docker run -it --name ubuntu-dev ubuntu bash

## 1.2 Instalamos Python y las librerias necesarias
apt update && apt -y upgrade
apt -y install python3 python3-pip
pip install requests mysql-connector-python

## 1.3 Creamos una imagen personalizada
docker commit ubuntu-dev ubuntu-python-custom

# RETO 2:
## 2.1 Crearemos un directorio en el host
mkdir ~ /docker-volumen

## Crearemos un nuevo contenedor basado en la imagen personalizada
docker run -it
--name ubuntu-with-volume
-v ~/docker-volumen:/app
ubuntu-python-custom bash

# RETO 3:
## 3.1 En la carpeta de volumen
cd ~/docker-volumen
git init
git add .
git commit -m "Inicialización del repositorio"

## 3.2 Crear un repositorio GitHub y vincularlo
git remote add origin https://github.com/user/repo.git
git branch -M main
git push -u origin main

# RETO 4:
## Desde la terminal:
## Creación y ejecución del contendor mysql:
	docker run --name mysql_server -d \
		-e MYSQL_ROOT_PASSWORD=Abcd1234 \
		-e MYSQL_DATABASE=bdcoches \
		-p 3307:3306 \
		-v mysql_data:/var/lib/mysql \
		mysql:latest
## Accedemos al contenedor:
	docker exec -it mysql_server bash
	mysql -u root -p
## Una vez dentro:
### Creamos la tabla coches en nuestra base de datos (mi_base_datos):
CREATE TABLE coches(
	id INT PRIMARY KEY,
	marca VARCHAR(20),
	modelo VARCHAR(20),
	color VARCHAR(15),
	km INT,
	precio INT
);
### Añadimos 5 registros:
INSERT INTO coches (id, marca, modelo, color, km, precio) VALUES
	(1, 'Ford', 'Corolla', 'Blanco', 50000, 15000),
	(2, 'Nissan', 'Civic', 'Negro', 30000, 18000),
	(3, 'Toyota', 'Focus', 'Azul', 40000, 14000),
	(4, 'Chevrolet', 'Cruze', 'Rojo', 60000, 13000),
	(5, 'Honda', 'Sentra', 'Gris', 20000, 17000);

# RETO 5:
## Para que puedan verse los contenedores, creamos una red personalizada:
docker network create mi-red-app
## Lanzamos un contenedor mysql, conectándolo a la red:
	docker run -d \
	--name mi-db-sql \
	--network mi-red-app \
	-e MYSQL_ROOT_PASSWORD=Abcd1234 \
	-e MYSQL_DATABASE=bdcoches \
	mysql:latest
## Creamos la tabla y le insertamos los datos, como en el reto anterior.
### Creamos un directorio donde tendremos dos archivos:
	1. consulta_db.py
	2. Dockerfile
### Construimos una imagen:
	docker build -t mi-app-python .
### Ejecutamos el contenedor:
	docker run --rm --name mi-app-python --network mi-red-app mi-app-python
### Nos mostrará por pantalla la tabla de los registros de la base de datos:
	+----+------------+---------+--------+-------+--------+
	| id |   marca    |  modelo | color  |   km  | precio |
	+----+------------+---------+--------+-------+--------+
	| 1  |   Toyota   | Corolla | Blanco | 50000 | 15000  |
	| 2  |   Honda    |  Civic  | Negro  | 30000 | 18000  |
	| 3  |    Ford    |  Focus  |  Azul  | 40000 | 14000  |
	| 4  | Chevrolet  |  Cruze  |  Rojo  | 60000 | 13000  |
	| 5  |   Nissan   |  Sentra |  Gris  | 20000 | 17000  |
	+----+------------+---------+--------+-------+--------+

# RETO 6:
## Desde el directorio donde hemos creado el Dockerfile, creamos dos archivos nuevos:
	- .gitignore
	- db_config.json
## Modificamos consulta_db.py, para que apunte a db_config.json.
### Reconstruimos la imagen:
	docker build -t mi-app-python .
### Añadimos esta linea al Dockerfile:
	COPY db_config.json /app/
### Lanzamos el contenedor:
	docker run --rm --name mi-app-python --network mi-red-app mi-app-python
### Nos mostrará la tabla de antes.

# RETO 7:
## Modificamos el archivo consulta_db.py, para que use la libreria prettytable.
### Reconstruimos la imagen:
	docker build -t mi-app-python .
### Lanzamos el contenedor:
	docker run --rm --name mi-app-python --network mi-red-app mi-app-python
### Nos mostrará la tabla así:
	+-----------------------------------------------------------+
	| ID | Marca      | Modelo  | Color  | Kilometraje | Precio |
	+----+------------+---------+--------+-------------+--------+
	| 1  | Toyota     | Corolla | Blanco | 50000       | 15000  |
	+----+------------+---------+--------+-------------+--------+
	| 2  | Honda      | Civic   | Negro  | 30000       | 18000  |
	+----+------------+---------+--------+-------------+--------+
	| 3  | Ford       | Focus   | Azul   | 40000       | 14000  |
	+----+------------+---------+--------+-------------+--------+
	| 4  | Chevrolet  | Cruze   | Rojo   | 60000       | 13000  |
	+----+------------+---------+--------+-------------+--------+
	| 5  | Nissan     | Sentra  | Gris   | 20000       | 17000  |
	+-----------------------------------------------------------+

# RETO 8:
## Creamos el contenedor mongo en el mismo directorio que hemos usando para los retos anteriores:
	docker run -d \
		  --name mi-mongo \
		  --network mi-red-app \
		  -p 27017:27017 \
		  -e MONGO_INITDB_ROOT_USERNAME=mongouser \
		  -e MONGO_INITDB_ROOT_PASSWORD=mongo1234 \
		  mongo:latest
## Accedemos al contenedor:
	docker exec -it mi-mongo mongosh -u mongouser -p mongo1234 --authenticationDatabase admin
### Usamos estos comandos:
		use bdcoches_mongo
### Añadimos registros:
	db.coches.insertMany([
		{
		  	"ID": 1,
		  	"Marca": "Toyota",
		  	"Modelo": "Corolla",
		  	"Color": "Rojo",
		  	"km": 25000,
		  	"Precio": 15000
		},
		{
		  	"ID": 2,
		  	"Marca": "Honda",
		  	"Modelo": "Civic",
		  	"Color": "Azul",
		  	"km": 30000,
		  	"Precio": 18000
		},
		{
		  	"ID": 3,
		  	"Marca": "Ford",
		  	"Modelo": "Focus",
		  	"Color": "Blanco",
		  	"km": 40000,
			"Precio": 17000
		}
	])
## Creamos un documento: consulta_mongo.py
### Actualizamos el Dockerfile.
### Construimos la imagen y lo lanzamos:
	docker build -t mi-mongo .
	docker run --rm --name mi-mongo1 --network mi-red-app mi-mongo
### Visualizamos la tabla:
		+----+--------+---------+--------+-------------+--------+
		| ID | Marca  | Modelo  | Color  | Kilometraje | Precio |
		+----+--------+---------+--------+-------------+--------+
		| 1  | Toyota | Corolla | Rojo   | 25000       | 15000  |
		+----+--------+---------+--------+-------------+--------+
		| 2  | Honda  | Civic   | Azul   | 30000       | 18000  |
		+----+--------+---------+--------+-------------+--------+
		| 3  | Ford   | Focus   | Blanco | 40000       | 17000  |
		+----+--------+---------+--------+-------------+--------+

# RETO 9:
## En un nuevo directorio llamado reto9, crearemos:
	Dockerfile
	init-mongo.js
## Accedemos a DockerHub:
	docker login
### Construimos la imagen:
	docker build -t <TU_USUARIO_DOCKERHUB>/mongo-coches:latest .
### Subimos la imagen:
	docker push <TU_USUARIO_DOCKERHUB>/mongo-coches:latest
## Accedemos a nuestro ParrotOS:
### Lanzamos la imagen:
	docker run -d \
	  --name mongo-desde-hub \
	  -e MONGO_INITDB_ROOT_USERNAME=mongouser \
	  -e MONGO_INITDB_ROOT_PASSWORD=mongo1234 \
	  -p 27017:27017 \
	  <TU_USUARIO_DOCKERHUB>/mongo-coches:latest
	docker exec -it mongo-desde-hub mongosh -u mongouser -p mongo1234 --authenticationDatabase admin