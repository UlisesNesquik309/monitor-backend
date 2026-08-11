<?php
/**
 * consultar_historial.php
 * Autor: (Jose Ulises Robledo Gutierrez)
 * Qué hace: consulta las lecturas guardadas entre una fecha inicial
 * y una fecha final, para el análisis histórico que pide el profesor.
 * Método esperado: GET
 * Query params esperados: ?fecha_inicio=2026-02-10 14:00:00&fecha_fin=2026-02-10 15:00:00
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../utils/Response.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    Response::noEncontrado('consultar_historial');
}

$fechaInicio = $_GET['fecha_inicio'] ?? null;
$fechaFin    = $_GET['fecha_fin']    ?? null;

if (!$fechaInicio || !$fechaFin) {
    Response::error("HISTORIAL_DATOS_INCOMPLETOS", "Se requieren fecha_inicio y fecha_fin", 400);
}

try {
    $database = new Database();
    $conn = $database->conectar();

    $stmt = $conn->prepare(
        "SELECT cpu, ram, disco, fecha_hora
         FROM lecturas
         WHERE fecha_hora BETWEEN :inicio AND :fin
         ORDER BY fecha_hora ASC"
    );
    $stmt->bindParam(':inicio', $fechaInicio);
    $stmt->bindParam(':fin', $fechaFin);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Response::success($resultados, "Consulta realizada correctamente");

} catch (Exception $e) {
    Response::error("HISTORIAL_ERROR_CONSULTA", "Error al consultar el historial", 500);
}
