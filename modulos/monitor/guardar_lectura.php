<?php
/**
 * guardar_lectura.php
 * Autor: (pon aquí tu nombre)
 * Qué hace: recibe desde React los valores de cpu, ram y disco
 * (los mismos que ya se mostraron en la gráfica) y los inserta
 * en la tabla "lecturas" para poder consultarlos después por fecha.
 * Método esperado: POST
 * Body esperado: { "cpu": 32.3, "ram": 48.1, "disco": 17.5 }
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../utils/Response.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::noEncontrado('guardar_lectura');
}

$body = json_decode(file_get_contents("php://input"), true);

$cpu    = $body['cpu']    ?? null;
$ram    = $body['ram']    ?? null;
$disco  = $body['disco']  ?? null;

if ($cpu === null || $ram === null || $disco === null) {
    Response::error("LECTURA_DATOS_INCOMPLETOS", "Se requieren cpu, ram y disco", 400);
}

if (!is_numeric($cpu) || !is_numeric($ram) || !is_numeric($disco)) {
    Response::error("LECTURA_DATOS_INVALIDOS", "cpu, ram y disco deben ser numéricos", 400);
}

try {
    $database = new Database();
    $conn = $database->conectar();

    $stmt = $conn->prepare("INSERT INTO lecturas (cpu, ram, disco) VALUES (:cpu, :ram, :disco)");
    $stmt->bindParam(':cpu', $cpu);
    $stmt->bindParam(':ram', $ram);
    $stmt->bindParam(':disco', $disco);
    $stmt->execute();

    Response::success(["id" => $conn->lastInsertId()], "Lectura guardada correctamente", 201);

} catch (Exception $e) {
    Response::error("LECTURA_ERROR_GUARDAR", "Error al guardar la lectura", 500);
}
