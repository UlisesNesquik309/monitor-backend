<?php
/**
 * registro.php
 * Autor: (pon aquí tu nombre)
 * Qué hace: crea un usuario nuevo. Solo pide nombre, correo y contraseña.
 * Método esperado: POST
 * Body esperado: { "nombre": "...", "correo": "...", "contrasena": "..." }
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/utils/Response.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::noEncontrado('registro');
}

$body = json_decode(file_get_contents("php://input"), true);

$nombre     = isset($body['nombre']) ? trim($body['nombre']) : null;
$correo     = isset($body['correo']) ? trim($body['correo']) : null;
$contrasena = $body['contrasena'] ?? null;

if (!$nombre || !$correo || !$contrasena) {
    Response::error("REGISTRO_DATOS_INCOMPLETOS", "Nombre, correo y contraseña son obligatorios", 400);
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    Response::error("REGISTRO_CORREO_INVALIDO", "El correo no tiene un formato válido", 400);
}

try {
    $database = new Database();
    $conn = $database->conectar();

    // Verificar que el correo no exista ya
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = :correo LIMIT 1");
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();

    if ($stmt->fetch()) {
        Response::error("REGISTRO_CORREO_EXISTENTE", "Ya existe una cuenta con ese correo", 409);
    }

    $hash = password_hash($contrasena, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, contrasena) VALUES (:nombre, :correo, :contrasena)");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':contrasena', $hash);
    $stmt->execute();

    Response::success([
        "id"     => $conn->lastInsertId(),
        "nombre" => $nombre
    ], "Usuario registrado correctamente", 201);

} catch (Exception $e) {
    Response::error("REGISTRO_ERROR_SERVIDOR", "Error al registrar el usuario", 500);
}
