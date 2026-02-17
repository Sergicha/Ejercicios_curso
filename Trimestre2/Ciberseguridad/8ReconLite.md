# 8. [OSINT] ReconLite (mini escáner OSINT HTTP)

## Como implementar librerias con Python e implementa el ReconLite.

### 1º Paso descargar pip si no lo tienes descargado
Para Linux:
    sudo apt update
    sudo apt install python3-pip
    pip3 --version

### 2º Nos descargamos las librerias
python3 -m pip install requests

### 3º Comprobamos lo instalado
python3 -m pip list

### 4º Ver información de un paquete
python3 -m pip show requests

### 5º Creamos un entorno virtual
python3 -m venv venv
source venv/bin/activate

### 6º Lo instalamos dentro del entorno
python -m pip install requests

### 7º Generamos dependencias dentro del entorno
python -m pip freeze > requirements.txt
---------------------------------------------------------------------------------------------
## Ejercicio
### Creamos estas carpetas
reconlite/
    reconlite.py
    requirements.txt
    README.md
    report.json (se creará automaticamente)

### Insertamos dentro el requirements.txt
requests>=2.31.0

### Dentro de reconlite.py insertamos el siguiente código
#!/usr/bin/env python3
import argparse
import json
import re
import sys
import time
from urllib.parse import urljoin, urlparse

import requests


DEFAULT_PATHS = [
    "/", "/robots.txt", "/sitemap.xml",
    "/.git/", "/.env", "/config.php", "/phpinfo.php",
    "/admin", "/admin/", "/login", "/login/",
    "/wp-admin", "/wp-login.php",
    "/api", "/api/", "/swagger", "/swagger/", "/openapi.json",
    "/server-status", "/actuator", "/actuator/health"
]

SEC_HEADERS = [
    "Strict-Transport-Security",
    "Content-Security-Policy",
    "X-Content-Type-Options",
    "X-Frame-Options",
    "Referrer-Policy",
    "Permissions-Policy",
    "Cross-Origin-Opener-Policy",
    "Cross-Origin-Resource-Policy",
    "Cross-Origin-Embedder-Policy",
]


def normalize_url(raw: str) -> str:
    raw = raw.strip()
    if not raw:
        raise ValueError("URL vacía.")
    if not re.match(r"^https?://", raw, re.IGNORECASE):
        raw = "https://" + raw  # por defecto https
    u = urlparse(raw)
    if not u.netloc:
        raise ValueError("URL inválida. Ejemplo: https://example.com")
    # reconstrucción limpia (sin fragmentos)
    clean = f"{u.scheme}://{u.netloc}"
    if u.path and u.path != "/":
        clean += u.path.rstrip("/")
    return clean


def safe_request(session: requests.Session, method: str, url: str, **kwargs):
    t0 = time.time()
    try:
        r = session.request(method, url, **kwargs)
        dt = (time.time() - t0) * 1000.0
        return r, dt, None
    except requests.RequestException as e:
        dt = (time.time() - t0) * 1000.0
        return None, dt, str(e)


def extract_basic_fingerprint(headers: dict) -> dict:
    # Fingerprinting suave: headers típicos (no infalible)
    server = headers.get("Server")
    powered = headers.get("X-Powered-By")
    via = headers.get("Via")
    return {
        "server": server,
        "x_powered_by": powered,
        "via": via,
    }


def analyze_security_headers(headers: dict) -> dict:
    present = {}
    missing = []
    for h in SEC_HEADERS:
        if h in headers:
            present[h] = headers.get(h)
        else:
            missing.append(h)

    # Heurísticas sencillas (no dogma)
    notes = []
    if "Strict-Transport-Security" not in headers:
        notes.append("No HSTS: si es un sitio web público, se suele recomendar forzar HTTPS con HSTS.")
    if headers.get("X-Content-Type-Options", "").lower() != "nosniff":
        notes.append("X-Content-Type-Options no es 'nosniff' (o falta).")
    if "Content-Security-Policy" not in headers:
        notes.append("Sin CSP: suele reducir impacto de XSS (no lo elimina).")

    return {"present": present, "missing": missing, "notes": notes}


def is_interesting_status(code: int) -> bool:
    # 200/204/3xx/401/403 son “interesantes” para enumeración
    return code in (200, 201, 202, 204, 301, 302, 307, 308, 401, 403)


def scan_paths(base_url: str, paths: list, timeout: int, verify_tls: bool, user_agent: str, max_paths: int):
    session = requests.Session()
    session.headers.update({"User-Agent": user_agent})

    results = []
    for i, p in enumerate(paths[:max_paths], start=1):
        full = urljoin(base_url + "/", p.lstrip("/"))
        r, dt, err = safe_request(
            session,
            "GET",
            full,
            timeout=timeout,
            allow_redirects=False,
            verify=verify_tls,
        )

        entry = {
            "path": p,
            "url": full,
            "error": err,
            "ms": round(dt, 2),
        }

        if r is not None:
            entry.update({
                "status": r.status_code,
                "content_type": r.headers.get("Content-Type"),
                "content_length": r.headers.get("Content-Length"),
                "location": r.headers.get("Location"),
            })

            # Guardamos solo “señales”, no el contenido entero (más limpio y ético)
            if is_interesting_status(r.status_code):
                results.append(entry)
        else:
            # errores de red también son evidencia
            results.append(entry)

    return results


def head_base(base_url: str, timeout: int, verify_tls: bool, user_agent: str):
    session = requests.Session()
    session.headers.update({"User-Agent": user_agent})

    r, dt, err = safe_request(
        session,
        "HEAD",
        base_url,
        timeout=timeout,
        allow_redirects=False,
        verify=verify_tls,
    )

    if r is None:
        return {"error": err, "ms": round(dt, 2)}

    return {
        "status": r.status_code,
        "ms": round(dt, 2),
        "headers": dict(r.headers),
    }


def main():
    parser = argparse.ArgumentParser(
        description="ReconLite - Recon HTTP/OSINT ligero con mentalidad ciber (solo objetivos autorizados)."
    )
    parser.add_argument("target", help="URL o dominio (ej: https://example.com o example.com)")
    parser.add_argument("--timeout", type=int, default=8, help="Timeout por request en segundos (default: 8)")
    parser.add_argument("--insecure", action="store_true", help="No verificar TLS (NO recomendado)")
    parser.add_argument("--max-paths", type=int, default=40, help="Máximo de rutas a probar (default: 40)")
    parser.add_argument("--paths-file", help="Archivo de rutas (una por línea) para enumeración")
    parser.add_argument("--out", default="report.json", help="Ruta del reporte JSON (default: report.json)")
    parser.add_argument("--ua", default="ReconLite/1.0 (+educational)", help="User-Agent personalizado")

    args = parser.parse_args()

    try:
        base_url = normalize_url(args.target)
    except ValueError as e:
        print(f"[!] {e}", file=sys.stderr)
        sys.exit(1)

    verify_tls = not args.insecure

    paths = DEFAULT_PATHS
    if args.paths_file:
        try:
            with open(args.paths_file, "r", encoding="utf-8") as f:
                custom = [line.strip() for line in f if line.strip() and not line.strip().startswith("#")]
            # Normaliza para que siempre empiecen por "/"
            paths = [p if p.startswith("/") else "/" + p for p in custom]
        except OSError as e:
            print(f"[!] No se pudo leer paths-file: {e}", file=sys.stderr)
            sys.exit(1)

    report = {
        "target": base_url,
        "timestamp_utc": time.strftime("%Y-%m-%dT%H:%M:%SZ", time.gmtime()),
        "config": {
            "timeout_s": args.timeout,
            "verify_tls": verify_tls,
            "max_paths": args.max_paths,
            "user_agent": args.ua,
        },
        "base_head": {},
        "fingerprint": {},
        "security_headers": {},
        "findings": [],
    }

    # 1) HEAD base
    base_head = head_base(base_url, args.timeout, verify_tls, args.ua)
    report["base_head"] = base_head

    if "headers" in base_head:
        headers = base_head["headers"]
        report["fingerprint"] = extract_basic_fingerprint(headers)
        report["security_headers"] = analyze_security_headers(headers)

    # 2) Enumeración de rutas
    findings = scan_paths(base_url, paths, args.timeout, verify_tls, args.ua, args.max_paths)
    report["findings"] = findings

    # 3) Guardar reporte
    try:
        with open(args.out, "w", encoding="utf-8") as f:
            json.dump(report, f, indent=2, ensure_ascii=False)
    except OSError as e:
        print(f"[!] No se pudo escribir el reporte: {e}", file=sys.stderr)
        sys.exit(1)

    # Resumen consola
    interesting = [x for x in findings if x.get("status") is not None]
    print(f"✅ Objetivo: {base_url}")
    if "status" in base_head:
        print(f"✅ HEAD status: {base_head['status']} ({base_head['ms']} ms)")
    print(f"✅ Hallazgos guardados en: {args.out}")
    print(f"✅ Endpoints interesantes (con status): {len(interesting)}")


if __name__ == "__main__":
    main()

