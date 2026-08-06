-- 02_seed.sql
-- Autor: (pon aquí tu nombre)
-- Qué hace: inserta un usuario de ejemplo para poder probar el login
-- desde el primer momento, sin depender de que el registro ya funcione.
--
-- IMPORTANTE: el valor de "contrasena" debe ser un hash bcrypt, nunca
-- texto plano. Genera el tuyo así:
--   docker exec -it monitor_backend php db/generar_hash.php admin123
-- y reemplaza el valor de abajo por el hash que te imprima.

USE monitor_sistema;

INSERT INTO usuarios (nombre, correo, contrasena) VALUES
('Admin', 'admin@monitor.com', 'PEGA_AQUI_EL_HASH_GENERADO');
