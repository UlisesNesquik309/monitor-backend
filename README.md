# Backend — Monitor de Recursos del Sistema

## Estructura

```
backend/
├── config/
│   └── database.php          # Conexión PDO a MySQL
├── utils/
│   └── Response.php          # Formato estándar de respuesta JSON
├── modulos/
│   └── monitor/
│       ├── obtener_lectura.php      # GET  -> lectura actual (ejecuta monitor.py)
│       ├── guardar_lectura.php      # POST -> guarda una lectura en la BD
│       └── consultar_historial.php # GET  -> historial por rango de fechas
├── python/
│   ├── monitor.py             # Lee CPU/RAM/Disco con psutil
│   └── requirements.txt
├── db/
│   ├── 01_schema.sql           # Creación de la BD y tablas
│   ├── 02_seed.sql             # Usuario de ejemplo (inserciones iniciales)
│   └── generar_hash.php        # Utilidad para generar el hash de una contraseña
├── login.php                   # POST -> login (correo + contrasena)
├── registro.php                # POST -> registro (nombre + correo + contrasena)
├── docker-compose.yml
├── Dockerfile
└── .gitignore
```

## Cómo levantarlo (con Docker)

1. Genera el hash de la contraseña de tu usuario de prueba y pégalo en `db/02_seed.sql`:
   ```
   php db/generar_hash.php admin123
   ```
   (si no tienes PHP local, levanta primero el contenedor y corre el comando dentro con `docker exec`)

2. Desde la carpeta `backend/`:
   ```
   docker compose up -d --build
   ```
   Esto levanta:
   - MySQL en el puerto `3306` (crea la BD automáticamente con `01_schema.sql` y `02_seed.sql`)
   - PHP + Apache en `http://localhost:8080`

3. Prueba en Postman:
   - `POST http://localhost:8080/login.php`
     Body (JSON): `{ "correo": "admin@monitor.com", "contrasena": "admin123" }`
   - `GET http://localhost:8080/modulos/monitor/obtener_lectura.php`
   - `POST http://localhost:8080/modulos/monitor/guardar_lectura.php`
     Body (JSON): `{ "cpu": 32.3, "ram": 48.1, "disco": 17.5 }`
   - `GET http://localhost:8080/modulos/monitor/consultar_historial.php?fecha_inicio=2026-02-10 00:00:00&fecha_fin=2026-02-11 00:00:00`

## Casos de prueba a documentar en Postman (para tu entrega)

Para cada endpoint, guarda en Postman al menos 2 casos:
- **Caso correcto**: body válido → capturar el `estado: true` y el `data` que regresa.
- **Caso de error**: falta un campo o el dato es inválido → capturar el `estado: false`, el `error` y el `mensaje`.

Esto es justo lo que te pide el profesor documentar en el archivo 1 (backend + diagrama E-R + JSON de Postman).

## Diagrama E-R

Con las 2 tablas de `01_schema.sql` (`usuarios` y `lecturas`) arma el diagrama en Lucidchart:
- `usuarios (id PK, nombre, correo, contrasena, creado_en)`
- `lecturas (id PK, cpu, ram, disco, fecha_hora)`

No hay relación FK entre ellas en esta versión (el monitor es del sistema completo, no por usuario). Si tu profesor pide que cada lectura quede ligada al usuario que inició sesión, avísame y te ayudo a agregar la llave foránea `usuario_id` en `lecturas`.
