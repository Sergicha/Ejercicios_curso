# 28. SonarQube: El Guardián de la Calidad del Código

Trabajaremos sobre la ruta: ~/proyectos/proyectoX
## 1 Montar SonarQube en Docker
### 1.1 Comprobar requisitos previos
- Comprobamos que Docker este instalado
```
docker --version
```
- Comprobamos si hay contenedores funcionando
```
docker ps
```
- Verifica que tienes Docker Compose
```
docker compose version
```
### 1.2 Preparar la carpeta del proyecto
```
mkdir -p proyectos/sonarqube-docker
```
```
cd sonarqube-docker
```
- Dentro de sonarqube-docker:
```
touch docker-compose.yml
```
### 1.3) Ajuste del sistema: vm.max_map_count
### 1.3.1) Ver el valor actual
```
sysctl vm.max_map_count
```
- Resultado:
	vm.max_map_count = 1048576
- En este caso, no hay que subirlo, porque es superior a 65530.
### 1.3.2) Subirlo temporalmente (hasta reinicio), si fuera necesario
```
sudo sysctl -w vm.max_map_count=262144
```
### 1.3.3) Hacerlo permanente
```
echo "vm.max_map_count=262144" | sudo tee /etc/sysctl.d/99-sonarqube.conf
```
```
sudo sysctl --system
```
- Comprobación final:
```
sysctl vm.max_map_count
```
## 1.4) docker-compose.yml
```
nano docker-compose.yml
```
- Pegamos esto:
```
version: "3.9"

services:
  db:
    image: postgres:15
    container_name: sonar-db
    environment:
      POSTGRES_USER: sonar
      POSTGRES_PASSWORD: sonar
      POSTGRES_DB: sonarqube
    volumes:
      - sonar_db_data:/var/lib/postgresql/data
    networks:
      - sonar-net
    restart: unless-stopped

  sonarqube:
    image: sonarqube:community
    container_name: sonarqube
    depends_on:
      - db
    ports:
      - "9000:9000"
    environment:
      SONAR_JDBC_URL: jdbc:postgresql://db:5432/sonarqube
      SONAR_JDBC_USERNAME: sonar
      SONAR_JDBC_PASSWORD: sonar
    volumes:
      - sonar_data:/opt/sonarqube/data
      - sonar_extensions:/opt/sonarqube/extensions
      - sonar_logs:/opt/sonarqube/logs
    networks:
      - sonar-net
    restart: unless-stopped

volumes:
  sonar_db_data:
  sonar_data:
  sonar_extensions:
  sonar_logs:

networks:
  sonar-net:
    driver: bridge
```
## 1.5) Arrancar SonarQube
- En la carpeta donde está docker-compose.yml
```
docker compose up -d
```
- Resultado:
	[+] up 34/34
	 ✔ Image postgres:15                        Pulled                         8.3ss
	 ✔ Image sonarqube:community                Pulled                         21.7s
	 ✔ Network sonarqube-docker_sonar-net       Created                        0.0s
	 ✔ Volume sonarqube-docker_sonar_data       Created                        0.0s
	 ✔ Volume sonarqube-docker_sonar_extensions Created                        0.0s
	 ✔ Volume sonarqube-docker_sonar_logs       Created                        0.0s
	 ✔ Volume sonarqube-docker_sonar_db_data    Created                        0.0s
	 ✔ Container sonar-db                       Created                        0.2s
	 ✔ Container sonarqube                      Created                        0.0s
- Comprobamos que se ha levantado correctamente:
```
docker ps
```
## 1.6) Ver logs y esperar a que esté listo
- Para ver el estado:
```
docker logs -f sonarqube
```
- Esperamos ver un mensaje que diga:
	***SonarQube is operational***
## 1.7) Acceso web y primer login
- Desde el navegador, accedemos:
	http://localhost:9000
- Credenciales por defecto:
	**Usuario**: admin
	**Contraseña**: admin (la cambiaremos al iniciar sesión)

# 2) Proyectos de ejemplo + preparación del análisis
## 2.1) Estructura de carpetas
- En ~/proyectos/:
```
mkdir -p proyecto-php/src
```
```
mkdir -p proyecto-java/src
```
## 2.2) Proyectos PHP con Apache en contenedor
```
cd proyecto-php
```
### 2.2.1) Crear archivo PHP con "malos olores"
```
nano src/index.php
```
- Pegamos esto:
```
<?php
function suma($a, $b) {
    return $a + $b;
}

function suma2($a, $b) { // duplicación
    return $a + $b;
}

$x = 5;
$y = 10;

if ($x == $y) {
    echo "Son iguales";
} else {
    echo "No son iguales";
}

echo "<br>Resultado: " . suma($x, $y);

// Código muerto
$z = 100;
?>
```
### 2.2.2) Crear docker-compose del proyecto PHP
```
nano docker-compose.yml
```
- Pegamos esto:
```
version: '3.8'

services:
  php-apache:
    image: php:8.2-apache
    container_name: php-grupo01
    ports:
      - "8081:80"
    volumes:
      - ./src:/var/www/html
    restart: unless-stopped
```
- Arrancamos:
```
docker compose up -d
```
- Resultado:
	[+] up 19/19
	 ✔ Image php:8.2-apache         Pulled                                                                             6.4s
	 ✔ Network proyecto-php_default Created                                                                            0.0s
	 ✔ Container php-grupo01        Created                                                                            0.2s
- Abrimos en el navegador:
	http://localhost:8081
- Mostrará en el navegador:
	***No son iguales
	Resultado:15***
## 2.3) Crear configuración básica de Sonar para PHP
- En ~/proyectos/proyecto-php/
```
nano sonar-project.properties
```
- Pegamos: 
```
sonar.projectKey=lab1-php
sonar.projectName=Proyecto PHP Lab1
sonar.projectVersion=1.0
sonar.sources=src
sonar.sourceEncoding=UTF-8
```
## 2.4) Proyecto Java simple
- En ~/proyectos/proyecto-java/
```
nano src/Main.java
```
- Pegamos esto:
```
public class Main {

    public static int suma(int a, int b) {
        return a + b;
    }

    public static int suma2(int a, int b) { // duplicación
        return a + b;
    }

    public static void main(String[] args) {
        int x = 5;
        int y = 5;

        if (x == y) {
            System.out.println("Iguales");
        } else {
            System.out.println("No son iguales");
        }

        int z = 100; // código muerto
        System.out.println(suma(x, y));
    }
}
```
- También creamos:
```
nano sonar-project.properties
```
- Pegamos:
```
sonar.projectKey=lab1-java
sonar.projectName=Proyecto JAVA Lab1
sonar.projectVersion=1.0
sonar.sources=.
sonar.sourceEncoding=UTF-8
```

# 3) Primer análisis real con Sonar Scanner
## 3.1) Crear Token en SonarQube
- Ir a:
	***User → My Account → Security → Generate Token***
## 3.2) Ejecutar Sonnar Scanner desde contenedor
- En ~/proyectos/proyecto-java/
```
sudo docker run --rm --network host \
  -e SONAR_HOST_URL="http://127.0.0.1:9000" \
  -e SONAR_TOKEN="sqa_8eb988779ec5c1d5ad2f1ec178845cde911010e8" \
  -v "$(pwd):/usr/src" \
  -w /usr/src \
  sonarsource/sonar-scanner-cli \
  -Dsonar.ws.timeout=120 \
  -Dsonar.scanner.ws.timeout=120
```
# 4) Ver el resultado del análisis
- En el navegador, vamos a:
	http://localhost:9000
- Entramos a **Projects**. Y ahí veremos nuestro proyecto.
	***Proyecto JAVA lab1***
- Entrar y observar:
	Bugs
	Vulnerabilities
	Code Smells
	Maintainability Rating
	Duplications
# 5) Analizar también el proyecto PHP
- En ~/proyectos/proyecto-php/
```
sudo docker run --rm --network host \
  -e SONAR_HOST_URL="http://127.0.0.1:9000" \
  -e SONAR_TOKEN="sqa_8eb988779ec5c1d5ad2f1ec178845cde911010e8" \
  -v "$(pwd):/usr/src" \
  -w /usr/src \
  sonarsource/sonar-scanner-cli \
  -Dsonar.ws.timeout=120 \
  -Dsonar.scanner.ws.timeout=120
```
# 6) Ver el resultado (Igual que en el apartado 4)
- En el navegador, vamos a:
	http://localhost:9000
- Entramos a **Projects**. Y ahí veremos nuestro proyecto.
	***Proyecto PHP lab1***
- Entrar y observar:
	Bugs
	Vulnerabilities
	Code Smells
	Maintainability Rating
	Duplications