<?php
/**
 * obtener_lectura.php
 * Autor: (Jose Ulises Robledo Gutierrez)
 * Qué hace: ejecuta el script de Python (monitor.py), que lee el uso
 * de CPU, RAM y disco en tiempo real, y regresa esos datos a React
 * en formato JSON. NO guarda nada en la BD, solo lee y regresa.
 * Método esperado: GET
 */

require_once __DIR__ . '/../../utils/Response.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    Response::noEncontrado('obtener_lectura');
}

try {
    $rutaScript = escapeshellarg(__DIR__ . '/../../python/monitor.py');
    $salida = shell_exec("python3 {$rutaScript} 2>&1");

    $datos = json_decode($salida, true);

    if (!is_array($datos) || !isset($datos['cpu'], $datos['ram'], $datos['disco'])) {
        Response::error("MONITOR_SIN_DATOS", "No se pudo obtener la lectura del sistema", 500);
    }

    Response::success($datos, "Lectura obtenida correctamente");

} catch (Exception $e) {
    Response::error("MONITOR_ERROR_LECTURA", "Error al ejecutar el script de monitoreo", 500);
}
