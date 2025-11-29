# Pokédex Bash: toma de decisiones y comprobaciones en scripts Sergio Chamba
# 1. Descargar
## Primero descargamos json en ubuntu

sudo apt install jq

# 2. Creacion de carpetas
## En este caso haremos dos carpetas, una con pokedex.json y otra pokedex.sh

# 3. En el pokedex.json
## Insertaremos el siguiente código para su funcionamiento:
    #!/usr/bin/env bash

    # Colores para mensajes bonitos
    verde="\e[32m"
    rojo="\e[31m"
    amarillo="\e[33m"
    reset="\e[0m"

    # 1) Comprobamos si jq está instalado
    echo -e "${amarillo}Comprobando si jq está instalado...${reset}"

    if ! command -v jq &> /dev/null; then
        echo -e "${rojo}jq no está instalado.${reset}"

        # Detectar sistema utilizando (Ubuntu o Raspbian)
        if grep -qi "ubuntu" /etc/os-release || grep -qi "raspbian" /etc/os-release; then
            echo -e "${amarillo}Sistema Ubuntu o Raspbian detectado.${reset}"
            echo "Instalando jq..."
            sudo apt update && sudo apt install -y jq
        else
            echo -e "${rojo}Este script solo instala jq automáticamente en Ubuntu o Raspbian.${reset}"
            echo "Instálalo manualmente con: sudo apt install jq"
            exit 1
        fi

        # Verificar la instalación
        if ! command -v jq &> /dev/null; then
            echo -e "${rojo}Error: jq no se pudo instalar correctamente.${reset}"
            exit 1
        fi
    else
        echo -e "${verde}jq ya está instalado.${reset}"
    fi

    # 2) Comprobamos el archivo pokedex.json
    json_file="pokedex.json"

    if [ ! -f "$json_file" ]; then
        echo -e "${amarillo}No se encontró el archivo ${json_file}.${reset}"
        echo "Creando archivo base de Pokédex..."

        cat > "$json_file" <<'EOF'
    {
      "pokemons": [
        { "numero": 1, "nombre": "Bulbasaur", "tipo": "Planta/Veneno", "nivel": 5 },
        { "numero": 4, "nombre": "Charmander", "tipo": "Fuego", "nivel": 5 },
        { "numero": 7, "nombre": "Squirtle", "tipo": "Agua", "nivel": 5 },
        { "numero": 25, "nombre": "Pikachu", "tipo": "Eléctrico", "nivel": 8 },
        { "numero": 6, "nombre": "Charizard", "tipo": "Fuego/Volador", "nivel": 36 },
        { "numero": 94, "nombre": "Gengar", "tipo": "Fantasma/Veneno", "nivel": 45 },
        { "numero": 448, "nombre": "Lucario", "tipo": "Lucha/Acero", "nivel": 40 },
        { "numero": 658, "nombre": "Greninja", "tipo": "Agua/Siniestro", "nivel": 50 }
      ]
    }
    EOF

        echo -e "${verde}Archivo pokedex.json creado correctamente con nuevos Pokémon.${reset}"
    fi

    # 3) Pedir que eliga el usuario un pokemon
    echo -e "\n${amarillo}Introduce el nombre o número del Pokémon:${reset}"
    read -r entrada

    # 4) Buscamos el pokemon en la base de datos Pokémon
    if [[ "$entrada" =~ ^[0-9]+$ ]]; then
        # Buscar por número (comparando como texto para evitar errores de tipo)
        pokemon=$(jq -r --arg num "$entrada" '.pokemons[] | select((.numero|tostring) == $num)' "$json_file")
    else
        # Buscar por nombre (sin distinguir mayúsculas/minúsculas)
        pokemon=$(jq -r --arg nombre "$entrada" '.pokemons[] | select(.nombre | ascii_downcase == ($nombre | ascii_downcase))' "$json_file")
    fi

    # 5) Comprobamos si es correcto el resultado
    if [ -z "$pokemon" ]; then
        echo -e "${rojo}No se encontró ningún Pokémon con ese nombre o número.${reset}"
        exit 1
    fi

    # 6) Mostramos los datos del Pokémon
    numero=$(echo "$pokemon" | jq -r '.numero')
    nombre=$(echo "$pokemon" | jq -r '.nombre')
    tipo=$(echo "$pokemon" | jq -r '.tipo')
    nivel=$(echo "$pokemon" | jq -r '.nivel')

    echo -e "\n${verde}=== Pokémon encontrado ===${reset}"
    echo "Número: $numero"
    echo "Nombre: $nombre"
    echo "Tipo: $tipo"
    echo "Nivel: $nivel"

    echo -e "\n${verde}Búsqueda completada exitosamente.${reset}"

# 4. Introduciermos la base de datos de los pokemon
    {
      "pokemons": [
        { "numero": 1, "nombre": "Bulbasaur", "tipo": "Planta/Veneno", "nivel": 5 },
        { "numero": 4, "nombre": "Charmander", "tipo": "Fuego", "nivel": 5 },
        { "numero": 7, "nombre": "Squirtle", "tipo": "Agua", "nivel": 5 },
        { "numero": 25, "nombre": "Pikachu", "tipo": "Eléctrico", "nivel": 8 },
        { "numero": 6, "nombre": "Charizard", "tipo": "Fuego/Volador", "nivel": 36 },
        { "numero": 94, "nombre": "Gengar", "tipo": "Fantasma/Veneno", "nivel": 45 },
        { "numero": 448, "nombre": "Lucario", "tipo": "Lucha/Acero", "nivel": 40 },
        { "numero": 658, "nombre": "Greninja", "tipo": "Agua/Siniestro", "nivel": 50 }
      ]
    }

# 5. Le daremos permisos de ejecución al archivo

sudo chmod +x pokedex.sh

# 6. Comprobamos que funcione correctamente el programa
Ejecutaremos el programa con el siguiente comando: ./pokedex.sh
Y ahí nos llevará directamente al menú