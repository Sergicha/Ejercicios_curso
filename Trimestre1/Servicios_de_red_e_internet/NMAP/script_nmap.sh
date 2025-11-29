#!/usr/bin/env bash
set -o pipefail

#---------------------------#
#  Verificaciones básicas   #
#---------------------------#
command -v nmap >/dev/null || { 
    echo "Instala nmap: sudo apt install -y nmap"; exit 1; 
}

[[ $EUID -ne 0 ]] && echo "[AVISO] Algunas opciones requieren sudo."

#---------------------------#
# Funciones utilitarias     #
#---------------------------#
ask_common() {
    read -p "Objetivo (IP/host): " TARGET
    [[ -z "$TARGET" ]] && return 1

    read -p "Directorio salida (default ./out): " OUT
    OUT=${OUT:-./out}
    mkdir -p "$OUT"

    TS=$(date +%Y%m%d_%H%M%S)
}

legal_note() {
cat <<EOF

--- USO LEGAL ---
• Solo escaneá sistemas con permiso explícito.
• Escanear sin permiso es ilegal.
EOF
read -p "Continuar (Enter)..."
}

run() {
    local name="$1" use_sudo="$2"
    shift 2
    ask_common || return

    local file="${OUT}/${name}_${TARGET}_${TS}.txt"

    echo "Ejecutando: nmap $* $TARGET"
    $use_sudo nmap "$@" "$TARGET" -oN "$file"

    echo "Resultado: $file"
    legal_note
}

#---------------------------#
#       MENÚ PRINCIPAL      #
#---------------------------#
menu() {
    while true; do
        clear
        cat <<EOF
======== NMAP MINI TOOL ========
1) Ping scan
2) SYN top 100
3) Connect + puertos custom
4) Servicios (-sV -sC)
5) Detección SO (-O)
6) UDP (-sU)
7) Agresivo (-A)
8) NSE scripts
9) Formatos salida
10) Sin ping (-Pn)
11) Comando custom
12) Salir
================================
EOF
        read -p "Opción: " op

        case $op in
            1) run "ping" ""      -sn ;;
            2) run "syn"  sudo    -sS --top-ports 100 -T4 ;;
            3) ask_common || continue
               read -p "Puertos (default 1-1024): " P; P=${P:-1-1024}
               run "connect" "" -sT -p "$P"
               ;;
            4) run "services" sudo -sV -sC -p- -T4 ;;
            5) run "os" sudo -O -v ;;
            6) run "udp" sudo -sU -p- -T3 ;;
            7) run "aggressive" sudo -A -p- -T4 ;;
            8) ask_common || continue
               read -p "Script NSE (default vuln): " NSE; NSE=${NSE:-vuln}
               run "nse_${NSE}" sudo --script="$NSE" -p- -T4
               ;;
            9) ask_common || continue
               PREF="${OUT}/formats_${TARGET}_${TS}"
               nmap -sV --top-ports 50 "$TARGET" \
                    -oN "${PREF}.nmap" -oX "${PREF}.xml" -oG "${PREF}.gnmap"
               echo "Generado: ${PREF}.*"; legal_note
               ;;
            10) run "nopin" "" -Pn --top-ports 100 -T4 ;;
            11) ask_common || continue
                read -p "Opciones para nmap: " OPTS
                run "custom" "" $OPTS
                ;;
            12) exit 0 ;;
            *) echo "Opción inválida"; sleep 1 ;;
        esac
    done
}

menu
