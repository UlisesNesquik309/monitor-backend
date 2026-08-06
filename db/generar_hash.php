<?php
/**
 * generar_hash.php
 * Uso: php generar_hash.php tu_contrasena
 * Esto imprime el hash bcrypt que debes pegar en 02_seed.sql
 * (o usarlo para crear tu primer usuario a mano en la BD).
 * No se sube a producción, es solo una herramienta de apoyo.
 */

if ($argc < 2) {
    echo "Uso: php generar_hash.php tu_contrasena\n";
    exit(1);
}

echo password_hash($argv[1], PASSWORD_BCRYPT) . "\n";
