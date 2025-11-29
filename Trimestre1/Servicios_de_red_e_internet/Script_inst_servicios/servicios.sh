#!/bin/bash

#==============================================================
#  SCRIPT PROFESIONAL DE INSTALACIÓN Y MANTENIMIENTO DE SERVICIOS
#  Autor: Sergio
#  Descripción: Menú interactivo para instalar y administrar servicios Linux
#==============================================================

clear

while true; do
    echo ".-------------------------------------------------------------."
    echo ".------------- MENU INSTALACIÓN DE SERVICIOS -----------------."
    echo ".-------------------------------------------------------------."
    echo "1) Instalar SSH"
    echo "2) Instalar Apache2"
    echo "3) Instalar MYSQL"
    echo "4) Instalar PHP"
    echo "5) Instalar FTP (vsftpd)"
    echo "6) Copia seguridad WEB (/var/www/html)"
    echo "7) Copia Bases de Datos (MySQL)"
    echo "8) Actualizar repositorio Linux"
    echo "9) Apagar equipo"
    echo "0) Salir del Script"
    echo ".-------------------------------------------------------------."
    read -p "Dame una opción [0-9]: " opcion
    clear

    case $opcion in
        1)
            echo "Instalando SSH..."
            sudo apt install ssh -y
            echo "SSH instalado correctamente."
            read -p "Presione Enter para continuar..."
        ;;

        2)
            echo "Instalar servidor Web..."
            sudo apt install apache2 -y
            echo "Apache2 instalado correctamente."
            read -p "Presione Enter para continuar..."
        ;;

        3)
            echo "Instalando servidor MySQL..."
            sudo apt install mysql-server -y
            echo "MySQL instalado correctamente."
            read -p "Presione Enter para continuar..."
        ;;

        4)
            echo "Instalando PHP..."
            sudo apt install php libapache2-mod-php php-mysql -y
            sudo systemctl restart apache2
            echo "PHP instalado correctamente."
            read -p "Presione Enter para continuar..."
        ;;

        5)
            echo "Instalando servidor FTP (vsftpd)..."
            sudo apt install vsftpd -y
            echo "Servidor FTP instalado correctamente."
            read -p "Presione Enter para continuar..."
        ;;

        6)
            echo "Creando copia de seguridad de /var/www/html..."
            sudo tar -czf web_backup.tar.gz /var/www/html
            echo "Copia creada como web_backup.tar.gz"
            read -p "Presione Enter para continuar..."
        ;;

        7)
            echo "Creando copia de seguridad de todas las bases de datos..."
            read -p "Usuario MySQL: " user
            read -s -p "Contraseña: " pass
            echo
            mysqldump -u $user -p$pass --all-databases > backup_mysql.sql
            echo "Copia guardada como backup_mysql.sql"
            read -p "Presione Enter para continuar..."
        ;;

        8)
            echo "Actualizando repositorio y sistema..."
            sudo apt update && sudo apt upgrade -y
            echo "Sistema actualizado correctamente."
            read -p "Presione Enter para continuar..."
        ;;

        9)
            echo "El sistema se está apagando"
            sudo shutdown now
        ;;

        0)
            echo "Saliendo del script"
            exit 0
        ;;

        *)
            echo "Opción no válida. Intente nuevamente."
            read -p "Presione Enter para continuar..."
        ;;
    esac
    clear
done
